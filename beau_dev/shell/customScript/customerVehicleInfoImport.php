<?php

require_once(dirname(__FILE__)."/../../app/Mage.php");

ini_set('display_errors', 1);

Mage::app();

$csvFile = "import/customerVehicleImport.csv";

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
$importer = new CsvImporter($csvFile);

$i = 0;
$header = $rowArray = array();

echo "Start".date('h:i:s A')."\n";
$customerEmail = "";
while($data = $importer->get(10)){

    foreach($data as $row){
        if($i==0){
            $header = explode(",",$row[0]);
            $i++;
            continue;
        }

        $data = explode(",",$row[0]);
        $rowArray = array_combine($header, $data);

        if(isset($rowArray['email']) && $rowArray['email']!=''){
            $customerEmail = $rowArray['email'];
            unset($rowArray['email']);
        } elseif($customerEmail ==""){
            continue;
        }else{
            unset($rowArray['email']);
        }

        try{
            $rows = $connection->fetchRow("select entity_id from customer_entity where email = '".$customerEmail."'");
            if(count($rows)>0){
                $rowArray['customer_id'] = $rows['entity_id'];
                $rowArray['website_id'] = 1;
                $connection->insert("customer_vehicle", $rowArray);
                echo "Insert in Customer Id: ".$rowArray['customer_id']."\n";
            }
        }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            Mage::log($e->getMessage(), null, 'customerVehicleMassImport.log');
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