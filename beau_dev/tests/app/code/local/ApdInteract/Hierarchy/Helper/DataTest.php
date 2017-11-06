<?php

require_once(__DIR__ . '/../../../../../../../app/Mage.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Unit tests for ApdInteract_Hierarchy_Helper_Data to check that the correct icons load for a given product category.
 *
 * @author Seth Dialino 2/19/2016
 */
class ApdInteract_Hierarchy_Helper_DataTest extends PHPUnit_Framework_TestCase {

    protected $object;
    protected $tyreId;
    protected $batteryId;
    protected $commercial_data;
    protected $consumer_data;

    protected function setUp() {
        Mage::app();
        $this->tyreId = '41';
        $this->batteryId = '43';
        $this->commercial_data = $this->_loadCommercialData();
        $this->consumer_data = $this->_loadConsumerData();
        $this->object = Mage::helper('apdinteract_hierarchy');
    }

    protected function tearDown() {
        
    }

    private function _loadCommercialData(){
        // Expected order of attributes based from the Configuration in the backend 
        // System > Configuration > Commercial Hierarchy
        $commercial_data = array(
            'lighttruck' => array ('grip','even_ware','fuel_saver','superior_braking_grip'),
            'lighttrucktyre' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'truck' => array ('off_road','value','cycling','performance'),
            'tractorandagricultural' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'trucktyre' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'retreadlighttrucktyre' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'retreadtrucktyre' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'heavycommercial' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'industrial' => array ('durability','even_ware','fuel_saver','superior_braking_grip'));
        
        return $commercial_data;
    }
    
    private function _loadConsumerData(){
        $consumer_data = array(
            'passenger' => array ('grip','fuel_saver','handling','even_ware'),
            'sports' => array ('grip','slow_ware','quiet_comfort','performance'),
            'fourbyfour' => array ('fuel_saver','superior_braking_grip','handling','durability'),
            'lighttruck' => array ('durability','even_ware','fuel_saver','superior_braking_grip'),
            'car' => array ('durability','even_ware','fuel_saver','superior_braking_grip'));
        
        return $consumer_data;
    }
    
    /**
     * @test 
     * @covers ApdInteract_Hierarchy_Helper_Data::getHierarchy
     */
    public function CommercialSegmentAttributes(){
        if (isset($this->commercial_data)) {
            foreach ($this->commercial_data as $consumer_category => $expected_attributes)
            {
                $actual_attributes = $this->object->getHierarchy('commercial', $consumer_category);
                $sorted_attributes = array_slice($actual_attributes, 0, 4);
                $this->assertEquals($sorted_attributes, $expected_attributes);
            }
        }
    }
    
    /**
     * @test 
     * @covers ApdInteract_Hierarchy_Helper_Data::getHierarchy
     */
    public function ConsumerSegmentAttributes(){
        if (isset($this->consumer_data)) {
            foreach ($this->consumer_data as $consumer_category => $expected_attributes)
            {
                $actual_attributes = $this->object->getHierarchy('consumer', $consumer_category);
                $sorted_attributes = array_slice($actual_attributes, 0, 4);
                $this->assertEquals($sorted_attributes, $expected_attributes);
            }
        }
    }

    /**
     * @test 
     * @covers ApdInteract_Hierarchy_Helper_Data::loadAttributeData
     */
    public function TyreAttributes() {
        $tyre_attributes = $this->_getAllAttributes();
        $expected_attributes = $this->_getAttributesByCategoryId($this->tyreId);     
        
        foreach ($expected_attributes as $expected_attribute) 
        {
            $this->assertTrue(in_array($expected_attribute, $tyre_attributes));
        }    
    }
    
    /**
     * @test
     * @covers ApdInteract_Hierarchy_Helper_Data::loadAttributeData
     */
    public function BatteryAttributes() {
        $battery_attributes = $this->_getAllAttributes();
        $expected_attributes = $this->_getAttributesByCategoryId($this->batteryId);     
        
        foreach ($expected_attributes as $expected_attribute) 
        {
            $this->assertTrue(in_array($expected_attribute, $battery_attributes));
        }    
    }
    
    private function _getAllAttributes(){
        $attributes = $this->object->loadAttributeData();
        foreach($attributes as $attribute => $details){
            $attribute_key[] = $attribute;
        }
        return $attribute_key;
    }
    
    private function _getAttributesByCategoryId($categoryId){
        if ($categoryId == '41'){
            $attributes = array('sports_performance_handling','durability','mileage','even_ware','handling','superior_braking_grip',
                'quiet_comfort','fuel_saver','performance','cycling','grip','slow_ware','off_road','value');
        }else{
            $attributes = array('durability','mileage','performance','cycling');
        }        
        return $attributes;
    }

}
