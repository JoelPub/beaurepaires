<?php
/**
 * Script use for update Username in Both Magento and DBC from CSV file
 *
 * Before run this script please update DBC database credentials
 *
 */

//DBC Database Connection
$servername = "";
$username = "";
$password = "";
$dbname = ""; //DBC Database

// Create connection
$dbcConn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($dbcConn->connect_error) {
    die("Connection failed: " . $dbcConn->connect_error);
}

require_once(dirname(__FILE__)."/../../app/Mage.php");

ini_set('display_errors', 1);

Mage::app();

$csvFile = "import/updateAdminUsername.csv";

$importer = new CsvImporter($csvFile);

$i = 0;
$header = $rowArray = array();

echo "Start".date('h:i:s A')."\n";

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');

while($data = $importer->get(2000)){

    foreach($data as $row){
        if($i==0){
            $header = explode(",",$row[0]);
            $i++;
            continue;
        }

        $data = explode(",",$row[0]);
        $rowArray = array_combine($header, $data);

        print_r($rowArray);

        try{
            $selectQuery = "UPDATE admin_user SET username = '".trim($rowArray['username'])."' where user_id = ".$rowArray['magentoid']." AND username = '".trim($rowArray['email'])."'";
            $result = $connection->query($selectQuery);

            $selectQuery = "UPDATE ea_user_settings SET username = '".trim($rowArray['username'])."' where username = '".trim($rowArray['email'])."'";
            $result = $dbcConn->query($selectQuery);
        }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            Mage::log($e->getMessage(), null, 'updateUsername.log');
            continue;
        }
    }
}

echo "End".date('h:i:s A')."\n";


class CsvImporter
{
    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;
    //--------------------------------------------------------------------
    function __construct($file_name, $parse_header=false, $delimiter="\t", $length=8000)
    {
        $this->fp = fopen($file_name, "r");
        $this->parse_header = $parse_header;
        $this->delimiter = $delimiter;
        $this->length = $length;
        $this->lines = $lines;

        if ($this->parse_header)
        {
            $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }

    }
    //--------------------------------------------------------------------
    function __destruct()
    {
        if ($this->fp)
        {
            fclose($this->fp);
        }
    }
    //--------------------------------------------------------------------
    function get($max_lines=0)
    {
        //if $max_lines is set to 0, then get all the data

        $data = array();

        if ($max_lines > 0)
            $line_count = 0;
        else
            $line_count = -1; // so loop limit is ignored

        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE)
        {

            if ($this->parse_header)
            {
                foreach ($this->header as $i => $heading_i)
                {
                    $row_new[$heading_i] = $row[$i];
                }
                $data[] = $row_new;
            }
            else
            {
                $data[] = $row;
            }

            if ($max_lines > 0)
                $line_count++;
        }
        return $data;
    }
    //--------------------------------------------------------------------

}
?>