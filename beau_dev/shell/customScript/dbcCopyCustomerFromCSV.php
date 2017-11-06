<?php

//DBC Database Connection
$servername = "";
$username = "";
$password = "";
$dbname = ""; //dbc database

// Create connection
$dbcConn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($dbcConn->connect_error) {
    die("Connection failed: " . $dbcConn->connect_error);
}



ini_set('display_errors', 1);



$csvFile = "import/dbcCustomerImport.csv";


$insertQueryDBCBase = "INSERT INTO ea_users (first_name,last_name,email,mobile_number,phone_number,address,street,city,state,zip_code,id_roles,id_stores,magento_customer_id,company_name)";

$importer = new CsvImporter($csvFile);

$i = 0;
$header = $rowArray = array();

$customerEmail = "";
while($data = $importer->get(5000)){

    foreach($data as $singlerow){
        if($i==0){
            $header = explode(",",$singlerow[0]);
            $i++;
            continue;
        }

        $data = explode(",",$singlerow[0]);
        $row = array_combine($header, $data);

        $row['id_roles'] = 3;
        $row['id_stores'] = 0;

        if(empty($row['first_name']) || is_null($row['first_name'])){
                $row['first_name'] = 'n/a';
            }

            if(empty($row['last_name']) || is_null($row['last_name'])){
                $row['last_name'] = 'n/a';
            }

            $row['street'] = '';

        try{

            //Ignore If Email ID already found in DBC
                $selectQueryDBC = "Select id from ea_users where email = '".$row['email']."'";
                $result = $dbcConn->query($selectQueryDBC);
                if($result->num_rows == 0) {
                    $insertQueryDBC = $insertQueryDBCBase." values('".$row['first_name']."','".$row['last_name']."','".$row['email']."','".$row['mobile_number']."','".$row['phone_number']."','".$row['address']."','".$row['street']."','".$row['city']."','".$row['state']."','".$row['zip_code']."',".$row['id_roles'].",".$row['id_stores'].",".$row['magento_customer_id'].",'".$row['company_name']."')";

                $dbcConn->query($insertQueryDBC);

                }
                        
        }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            Mage::log($e->getMessage(), null, 'customerVehicleMassImport.log');
            continue;
        }
    }


}


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
        //$this->lines = $lines;

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
