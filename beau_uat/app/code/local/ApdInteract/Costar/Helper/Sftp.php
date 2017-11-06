<?php

class ApdInteract_Costar_Helper_Sftp extends Varien_Io_Sftp
{

    private $_pdoDb;
    private $_filename;

    public function connectToCostarSFTP()
    {
        $myhost = 'sftp.microhouse.com.au';
        $myuser = 'Beaurepaires';
        $mypass = '45heRfU92W';

        $this->open(
            array(
                'host' => $myhost,
                'username' => $myuser,
                'password' => $mypass
            )
        );
        return $this;
    }

    public function createDirIfNotExisting($dir)
    {
        try {
            $this->mkdir($dir, $mode = 0777, $recursive = false);
        } catch (Exception $e) {
            $this->_logException($e);
        }
    }

    private function _moveToProcessedDir($filename)
    {
        $processed_dir = 'Processed';
        $this->createDirIfNotExisting($processed_dir);
        $src = $filename;
        $dest = './' . $processed_dir . '/' . $filename;
        return $this->mv($src, $dest);
    }

    public function cdToPickupDir()
    {
        $pickup_dir = 'Pickup';
        $this->cd($pickup_dir);
        return $this;
    }

    public function getFileList()
    {
        return array_slice(array_keys($this->rawls()), 2);
    }

    public function getOldestMatchingFilename($pattern = 'StockOnHandCorporate_Complete')
    {
        return $this->_getMatchingFilename($pattern, 'oldest');
    }

    public function getNewestMatchingFilename($pattern = 'StockOnHandCorporate_Complete')
    {
        return $this->_getMatchingFilename($pattern, 'newest');
    }

    private function _getMatchingFilename($pattern = 'StockOnHandCorporate_Complete', $sort_dir = 'oldest')
    {
        // get name of the most recent file starting with pattern
        $files = $this->getFileList();

        if ($sort_dir == 'oldest') {
            sort($files, SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            rsort($files, SORT_NATURAL | SORT_FLAG_CASE);
        }

        foreach ($files as $filename) {
            if (strpos($filename, $pattern) !== false) {
                return $filename;
            }
        }
    }

    private function _loadFile($filename, $dest = null)
    {
        return $this->read($filename, $dest);
    }

    private function _getNextFileInQueue($pattern, $copy_to_destination = null)
    {

        try {            
            $filecontents = false;
            
            $this->connectToCostarSFTP()
                ->cdToPickupDir();

            $this->_filename = $this->getOldestMatchingFilename($pattern);
            if ($this->_filename) {
                Mage::log("Processing: START: {$this->_filename}", null, 'costar_import_file_processing.log');
                $filecontents = $this->_loadFile($this->_filename, $copy_to_destination);
                // if $copy_to_destination is blank, it returns the file contents as a string.
                // Otherwise, returns true if successful
             
            }
            return $filecontents;
            
        } catch (exception $e) {
            $this->_logException($e);
            // Keep going

            return $filecontents;
        }
    }

    public function processStockFileQueue()
    {
        $this->_processFileQueue('StockOnHandCorporate_');
    }

    private function _processFileQueue($pattern)
    {
        Mage::log("Begin processing: {$pattern}* from costar sFTP (oldest first)", null, 'costar_import_file_processing.log');
        
        $temp_file = Mage::getBaseDir('tmp') . '/' . $pattern . '.tmp';
        while ($this->_getNextFileInQueue($pattern, $temp_file)) {            
            $this->_loadLocalStockFileIntoDatabase($temp_file);
            $this->_moveToProcessedDir($this->_filename);
            $this->close();
            unlink($temp_file);
            Mage::log("Processing: END: {$this->_filename}", null, 'costar_import_file_processing.log');
        }
    }

    private function _loadLocalStockFileIntoDatabase($path_to_stockfile)
    {
        try {
            // IN // U508	564163	1.0	2.0	3.0	Active
            // OUT array('U508', '564163', 3, 1);
            
            $sql = "LOAD DATA LOCAL INFILE '{$path_to_stockfile}'
                    REPLACE INTO TABLE apd_stock_store_product
                    FIELDS TERMINATED BY '\\t'
                    LINES TERMINATED BY '\\r\\n'
                    (costar_store_code, sku, @col3, @col4, @floatqty, @active)
                    SET 
                    is_active = IF (@active='Active', 1, 0),
                    qty = FLOOR(@floatqty)                 
                    ;"; // IGNORE 1 LINES // if header

            // Debugging line
            // Mage::log("SQL: {$sql}", null, 'costar_import_file_processing.log');
            // To test/debug this, try mysql --local-infile -A [database] from command line
            
            $result = $this->_pdoDb()->exec($sql);
            
            if (!$result) {
                $error = print_r($this->_pdoDb()->errorInfo(), true);
                Mage::log("MySQL error: {$error}", null, 'costar_import_file_processing.log');
            }
            
        } catch (exception $e) {
            $this->_logException($e);
            // And keep going
        }
    }

// // NOT NEEDED YET, (but might use something similar later)    
//    public function updateStockFileDatabase($stockfile)
//    {
//        $file_lines = preg_split('/\n|\r/', $stockfile, -1, PREG_SPLIT_NO_EMPTY);
//        foreach ($file_lines as $csv_string) {
//            $this->_insertIntoStockDatabase($this->_stockCSVLineToArray($csv_string));
//        }
//    }

    private function _logException($e) {
        Mage::logException($e);
        Mage::log($e->getMessage(), null, 'costar_import_file_processing.log');
    }
    
    private function _dbread()
    {
        return $this->_dbconnect('core_read');        
    }
    
    private function _dbwrite()
    {
        return $this->_dbconnect('core_write');
    }
    
    private function _dbconnect($connection) {
        return Mage::getSingleton('core/resource')->getConnection($connection);
    }

    private function _pdoDb($options = array(PDO::MYSQL_ATTR_LOCAL_INFILE => true))
    {
        // Usage: // $this->_pdoDb()->exec($sql);
        // Use this for LOAD DATA LOCAL INFILE queries
        // (Standard Magento DB connection doesn't work)

        try {
            if (!isset($this->_pdoDb)) {

                $local = simplexml_load_file(Mage::getBaseDir() . "/app/etc/local.xml");
                $connection = $local->global->resources->default_setup->connection;
                $this->_pdoDb = new PDO("mysql:host={$connection->host};dbname={$connection->dbname}", $connection->username, $connection->password, $options
                );
            }
            return $this->_pdoDb;
        } catch (PDOException $e) {
            $this->_logException($e);
        }
    }
    
    public function getStockQty($store_code, $sku) {
        $sql = 'SELECT * FROM apd_stock_store_product
                WHERE sku = ?
                AND costar_store_code = ?                 
                AND is_active = 1
                LIMIT 1';
        $binds = array($sku, $store_code);
        $result = $this->_dbread()->fetchRow($sql, $binds);
        return $result;
    }

// // NOT NEEDED YET, (but might use something similar later)    
//    private function _cleanArray($array)
//    {
//        foreach ($array as $value) {
//            $clean_array[] = trim($value);
//        }
//        return $clean_array;
//    }

// // NOT NEEDED YET, (but might use something similar later)    
//    private function _stockCSVLineToArray($csv_string)
//    {
//        // IN // U508	564163	1.0	2.0	3.0	Active
//        // OUT array('U508', '564163', 3, 1);
//        $temp_data = explode("\t", $csv_string);
//        $data = array_slice($temp_data, 0, 2);
//
//        $data[2] = $temp_data[4]; // $temp_data[x] => 2,3 or 4, depending on which qty field we want to use.
//
//        $data[3] = 0; // Inactive
//        if (strtolower($temp_data[5]) == 'active') {
//            $data[3] = 1;
//        }
//
//        $data = $this->_cleanArray($data);
//
//        return $data;
//    }

// // NOT NEEDED YET, (but might use something similar later)    
//    private function _insertIntoStockDatabase($binds)
//    {
//
//        // costar_store_code | sku | IGNORE | IGNORE | qty | is_active (=1 if "Active", otherwise 0)
//        // U508	564163	1.0	2.0	3.0	Active
//        //
//        // $binds = array('U508', '564163', 5, 1);
//
//        try {
//
//            $sql = "INSERT INTO apd_stock_store_product "
//                . "(costar_store_code, sku, qty, is_active) "
//                . "VALUES (?,?,?,?) "
//                . "ON DUPLICATE KEY UPDATE qty=VALUES(qty), is_active=VALUES(is_active)";
//
//            $this->_dbwrite()->query($sql, $binds);
//        } catch (exception $e) {
//            $this->_logException($e);
//        }
//    }
}
