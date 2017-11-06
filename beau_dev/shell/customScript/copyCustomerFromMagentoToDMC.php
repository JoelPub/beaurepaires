<?php

//DBC Database Connection
$servername = "";
$username = "";
$password = "";
$dbname = "apdgystage_booking"; //DBC Database

// Create connection
$dbcConn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($dbcConn->connect_error) {
    die("Connection failed: " . $dbcConn->connect_error);
}

require_once(dirname(__FILE__)."/../../app/Mage.php");

ini_set('display_errors', 1);

Mage::app();

//$csvFile = "import/customerAddressImort.csv";

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');

$customerCountQuery = "select count(*) as totalRows from customer_entity";
$rowsCount = $connection->fetchrow($customerCountQuery);

$i=0;

if(count($rowsCount)== 0){
    die("No Record Found");
}

$totalRows = $rowsCount['totalRows'];
$limitFrom = 0;
$limitTo = 500;

 $insertQueryDBCBase = "INSERT INTO ea_users (first_name,last_name,email,mobile_number,phone_number,address,street,city,state,zip_code,id_roles,id_stores,magento_customer_id,company_name)";


while ($limitFrom <= $totalRows) {

    $customerQuery = "SELECT ce.entity_id AS magento_customer_id, ce.email as email, cev2.value AS first_name, cev3.value AS last_name, caev.value as address, caev1.value AS city, caev2.value AS state, caev3.value AS zip_code, caev4.value AS mobile_number,caev5.value AS phone_number, caev6.value AS company_name
    FROM customer_entity ce
    LEFT JOIN customer_entity_varchar cev2 ON (ce.entity_id = cev2.entity_id AND cev2.attribute_id = 5)
    LEFT JOIN customer_entity_varchar cev3 ON (ce.entity_id = cev3.entity_id AND cev3.attribute_id = 7)
    LEFT JOIN customer_address_entity cae ON (ce.entity_id = cae.parent_id)
    LEFT JOIN customer_address_entity_text caev ON (cae.entity_id = caev.entity_id AND caev.attribute_id = 25)
    LEFT JOIN customer_address_entity_varchar caev1 ON (cae.entity_id = caev1.entity_id AND caev1.attribute_id = 26)
    LEFT JOIN customer_address_entity_varchar caev2 ON (cae.entity_id = caev2.entity_id AND caev2.attribute_id = 28)
    LEFT JOIN customer_address_entity_varchar caev3 ON (cae.entity_id = caev3.entity_id AND caev3.attribute_id = 30)
    LEFT JOIN customer_address_entity_varchar caev4 ON (cae.entity_id = caev4.entity_id AND caev4.attribute_id = 388)
    LEFT JOIN customer_address_entity_varchar caev5 ON (cae.entity_id = caev5.entity_id AND caev5.attribute_id = 31)
    LEFT JOIN customer_address_entity_varchar caev6 ON (cae.entity_id = caev6.entity_id AND caev6.attribute_id = 24)
    order by ce.entity_id desc LIMIT $limitFrom,$limitTo";

    $rows = $connection->fetchAll($customerQuery);
    if (count($rows) > 0) {

        foreach($rows as $row){

//print_r($row);
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
                Mage::log($e->getMessage(), null, 'copyCustomerToDBC.log');
                //continue;
            }

        }
   }


    $limitFrom = $limitTo;
    $limitTo = $limitTo + 500;
}

