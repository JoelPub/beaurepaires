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



class Mirasvit_SearchIndex_Model_Index_External_Joomla_Zoo_Item extends Varien_Object
{
    public function getContent()
    {
        $elements = json_decode($this->getElements(), true);
        $content = array();

        foreach ($elements as $element) {
            foreach ($element as $value) {
                if (isset($value['value'])) {
                    $content[] = $value['value'];
                }
            }
        }
        $content = implode(' ', $content);

        return $content;
    }

    public function getUrl()
    {
        $url = $this->getIndex()->getProperty('url_template');

        foreach ($this->getData() as $key => $value) {
            $key = strtolower($key);
            $url = str_replace('{'.$key.'}', $value, $url);
        }

        return $url;
    }

    public function getIndex()
    {
        return Mage::helper('searchindex/index')->getIndexModel('external_joomla_zoo');
    }
}
