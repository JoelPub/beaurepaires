<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Sphinx Search Ultimate
 * @version   2.3.4
 * @build     1372
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_SearchIndex_Model_Index_Blackbird_Contentmanager_Content_Index extends Mirasvit_SearchIndex_Model_Index
{
    public function getBaseGroup()
    {
        return 'Blackbird';
    }

    public function getBaseTitle()
    {
        return 'Content';
    }

    public function canUse()
    {
        return Mage::getConfig()->getModuleConfig('Blackbird_ContentManager')->is('active', 'true');
    }

    public function getPrimaryKey()
    {
        return 'entity_id';
    }

    public function getAvailableAttributes()
    {
        $searchableCtIds = $this->getSearchableCtIds();

        $fields = Mage::getModel('contentmanager/contenttype_option')
            ->getCollection()
            ->addFieldToSelect('identifier')
            ->addTitleToResult(0)
            ->addFieldToFilter('ct_id', array('in' => $searchableCtIds));

        $result['title'] = Mage::helper('contentmanager')->__('Title');
        foreach($fields as $field)
        {
            $result[$field->getIdentifier()] = $field->getTitle();
        }

        return $result;
    }

    public function getCollection()
    {
        $searchableCtIds = $this->getSearchableCtIds();

        $collection = Mage::getModel('contentmanager/content')->getCollection();
        $collection->addAttributeToFilter('status', 1);
        $collection->addAttributeToFilter('ct_id', array('in' => $searchableCtIds));
        $collection->addAttributeToSelect('*');

        $this->joinMatched($collection, 'e.entity_id');
        return $collection;
    }

    public function getSearchableCtIds()
    {
        $collection = Mage::getModel('contentmanager/contenttype')
            ->getCollection()
            ->addFieldToFilter('search_enabled', 1);

        return $collection->getAllIds();
    }
}