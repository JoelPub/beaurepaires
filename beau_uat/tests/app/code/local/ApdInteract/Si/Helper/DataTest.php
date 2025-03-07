<?php
require_once(__DIR__.'/../../../../../../../app/Mage.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-16 at 15:10:34.
 */
class ApdInteract_Si_Helper_DataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ApdInteract_Si_Helper_Data
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called once before everything.
     */
    public function __construct()
    {
        Mage::app();
        
        $this->helper->si = Mage::helper('si');
        $this->helper->requestprice = Mage::helper('apdinteract_requestprice');        
        parent::__construct();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
        $this->helper->requestprice->deleteTestOrders();
        
    }
    
    private function _getOrdersCount() 
    {
        $orders = $this->helper->si->getOrdersCollection();
        return $orders->count();
    }

    /**
     * @covers ApdInteract_Si_Helper_Data::getStreamInteractiveOrderXml
     */
    public function testGetStreamInteractiveOrderXml()
    {
        $order = $this->helper->requestprice->createTestOrder();          
        
        $params['auth'] = "91850210b93d06aa7a5018f0c5c403f84f42ecf5233d0c418e478b82c8fabc2a";
        $params['date'] = date('Ymd'); // something like yyyymmdd
        $today = date('Y-m-d');
        
        $xml = $this->helper->si->getStreamInteractiveOrderXml($params);        
        // fwrite(STDOUT, print_r($xml, true) . "\n"); // Dumps result to the output window. Useful for debugging
        

        // Sample XML
        //<row responseQueryId="1">
//<quote_id>1000021</quote_id>
//<web_site_id>5</web_site_id>
//<quote_type>T</quote_type>
//<date_submission>2016-02-17 11:07:47</date_submission>
//<name>Auto-generated-TEST-Firstname-3213 TEST-Customer-please-Ignore-Lastname</name>
//<email>test@test.com</email>
//<phone>0412123123</phone>
//<postcode>0000</postcode>
//<state>VIC</state>
//<town>Not Specified</town>
//<make></make>
//<model></model>
//<year></year>
//<registration></registration>
//<tyre_size></tyre_size>
//<additional_info>  12:00 am - 6 x Assurance Triplemax (529694) A A 5 5,</additional_info>
//<othserv_rotation>N</othserv_rotation>
//<othserv_warranty>N</othserv_warranty>
//<othserv_alignment>N</othserv_alignment>
//<servicing_details></servicing_details>
//<last_service></last_service>
//<opt_out_product>N</opt_out_product>
//<vehicle_version></vehicle_version>
//<email_address_to_sent></email_address_to_sent>
//<num_tyres></num_tyres>
//</row>

        
        
        $findme_array = array (
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<!DOCTYPE QuoteRequestResponse>',
            '<QuoteRequestResponse rows="' . $this->_getOrdersCount() . '" fields="26">',
            '<row responseQueryId="1">',
            '<quote_id>',
            '<web_site_id>5</web_site_id>',
            '<quote_type>T</quote_type>',
            '<date_submission>' . $today,            
            '<name>Auto-generated-TEST-Firstname-3213 TEST-Customer-please-Ignore-Lastname</name>',
            '<email>test@test.com</email>',
            '<phone>0412123123</phone>',
            '<postcode>3121</postcode>',
            '<state>VIC</state>',
            '<town>RICHMOND</town>'            
        );
        
        $additionalInfo = '';
        
        if ($order instanceof Mage_Sales_Model_Order)
        {        
            $items = $order->getAllVisibleItems();

            foreach ($items as $item)
            {                 
                $additionalInfo .= $this->helper->si->buildAdditionalInfoFieldFromItemData($item);                        
            }
        }
        
        $cleanAdditionalInfo = $this->helper->si->xmlEscape(
            'PRICE REQUEST  12:00 am - ' . 
            $additionalInfo
            );
        
        // $findme_array[] = '<additional_info>  12:00 am - 6 x Assurance Triplemax (529694) A A 5 5,</additional_info>';
        $findme_array[] =  '<additional_info>' . $cleanAdditionalInfo . '</additional_info>';
        
        foreach ($findme_array as $findme)
        {
            $log_msg = "Check XML for {$findme}";
            fwrite(STDOUT, print_r($log_msg, true) . "\n"); // Dumps result to the output window. Useful for debugging
            
            $this->assertContains($findme, $xml, "{$findme} not found in {$xml}");
        }
    }

    /**
     * @covers ApdInteract_Si_Helper_Data::xmlEscape
     */
    public function testXmlEscape()
    {
        // str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
        
        $dirty_string = '"Bugsy" O\'Toole\'s age was > 36 & < 92 years old';
        $expected_result = '&quot;Bugsy&quot; O&apos;Toole&apos;s age was &gt; 36 &amp; &lt; 92 years old';
        $actual_result = $this->helper->si->xmlEscape($dirty_string);
        
        $this->assertEquals($actual_result, $expected_result);
            
    }
    
    /**
     * @covers ApdInteract_Si_Helper_Data::DateFromGmtFormat
     */
    public function testDateFromGmtFormat()
    {
        $params['date'] = '20120131';        
        $expected_from_timestring[0] = '2012-01-30 13:00:00'; // Timezone difference could be 10 or 11, depending on DST
        $expected_from_timestring[1] = '2012-01-30 14:00:00'; // Timezone difference could be 10 or 11, depending on DST
        
        $actual_from_timestring = $this->helper->si->setParams($params)->DateFromGmtFormat(); 
        $result = in_array($actual_from_timestring, $expected_from_timestring);        
        $this->assertTrue($result, "FAIL: {$actual_from_timestring} did not match any of these: " . print_r($expected_from_timestring, true));
    }

    /**
     * @covers ApdInteract_Si_Helper_Data::DateToGmtFormat
     */
    public function testDateToGmtFormat()
    {
        $params['date'] = '20120131';        
        $expected_from_timestring[0] = '2012-01-31 12:59:59'; // Timezone difference could be 10 or 11, depending on DST
        $expected_from_timestring[1] = '2012-01-31 13:59:59'; // Timezone difference could be 10 or 11, depending on DST
        
        $actual_from_timestring = $this->helper->si->setParams($params)->DateToGmtFormat(); 
        $result = in_array($actual_from_timestring, $expected_from_timestring);        
        $this->assertTrue($result, "FAIL: {$actual_from_timestring} did not match any of these: " . print_r($expected_from_timestring, true));
    }

    /**
     * @covers ApdInteract_Si_Helper_Data::getDateParam
     */
    public function testGetDateParam()
    {
        $params['date'] = '20120131';
        $expected = '2012-1-31';
        $actual = $this->helper->si->setParams($params)->getDateParam(); 
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ApdInteract_Si_Helper_Data::convertToAuTime
     * @todo   Implement testConvertToAuTime().
     */
    public function testConvertToAuTime()
    {
        $timestring = '2012-01-31 23:59:59';
        $expected[] = '2012-02-01 09:59:59'; // GMT to AEST = +10 or
        $expected[] = '2012-02-01 10:59:59'; // +11, depending on if DST is active
        $actual = $this->helper->si->convertToAuTime($timestring);
        
        $result = in_array($actual, $expected);        
        $this->assertTrue($result, "FAIL: {$actual} did not match any of these: " . print_r($expected, true));
    }
}
