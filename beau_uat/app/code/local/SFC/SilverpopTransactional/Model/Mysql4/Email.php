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

class SFC_SilverpopTransactional_Model_Mysql4_Email extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the silverpoptransactional_id refers to the key field in your database table.
        $this->_init('silverpoptransactional/email', 'email_id');
    }
}