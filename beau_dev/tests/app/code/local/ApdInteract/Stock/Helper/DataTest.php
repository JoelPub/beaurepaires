<?php
require_once(__DIR__.'/../../../../../../../app/Mage.php');


/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-08 at 16:38:48.
 */
class ApdInteract_Stock_Helper_DataTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ApdInteract_Stock_Helper_Data
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        Mage::app();
        $this->object = Mage::helper('apdinteract_stock');
        $this->object->updateCostarCode();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    private function _dbr()
    {
        return Mage::getSingleton('core/resource')->getConnection('core/read');
    }
    
    /**
     * @covers ApdInteract_Stock_Helper_Data::updateCostarCode
     */
    public function testAllStoresHaveACostarCode()
    {
        $sql = 'SELECT count(*) FROM iwd_storelocator WHERE costar_store_code = ""';
        $result = $this->_dbr()->fetchOne($sql);
        fwrite(STDOUT, print_r($result, true) . "\n"); // Dumps result to the output window. Useful for debugging       
        
        $this->assertTrue(empty($result));
    }
    
    /**
     * @covers ApdInteract_Stock_Helper_Data::updateCostarCode
     */
    public function testStoresHaveNoDuplicatedCostarCodes()
    {
        $sql = 'SELECT costar_store_code, count(costar_store_code) FROM iwd_storelocator GROUP BY costar_store_code HAVING count(costar_store_code) > 1';
        $result = $this->_dbr()->fetchRow($sql);
        fwrite(STDOUT, print_r($result, true) . "\n"); // Dumps result to the output window. Useful for debugging       
        
        $this->assertTrue(empty($result));
    }
}
