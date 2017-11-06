<?php
/**
 * StoreFront Silverpop Transaction Email Magento Extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to commercial source code license 
 * of StoreFront Consulting, Inc.
 *
 * @category	SFC
 * @package    	SFC_SilverpopTransactional
 * @website 	http://www.storefrontconsulting.com/
 * @copyright 	Copyright (C) 2009-2013 StoreFront Consulting, Inc. All Rights Reserved.
 */

class SFC_SilverpopTransactional_Model_Status extends Varien_Object
{
    const STATUS_ERROR		= -1;
    const STATUS_SENT		= 1;
    const STATUS_UNSENT		= 0;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ERROR	=> Mage::helper('silverpoptransactional')->__('Error'),
            self::STATUS_SENT   => Mage::helper('silverpoptransactional')->__('Sent'),
            self::STATUS_UNSENT => Mage::helper('silverpoptransactional')->__('Unsent')
        );
    }
}