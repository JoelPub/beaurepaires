<?php

require_once(dirname(__FILE__)."/../../app/Mage.php");

ini_set('display_errors', 1);

Mage::app();

//$csvFile = "../../Export_Magento_Customer_Hdr_20170427.csv";
$csvFile = "import/customerInfoFinal.csv";

$importer = new CsvImporter($csvFile);

$i = 0;
$header = $rowArray = array();

echo "Start".date('h:i:s A')."\n";

while($data = $importer->get(2000)){

    foreach($data as $row){
        if($i==0){
            $header = explode(",",$row[0]);
            $i++;
            continue;
        }

        $data = explode(",",$row[0]);
        $rowArray = array_combine($header, $data);
        $rowArray["dormant_flag"] = 1;

        try{
            $customer = Mage::getModel("customer/customer");
            $customer->setWebsiteId(1)->loadByEmail( $rowArray['email'] );
            $exists = $customer->getId();
            if($exists){
                //echo "Record Found : ".$exists." ".$rowArray['email']."\n";
                continue;
            }
            $customer->setData($rowArray);
            $customer->save();
            echo "New Record : ".$rowArray['email']."\n";
        }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            Mage::log($e->getMessage(), null, 'customerInfoMassInport.log');
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