<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Sphinx Search Ultimate
 * @version   2.3.4
 * @build     1372
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */



//
// $Id: sphinxapi.php 1365 2008-07-15 00:33:22Z shodan $
//

//
// Copyright (c) 2001-2008, Andrew Aksyonoff. All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License. You should have
// received a copy of the GPL license along with this program; if you
// did not, you can find it at http://www.gnu.org/
//

/////////////////////////////////////////////////////////////////////////////
// PHP version of Sphinx searchd client (PHP API)
/////////////////////////////////////////////////////////////////////////////

/// known searchd commands
define('SEARCHD_COMMAND_SEARCH',    0);
define('SEARCHD_COMMAND_EXCERPT',    1);
define('SEARCHD_COMMAND_UPDATE',    2);
define('SEARCHD_COMMAND_KEYWORDS', 3);

/// current client-side command implementation versions
define('VER_COMMAND_SEARCH',        0x113);
define('VER_COMMAND_EXCERPT',        0x100);
define('VER_COMMAND_UPDATE',        0x101);
define('VER_COMMAND_KEYWORDS',    0x100);

/// known searchd status codes
define('SEARCHD_OK',                0);
define('SEARCHD_ERROR',            1);
define('SEARCHD_RETRY',            2);
define('SEARCHD_WARNING',            3);

/// known match modes
define('SPH_MATCH_ALL',            0);
define('SPH_MATCH_ANY',            1);
define('SPH_MATCH_PHRASE',        2);
define('SPH_MATCH_BOOLEAN',        3);
define('SPH_MATCH_EXTENDED',        4);
define('SPH_MATCH_FULLSCAN',        5);
define('SPH_MATCH_EXTENDED2',        6);    // extended engine V2 (TEMPORARY, WILL BE REMOVED)

/// known ranking modes (ext2 only)
define('SPH_RANK_PROXIMITY_BM25',    0);    ///< default mode, phrase proximity major factor and BM25 minor one
define('SPH_RANK_BM25',            1);    ///< statistical mode, BM25 ranking only (faster but worse quality)
define('SPH_RANK_NONE',            2);    ///< no ranking, all matches get a weight of 1
define('SPH_RANK_WORDCOUNT',        3);    ///< simple word-count weighting, rank is a weighted sum of per-field keyword occurence counts

/// known sort modes
define('SPH_SORT_RELEVANCE',        0);
define('SPH_SORT_ATTR_DESC',        1);
define('SPH_SORT_ATTR_ASC',        2);
define('SPH_SORT_TIME_SEGMENTS',     3);
define('SPH_SORT_EXTENDED',         4);
define('SPH_SORT_EXPR',             5);

/// known filter types
define('SPH_FILTER_VALUES',        0);
define('SPH_FILTER_RANGE',        1);
define('SPH_FILTER_FLOATRANGE',    2);

/// known attribute types
define('SPH_ATTR_INTEGER',        1);
define('SPH_ATTR_TIMESTAMP',        2);
define('SPH_ATTR_ORDINAL',        3);
define('SPH_ATTR_BOOL',            4);
define('SPH_ATTR_FLOAT',            5);
define('SPH_ATTR_MULTI',            0x40000000);

/// known grouping functions
define('SPH_GROUPBY_DAY',            0);
define('SPH_GROUPBY_WEEK',        1);
define('SPH_GROUPBY_MONTH',        2);
define('SPH_GROUPBY_YEAR',        3);
define('SPH_GROUPBY_ATTR',        4);
define('SPH_GROUPBY_ATTRPAIR',    5);

/// portably pack numeric to 64 unsigned bits, network order
function sphPack64($v)
{
    assert(is_numeric($v));

    // x64 route
    if (PHP_INT_SIZE >= 8) {
        $i = (int) $v;

        return pack('NN', $i >> 32, $i & ((1 << 32) - 1));
    }

    // x32 route, bcmath
    $x = '4294967296';
    if (function_exists('bcmul')) {
        $h = bcdiv($v, $x, 0);
        $l = bcmod($v, $x);

        return pack('NN', (float) $h, (float) $l); // conversion to float is intentional; int would lose 31st bit
    }

    // x32 route, 15 or less decimal digits
    // we can use float, because its actually double and has 52 precision bits
    if (strlen($v) <= 15) {
        $f = (float) $v;
        $h = (int) ($f / $x);
        $l = (int) ($f - $x * $h);

        return pack('NN', $h, $l);
    }

    // x32 route, 16 or more decimal digits
    // well, let me know if you *really* need this
    die('INTERNAL ERROR: packing more than 15-digit numeric on 32-bit PHP is not implemented yet (contact support)');
}

/// portably unpack 64 unsigned bits, network order to numeric
function sphUnpack64($v)
{
    list($h, $l) = array_values(unpack('N*N*', $v));

    // x64 route
    if (PHP_INT_SIZE >= 8) {
        if ($h < 0) {
            $h += (1 << 32);
        } // because php 5.2.2 to 5.2.5 is totally fucked up again
        if ($l < 0) {
            $l += (1 << 32);
        }

        return ($h << 32) + $l;
    }

    // x32 route
    $h = sprintf('%u', $h);
    $l = sprintf('%u', $l);
    $x = '4294967296';

    // bcmath
    if (function_exists('bcmul')) {
        return bcadd($l, bcmul($x, $h));
    }

    // no bcmath, 15 or less decimal digits
    // we can use float, because its actually double and has 52 precision bits
    if ($h < 1048576) {
        $f = ((float) $h) * $x + (float) $l;

        return sprintf('%.0f', $f); // builtin conversion is only about 39-40 bits precise!
    }

    // x32 route, 16 or more decimal digits
    // well, let me know if you *really* need this
    die('INTERNAL ERROR: unpacking more than 15-digit numeric on 32-bit PHP is not implemented yet (contact support)');
}

/// sphinx searchd client class
class SphinxClient
{
    public $_host;            ///< searchd host (default is "localhost")
    public $_port;            ///< searchd port (default is 3312)
    public $_offset;        ///< how many records to seek from result-set start (default is 0)
    public $_limit;        ///< how many records to return from result-set starting at offset (default is 20)
    public $_mode;            ///< query matching mode (default is SPH_MATCH_ALL)
    public $_weights;        ///< per-field weights (default is 1 for all fields)
    public $_sort;            ///< match sorting mode (default is SPH_SORT_RELEVANCE)
    public $_sortby;        ///< attribute to sort by (defualt is "")
    public $_min_id;        ///< min ID to match (default is 0, which means no limit)
    public $_max_id;        ///< max ID to match (default is 0, which means no limit)
    public $_filters;        ///< search filters
    public $_groupby;        ///< group-by attribute name
    public $_groupfunc;    ///< group-by function (to pre-process group-by attribute value with)
    public $_groupsort;    ///< group-by sorting clause (to sort groups in result set with)
    public $_groupdistinct;///< group-by count-distinct attribute
    public $_maxmatches;    ///< max matches to retrieve
    public $_cutoff;        ///< cutoff to stop searching at (default is 0)
    public $_retrycount;    ///< distributed retries count
    public $_retrydelay;    ///< distributed retries delay
    public $_anchor;        ///< geographical anchor point
    public $_indexweights;    ///< per-index weights
    public $_ranker;        ///< ranking mode (default is SPH_RANK_PROXIMITY_BM25)
    public $_maxquerytime;    ///< max query time, milliseconds (default is 0, do not limit)
    public $_fieldweights;    ///< per-field-name weights

    public $_error;        ///< last error message
    public $_warning;        ///< last warning message

    public $_reqs;            ///< requests array for multi-query
    public $_mbenc;        ///< stored mbstring encoding
    public $_arrayresult;    ///< whether $result["matches"] should be a hash or an array
    public $_timeout;        ///< connect timeout

    /////////////////////////////////////////////////////////////////////////////
    // common stuff
    /////////////////////////////////////////////////////////////////////////////

    /// create a new client object and fill defaults
    public function __construct()
    {
        // per-client-object settings
        $this->_host = 'localhost';
        $this->_port = 3312;

        // per-query settings
        $this->_offset = 0;
        $this->_limit = 20;
        $this->_mode = SPH_MATCH_ALL;
        $this->_weights = array();
        $this->_sort = SPH_SORT_RELEVANCE;
        $this->_sortby = '';
        $this->_min_id = 0;
        $this->_max_id = 0;
        $this->_filters = array();
        $this->_groupby = '';
        $this->_groupfunc = SPH_GROUPBY_DAY;
        $this->_groupsort = '@group desc';
        $this->_groupdistinct = '';
        $this->_maxmatches = 1000;
        $this->_cutoff = 0;
        $this->_retrycount = 0;
        $this->_retrydelay = 0;
        $this->_anchor = array();
        $this->_indexweights = array();
        $this->_ranker = SPH_RANK_PROXIMITY_BM25;
        $this->_maxquerytime = 0;
        $this->_fieldweights = array();

        $this->_error = ''; // per-reply fields (for single-query case)
        $this->_warning = '';
        $this->_reqs = array();    // requests storage (for multi-query case)
        $this->_mbenc = '';
        $this->_arrayresult = false;
        $this->_timeout = 0;
    }

    /// get last error message (string)
    public function GetLastError()
    {
        return $this->_error;
    }

    /// get last warning message (string)
    public function GetLastWarning()
    {
        return $this->_warning;
    }

    /// set searchd host name (string) and port (integer)
    public function SetServer($host, $port)
    {
        assert(is_string($host));
        assert(is_int($port));
        $this->_host = $host;
        $this->_port = $port;
    }

    /// set server connection timeout (0 to remove)
    public function SetConnectTimeout($timeout)
    {
        assert(is_numeric($timeout));
        $this->_timeout = $timeout;
    }

    /////////////////////////////////////////////////////////////////////////////

    /// enter mbstring workaround mode
    public function _MBPush()
    {
        $this->_mbenc = '';
        if (ini_get('mbstring.func_overload') & 2) {
            $this->_mbenc = mb_internal_encoding();
            mb_internal_encoding('latin1');
        }
    }

    /// leave mbstring workaround mode
    public function _MBPop()
    {
        if ($this->_mbenc) {
            mb_internal_encoding($this->_mbenc);
        }
    }

    /// connect to searchd server
    public function _Connect()
    {
        $errno = 0;
        $errstr = '';
        if ($this->_timeout <= 0) {
            $fp = @fsockopen($this->_host, $this->_port, $errno, $errstr);
        } else {
            $fp = @fsockopen($this->_host, $this->_port, $errno, $errstr, $this->_timeout);
        }

        if (!$fp) {
            $errstr = trim($errstr);
            $this->_error = "connection to {$this->_host}:{$this->_port} failed (errno=$errno, msg=$errstr)";

            return false;
        }

        // check version
        list(, $v) = unpack('N*', fread($fp, 4));
        $v = (int) $v;
        if ($v < 1) {
            fclose($fp);
            $this->_error = "expected searchd protocol version 1+, got version '$v'";

            return false;
        }

        // all ok, send my version
        fwrite($fp, pack('N', 1));

        return $fp;
    }

    /// get and check response packet from searchd server
    public function _GetResponse($fp, $client_ver)
    {
        $response = '';
        $len = 0;

        $header = fread($fp, 8);
        if (strlen($header) == 8) {
            list($status, $ver, $len) = array_values(unpack('n2a/Nb', $header));
            $left = $len;
            while ($left > 0 && !feof($fp)) {
                $chunk = fread($fp, $left);
                if ($chunk) {
                    $response .= $chunk;
                    $left -= strlen($chunk);
                }
            }
        }
        fclose($fp);

        // check response
        $read = strlen($response);
        if (!$response || $read != $len) {
            $this->_error = $len
                ? "failed to read searchd response (status=$status, ver=$ver, len=$len, read=$read)"
                : 'received zero-sized searchd response';

            return false;
        }

        // check status
        if ($status == SEARCHD_WARNING) {
            list(, $wlen) = unpack('N*', substr($response, 0, 4));
            $this->_warning = substr($response, 4, $wlen);

            return substr($response, 4 + $wlen);
        }
        if ($status == SEARCHD_ERROR) {
            $this->_error = 'searchd error: '.substr($response, 4);

            return false;
        }
        if ($status == SEARCHD_RETRY) {
            $this->_error = 'temporary searchd error: '.substr($response, 4);

            return false;
        }
        if ($status != SEARCHD_OK) {
            $this->_error = "unknown status code '$status'";

            return false;
        }

        // check version
        if ($ver < $client_ver) {
            $this->_warning = sprintf("searchd command v.%d.%d older than client's v.%d.%d, some options might not work",
                $ver >> 8, $ver & 0xff, $client_ver >> 8, $client_ver & 0xff);
        }

        return $response;
    }

    /////////////////////////////////////////////////////////////////////////////
    // searching
    /////////////////////////////////////////////////////////////////////////////

    /// set offset and count into result set,
    /// and optionally set max-matches and cutoff limits
    public function SetLimits($offset, $limit, $max = 0, $cutoff = 0)
    {
        assert(is_int($offset));
        assert(is_int($limit));
        assert($offset >= 0);
        assert($limit > 0);
        assert($max >= 0);
        $this->_offset = $offset;
        $this->_limit = $limit;
        if ($max > 0) {
            $this->_maxmatches = $max;
        }
        if ($cutoff > 0) {
            $this->_cutoff = $cutoff;
        }
    }

    /// set maximum query time, in milliseconds, per-index
    /// integer, 0 means "do not limit"
    public function SetMaxQueryTime($max)
    {
        assert(is_int($max));
        assert($max >= 0);
        $this->_maxquerytime = $max;
    }

    /// set matching mode
    public function SetMatchMode($mode)
    {
        assert($mode == SPH_MATCH_ALL
            || $mode == SPH_MATCH_ANY
            || $mode == SPH_MATCH_PHRASE
            || $mode == SPH_MATCH_BOOLEAN
            || $mode == SPH_MATCH_EXTENDED
            || $mode == SPH_MATCH_FULLSCAN
            || $mode == SPH_MATCH_EXTENDED2);
        $this->_mode = $mode;
    }

    /// set ranking mode
    public function SetRankingMode($ranker)
    {
        assert($ranker == SPH_RANK_PROXIMITY_BM25
            || $ranker == SPH_RANK_BM25
            || $ranker == SPH_RANK_NONE
            || $ranker == SPH_RANK_WORDCOUNT);
        $this->_ranker = $ranker;
    }

    /// set matches sorting mode
    public function SetSortMode($mode, $sortby = '')
    {
        assert(
            $mode == SPH_SORT_RELEVANCE ||
            $mode == SPH_SORT_ATTR_DESC ||
            $mode == SPH_SORT_ATTR_ASC ||
            $mode == SPH_SORT_TIME_SEGMENTS ||
            $mode == SPH_SORT_EXTENDED ||
            $mode == SPH_SORT_EXPR);
        assert(is_string($sortby));
        assert($mode == SPH_SORT_RELEVANCE || strlen($sortby) > 0);

        $this->_sort = $mode;
        $this->_sortby = $sortby;
    }

    /// bind per-field weights by order
    /// DEPRECATED; use SetFieldWeights() instead
    public function SetWeights($weights)
    {
        assert(is_array($weights));
        foreach ($weights as $weight) {
            assert(is_int($weight));
        }

        $this->_weights = $weights;
    }

    /// bind per-field weights by name
    public function SetFieldWeights($weights)
    {
        $this->_fieldweights = $weights;
    }

    /// bind per-index weights by name
    public function SetIndexWeights($weights)
    {
        assert(is_array($weights));
        foreach ($weights as $index => $weight) {
            assert(is_string($index));
            assert(is_int($weight));
        }
        $this->_indexweights = $weights;
    }

    /// set IDs range to match
    /// only match records if document ID is beetwen $min and $max (inclusive)
    public function SetIDRange($min, $max)
    {
        assert(is_numeric($min));
        assert(is_numeric($max));
        assert($min <= $max);
        $this->_min_id = $min;
        $this->_max_id = $max;
    }

    /// set values set filter
    /// only match records where $attribute value is in given set
    public function SetFilter($attribute, $values, $exclude = false)
    {
        assert(is_string($attribute));
        assert(is_array($values));
        assert(count($values));

        if (is_array($values) && count($values)) {
            foreach ($values as $value) {
                assert(is_numeric($value));
            }

            $this->_filters[] = array('type' => SPH_FILTER_VALUES, 'attr' => $attribute, 'exclude' => $exclude, 'values' => $values);
        }
    }

    /// set range filter
    /// only match records if $attribute value is beetwen $min and $max (inclusive)
    public function SetFilterRange($attribute, $min, $max, $exclude = false)
    {
        assert(is_string($attribute));
        assert(is_int($min));
        assert(is_int($max));
        assert($min <= $max);

        $this->_filters[] = array('type' => SPH_FILTER_RANGE, 'attr' => $attribute, 'exclude' => $exclude, 'min' => $min, 'max' => $max);
    }

    /// set float range filter
    /// only match records if $attribute value is beetwen $min and $max (inclusive)
    public function SetFilterFloatRange($attribute, $min, $max, $exclude = false)
    {
        assert(is_string($attribute));
        assert(is_float($min));
        assert(is_float($max));
        assert($min <= $max);

        $this->_filters[] = array('type' => SPH_FILTER_FLOATRANGE, 'attr' => $attribute, 'exclude' => $exclude, 'min' => $min, 'max' => $max);
    }

    /// setup anchor point for geosphere distance calculations
    /// required to use @geodist in filters and sorting
    /// latitude and longitude must be in radians
    public function SetGeoAnchor($attrlat, $attrlong, $lat, $long)
    {
        assert(is_string($attrlat));
        assert(is_string($attrlong));
        assert(is_float($lat));
        assert(is_float($long));

        $this->_anchor = array('attrlat' => $attrlat, 'attrlong' => $attrlong, 'lat' => $lat, 'long' => $long);
    }

    /// set grouping attribute and function
    public function SetGroupBy($attribute, $func, $groupsort = '@group desc')
    {
        assert(is_string($attribute));
        assert(is_string($groupsort));
        assert($func == SPH_GROUPBY_DAY
            || $func == SPH_GROUPBY_WEEK
            || $func == SPH_GROUPBY_MONTH
            || $func == SPH_GROUPBY_YEAR
            || $func == SPH_GROUPBY_ATTR
            || $func == SPH_GROUPBY_ATTRPAIR);

        $this->_groupby = $attribute;
        $this->_groupfunc = $func;
        $this->_groupsort = $groupsort;
    }

    /// set count-distinct attribute for group-by queries
    public function SetGroupDistinct($attribute)
    {
        assert(is_string($attribute));
        $this->_groupdistinct = $attribute;
    }

    /// set distributed retries count and delay
    public function SetRetries($count, $delay = 0)
    {
        assert(is_int($count) && $count >= 0);
        assert(is_int($delay) && $delay >= 0);
        $this->_retrycount = $count;
        $this->_retrydelay = $delay;
    }

    /// set result set format (hash or array; hash by default)
    /// PHP specific; needed for group-by-MVA result sets that may contain duplicate IDs
    public function SetArrayResult($arrayresult)
    {
        assert(is_bool($arrayresult));
        $this->_arrayresult = $arrayresult;
    }

    //////////////////////////////////////////////////////////////////////////////

    /// clear all filters (for multi-queries)
    public function ResetFilters()
    {
        $this->_filters = array();
        $this->_anchor = array();
    }

    /// clear groupby settings (for multi-queries)
    public function ResetGroupBy()
    {
        $this->_groupby = '';
        $this->_groupfunc = SPH_GROUPBY_DAY;
        $this->_groupsort = '@group desc';
        $this->_groupdistinct = '';
    }

    //////////////////////////////////////////////////////////////////////////////

    /// connect to searchd server, run given search query through given indexes,
    /// and return the search results
    public function Query($query, $index = '*', $comment = '')
    {
        assert(empty($this->_reqs));

        $this->AddQuery($query, $index, $comment);
        $results = $this->RunQueries();
        $this->_reqs = array(); // just in case it failed too early

        if (!is_array($results)) {
            return false;
        } // probably network error; error message should be already filled

        $this->_error = $results[0]['error'];
        $this->_warning = $results[0]['warning'];
        if ($results[0]['status'] == SEARCHD_ERROR) {
            return false;
        } else {
            return $results[0];
        }
    }

    /// helper to pack floats in network byte order
    public function _PackFloat($f)
    {
        $t1 = pack('f', $f); // machine order
        list(, $t2) = unpack('L*', $t1); // int in machine order
        return pack('N', $t2);
    }

    /// add query to multi-query batch
    /// returns index into results array from RunQueries() call
    public function AddQuery($query, $index = '*', $comment = '')
    {
        // mbstring workaround
        $this->_MBPush();

        // build request
        $req = pack('NNNNN', $this->_offset, $this->_limit, $this->_mode, $this->_ranker, $this->_sort); // mode and limits
        $req .= pack('N', strlen($this->_sortby)).$this->_sortby;
        $req .= pack('N', strlen($query)).$query; // query itself
        $req .= pack('N', count($this->_weights)); // weights
        foreach ($this->_weights as $weight) {
            $req .= pack('N', (int) $weight);
        }
        $req .= pack('N', strlen($index)).$index; // indexes
        $req .= pack('N', 1); // id64 range marker
        $req .= sphPack64($this->_min_id).sphPack64($this->_max_id); // id64 range

        // filters
        $req .= pack('N', count($this->_filters));
        foreach ($this->_filters as $filter) {
            $req .= pack('N', strlen($filter['attr'])).$filter['attr'];
            $req .= pack('N', $filter['type']);
            switch ($filter['type']) {
                case SPH_FILTER_VALUES:
                    $req .= pack('N', count($filter['values']));
                    foreach ($filter['values'] as $value) {
                        $req .= pack('N', floatval($value));
                    } // this uberhack is to workaround 32bit signed int limit on x32 platforms
                    break;

                case SPH_FILTER_RANGE:
                    $req .= pack('NN', $filter['min'], $filter['max']);
                    break;

                case SPH_FILTER_FLOATRANGE:
                    $req .= $this->_PackFloat($filter['min']).$this->_PackFloat($filter['max']);
                    break;

                default:
                    assert(0 && 'internal error: unhandled filter type');
            }
            $req .= pack('N', $filter['exclude']);
        }

        // group-by clause, max-matches count, group-sort clause, cutoff count
        $req .= pack('NN', $this->_groupfunc, strlen($this->_groupby)).$this->_groupby;
        $req .= pack('N', $this->_maxmatches);
        $req .= pack('N', strlen($this->_groupsort)).$this->_groupsort;
        $req .= pack('NNN', $this->_cutoff, $this->_retrycount, $this->_retrydelay);
        $req .= pack('N', strlen($this->_groupdistinct)).$this->_groupdistinct;

        // anchor point
        if (empty($this->_anchor)) {
            $req .= pack('N', 0);
        } else {
            $a = &$this->_anchor;
            $req .= pack('N', 1);
            $req .= pack('N', strlen($a['attrlat'])).$a['attrlat'];
            $req .= pack('N', strlen($a['attrlong'])).$a['attrlong'];
            $req .= $this->_PackFloat($a['lat']).$this->_PackFloat($a['long']);
        }

        // per-index weights
        $req .= pack('N', count($this->_indexweights));
        foreach ($this->_indexweights as $idx => $weight) {
            $req .= pack('N', strlen($idx)).$idx.pack('N', $weight);
        }

        // max query time
        $req .= pack('N', $this->_maxquerytime);

        // per-field weights
        $req .= pack('N', count($this->_fieldweights));
        foreach ($this->_fieldweights as $field => $weight) {
            $req .= pack('N', strlen($field)).$field.pack('N', $weight);
        }

        // comment
        $req .= pack('N', strlen($comment)).$comment;

        // mbstring workaround
        $this->_MBPop();

        // store request to requests array
        $this->_reqs[] = $req;

        return count($this->_reqs) - 1;
    }

    /// connect to searchd, run queries batch, and return an array of result sets
    public function RunQueries()
    {
        if (empty($this->_reqs)) {
            $this->_error = 'no queries defined, issue AddQuery() first';

            return false;
        }

        // mbstring workaround
        $this->_MBPush();

        if (!($fp = $this->_Connect())) {
            $this->_MBPop();

            return false;
        }

        ////////////////////////////
        // send query, get response
        ////////////////////////////

        $nreqs = count($this->_reqs);
        $req = implode('', $this->_reqs);
        $len = 4 + strlen($req);
        $req = pack('nnNN', SEARCHD_COMMAND_SEARCH, VER_COMMAND_SEARCH, $len, $nreqs).$req; // add header

        fwrite($fp, $req, $len + 8);
        if (!($response = $this->_GetResponse($fp, VER_COMMAND_SEARCH))) {
            $this->_MBPop();

            return false;
        }

        $this->_reqs = array();

        //////////////////
        // parse response
        //////////////////

        $p = 0; // current position
        $max = strlen($response); // max position for checks, to protect against broken responses

        $results = array();
        for ($ires = 0; $ires < $nreqs && $p < $max; ++$ires) {
            $results[] = array();
            $result = &$results[$ires];

            $result['error'] = '';
            $result['warning'] = '';

            // extract status
            list(, $status) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            $result['status'] = $status;
            if ($status != SEARCHD_OK) {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $message = substr($response, $p, $len);
                $p += $len;

                if ($status == SEARCHD_WARNING) {
                    $result['warning'] = $message;
                } else {
                    $result['error'] = $message;
                    continue;
                }
            }

            // read schema
            $fields = array();
            $attrs = array();

            list(, $nfields) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            while ($nfields-- > 0 && $p < $max) {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $fields[] = substr($response, $p, $len);
                $p += $len;
            }
            $result['fields'] = $fields;

            list(, $nattrs) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            while ($nattrs-- > 0 && $p < $max) {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $attr = substr($response, $p, $len);
                $p += $len;
                list(, $type) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $attrs[$attr] = $type;
            }
            $result['attrs'] = $attrs;

            // read match count
            list(, $count) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            list(, $id64) = unpack('N*', substr($response, $p, 4));
            $p += 4;

            // read matches
            $idx = -1;
            while ($count-- > 0 && $p < $max) {
                // index into result array
                ++$idx;

                // parse document id and weight
                if ($id64) {
                    $doc = sphUnpack64(substr($response, $p, 8));
                    $p += 8;
                    list(, $weight) = unpack('N*', substr($response, $p, 4));
                    $p += 4;
                } else {
                    list($doc, $weight) = array_values(unpack('N*N*',
                        substr($response, $p, 8)));
                    $p += 8;

                    if (PHP_INT_SIZE >= 8) {
                        // x64 route, workaround broken unpack() in 5.2.2+
                        if ($doc < 0) {
                            $doc += (1 << 32);
                        }
                    } else {
                        // x32 route, workaround php signed/unsigned braindamage
                        $doc = sprintf('%u', $doc);
                    }
                }
                $weight = sprintf('%u', $weight);

                // create match entry
                if ($this->_arrayresult) {
                    $result['matches'][$idx] = array('id' => $doc, 'weight' => $weight);
                } else {
                    $result['matches'][$doc]['weight'] = $weight;
                }

                // parse and create attributes
                $attrvals = array();
                foreach ($attrs as $attr => $type) {
                    // handle floats
                    if ($type == SPH_ATTR_FLOAT) {
                        list(, $uval) = unpack('N*', substr($response, $p, 4));
                        $p += 4;
                        list(, $fval) = unpack('f*', pack('L', $uval));
                        $attrvals[$attr] = $fval;
                        continue;
                    }

                    // handle everything else as unsigned ints
                    list(, $val) = unpack('N*', substr($response, $p, 4));
                    $p += 4;
                    if ($type & SPH_ATTR_MULTI) {
                        $attrvals[$attr] = array();
                        $nvalues = $val;
                        while ($nvalues-- > 0 && $p < $max) {
                            list(, $val) = unpack('N*', substr($response, $p, 4));
                            $p += 4;
                            $attrvals[$attr][] = sprintf('%u', $val);
                        }
                    } else {
                        $attrvals[$attr] = sprintf('%u', $val);
                    }
                }

                if ($this->_arrayresult) {
                    $result['matches'][$idx]['attrs'] = $attrvals;
                } else {
                    $result['matches'][$doc]['attrs'] = $attrvals;
                }
            }

            list($total, $total_found, $msecs, $words) =
                array_values(unpack('N*N*N*N*', substr($response, $p, 16)));
            $result['total'] = sprintf('%u', $total);
            $result['total_found'] = sprintf('%u', $total_found);
            $result['time'] = sprintf('%.3f', $msecs / 1000);
            $p += 16;

            while ($words-- > 0 && $p < $max) {
                list(, $len) = unpack('N*', substr($response, $p, 4));
                $p += 4;
                $word = substr($response, $p, $len);
                $p += $len;
                list($docs, $hits) = array_values(unpack('N*N*', substr($response, $p, 8)));
                $p += 8;
                $result['words'][$word] = array(
                    'docs' => sprintf('%u', $docs),
                    'hits' => sprintf('%u', $hits), );
            }
        }

        $this->_MBPop();

        return $results;
    }

    /////////////////////////////////////////////////////////////////////////////
    // excerpts generation
    /////////////////////////////////////////////////////////////////////////////

    /// connect to searchd server, and generate exceprts (snippets)
    /// of given documents for given query. returns false on failure,
    /// an array of snippets on success
    public function BuildExcerpts($docs, $index, $words, $opts = array())
    {
        assert(is_array($docs));
        assert(is_string($index));
        assert(is_string($words));
        assert(is_array($opts));

        $this->_MBPush();

        if (!($fp = $this->_Connect())) {
            $this->_MBPop();

            return false;
        }

        /////////////////
        // fixup options
        /////////////////

        if (!isset($opts['before_match'])) {
            $opts['before_match'] = '<b>';
        }
        if (!isset($opts['after_match'])) {
            $opts['after_match'] = '</b>';
        }
        if (!isset($opts['chunk_separator'])) {
            $opts['chunk_separator'] = ' ... ';
        }
        if (!isset($opts['limit'])) {
            $opts['limit'] = 256;
        }
        if (!isset($opts['around'])) {
            $opts['around'] = 5;
        }
        if (!isset($opts['exact_phrase'])) {
            $opts['exact_phrase'] = false;
        }
        if (!isset($opts['single_passage'])) {
            $opts['single_passage'] = false;
        }
        if (!isset($opts['use_boundaries'])) {
            $opts['use_boundaries'] = false;
        }
        if (!isset($opts['weight_order'])) {
            $opts['weight_order'] = false;
        }

        /////////////////
        // build request
        /////////////////

        // v.1.0 req
        $flags = 1; // remove spaces
        if ($opts['exact_phrase']) {
            $flags |= 2;
        }
        if ($opts['single_passage']) {
            $flags |= 4;
        }
        if ($opts['use_boundaries']) {
            $flags |= 8;
        }
        if ($opts['weight_order']) {
            $flags |= 16;
        }
        $req = pack('NN', 0, $flags); // mode=0, flags=$flags
        $req .= pack('N', strlen($index)).$index; // req index
        $req .= pack('N', strlen($words)).$words; // req words

        // options
        $req .= pack('N', strlen($opts['before_match'])).$opts['before_match'];
        $req .= pack('N', strlen($opts['after_match'])).$opts['after_match'];
        $req .= pack('N', strlen($opts['chunk_separator'])).$opts['chunk_separator'];
        $req .= pack('N', (int) $opts['limit']);
        $req .= pack('N', (int) $opts['around']);

        // documents
        $req .= pack('N', count($docs));
        foreach ($docs as $doc) {
            assert(is_string($doc));
            $req .= pack('N', strlen($doc)).$doc;
        }

        ////////////////////////////
        // send query, get response
        ////////////////////////////

        $len = strlen($req);
        $req = pack('nnN', SEARCHD_COMMAND_EXCERPT, VER_COMMAND_EXCERPT, $len).$req; // add header
        $wrote = fwrite($fp, $req, $len + 8);
        if (!($response = $this->_GetResponse($fp, VER_COMMAND_EXCERPT))) {
            $this->_MBPop();

            return false;
        }

        //////////////////
        // parse response
        //////////////////

        $pos = 0;
        $res = array();
        $rlen = strlen($response);
        for ($i = 0; $i < count($docs); ++$i) {
            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;

            if ($pos + $len > $rlen) {
                $this->_error = 'incomplete reply';
                $this->_MBPop();

                return false;
            }
            $res[] = $len ? substr($response, $pos, $len) : '';
            $pos += $len;
        }

        $this->_MBPop();

        return $res;
    }

    /////////////////////////////////////////////////////////////////////////////
    // keyword generation
    /////////////////////////////////////////////////////////////////////////////

    /// connect to searchd server, and generate keyword list for a given query
    /// returns false on failure,
    /// an array of words on success
    public function BuildKeywords($query, $index, $hits)
    {
        assert(is_string($query));
        assert(is_string($index));
        assert(is_bool($hits));

        $this->_MBPush();

        if (!($fp = $this->_Connect())) {
            $this->_MBPop();

            return false;
        }

        /////////////////
        // build request
        /////////////////

        // v.1.0 req
        $req = pack('N', strlen($query)).$query; // req query
        $req .= pack('N', strlen($index)).$index; // req index
        $req .= pack('N', (int) $hits);

        ////////////////////////////
        // send query, get response
        ////////////////////////////

        $len = strlen($req);
        $req = pack('nnN', SEARCHD_COMMAND_KEYWORDS, VER_COMMAND_KEYWORDS, $len).$req; // add header
        $wrote = fwrite($fp, $req, $len + 8);
        if (!($response = $this->_GetResponse($fp, VER_COMMAND_KEYWORDS))) {
            $this->_MBPop();

            return false;
        }

        //////////////////
        // parse response
        //////////////////

        $pos = 0;
        $res = array();
        $rlen = strlen($response);
        list(, $nwords) = unpack('N*', substr($response, $pos, 4));
        $pos += 4;
        for ($i = 0; $i < $nwords; ++$i) {
            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;
            $tokenized = $len ? substr($response, $pos, $len) : '';
            $pos += $len;

            list(, $len) = unpack('N*', substr($response, $pos, 4));
            $pos += 4;
            $normalized = $len ? substr($response, $pos, $len) : '';
            $pos += $len;

            $res[] = array('tokenized' => $tokenized, 'normalized' => $normalized);

            if ($hits) {
                list($ndocs, $nhits) = array_values(unpack('N*N*', substr($response, $pos, 8)));
                $pos += 8;
                $res [$i]['docs'] = $ndocs;
                $res [$i]['hits'] = $nhits;
            }

            if ($pos > $rlen) {
                $this->_error = 'incomplete reply';
                $this->_MBPop();

                return false;
            }
        }

        $this->_MBPop();

        return $res;
    }

    public function EscapeString($string)
    {
        $from = array('(',')','|','-','!','@','~','"','&', '/');
        $to = array('\(','\)','\|','\-','\!','\@','\~','\"', '\&', '\/');

        return str_replace($from, $to, $string);
    }

    /////////////////////////////////////////////////////////////////////////////
    // attribute updates
    /////////////////////////////////////////////////////////////////////////////

    /// update given attribute values on given documents in given indexes
    /// returns amount of updated documents (0 or more) on success, or -1 on failure
    public function UpdateAttributes($index, $attrs, $values)
    {
        // verify everything
        assert(is_string($index));

        assert(is_array($attrs));
        foreach ($attrs as $attr) {
            assert(is_string($attr));
        }

        assert(is_array($values));
        foreach ($values as $id => $entry) {
            assert(is_numeric($id));
            assert(is_array($entry));
            assert(count($entry) == count($attrs));
            foreach ($entry as $v) {
                assert(is_int($v));
            }
        }

        // build request
        $req = pack('N', strlen($index)).$index;

        $req .= pack('N', count($attrs));
        foreach ($attrs as $attr) {
            $req .= pack('N', strlen($attr)).$attr;
        }

        $req .= pack('N', count($values));
        foreach ($values as $id => $entry) {
            $req .= sphPack64($id);
            foreach ($entry as $v) {
                $req .= pack('N', $v);
            }
        }

        // mbstring workaround
        $this->_MBPush();

        // connect, send query, get response
        if (!($fp = $this->_Connect())) {
            $this->_MBPop();

            return -1;
        }

        $len = strlen($req);
        $req = pack('nnN', SEARCHD_COMMAND_UPDATE, VER_COMMAND_UPDATE, $len).$req; // add header
        fwrite($fp, $req, $len + 8);

        if (!($response = $this->_GetResponse($fp, VER_COMMAND_UPDATE))) {
            $this->_MBPop();

            return -1;
        }

        // parse response
        list(, $updated) = unpack('N*', substr($response, 0, 4));
        $this->_MBPop();

        return $updated;
    }
}

//
// $Id: sphinxapi.php 1365 2008-07-15 00:33:22Z shodan $
//
;
