<?php

require_once(dirname(__FILE__)."/../../app/Mage.php");

ini_set('display_errors', 1);

Mage::app();

$csvFile = "import/customerAddressImort.csv";

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
$importer = new CsvImporter($csvFile);

$i = 0;
$header = $rowArray = array();

echo "Start".date('h:i:s A')."\n";
$customerEmail = "";

$regionIdArray = array('ACT'=>485,'NSW'=>486,'NT'=>487,'QLD'=>488,'SA'=>489,'TAS'=>490,'VIC'=>491,'WA'=>492);

while($data = $importer->get(5000)){

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

                $rowArray['customer_id'] = (int)$rows['entity_id'];
                $rowArray['parent_id'] = (int)$rows['entity_id'];
                if (array_key_exists($rowArray['region'], $regionIdArray)) {
                    $rowArray['region_id'] = $regionIdArray[$rowArray['region']];
                    unset($rowArray['region']);
                }

                $address = Mage::getModel("customer/address");
                $address->setData($rowArray)
                    ->setIsDefaultBilling('1')
                    ->setIsDefaultShipping('1')
                    ->setSaveInAddressBook('1');

                //var_dump($address->getData());

                $address->save();
                echo "Insert in Customer Id: ".$rowArray['customer_id']."\n";
            }

        }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            Mage::log($e->getMessage(), null, 'customerAddressMassImport.log');
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
