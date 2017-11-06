<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Customer Vehicle Action column - Update or Delete Vehicle
 *
 * @category   Mage
 * @package    Apdinteract_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Adminhtml_Block_Customer_Grid_Renderer_Multiaction
    extends Mage_Adminhtml_Block_Customer_Grid_Renderer_Multiaction
{
    /**
     * Renders column
     *
     * @param  Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $html = '';
        $actions = $this->getColumn()->getActions();
        if (!empty($actions) && is_array($actions)) {
            $links = array();
            foreach ($actions as $action) {
                if (is_array($action)) {
                    $link = $this->_toLinkHtml($action, $row);
                    if ($link) {
                        $links[] = $link;
                    }
                }
            }
            $html = implode('<br />', $links);
        }

        if ($html == '') {
            $html = '&nbsp;';
        }

        return $html;
    }

    /**
     * Render single action as link html
     * Generates the URL under the Action Column (grid)
     * @param  array $action
     * @param  Varien_Object $row
     * @return string
     */
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        if ($action['process'] == 'delete_vehicle'){
            $style = '';
            $title = 'Delete Vehicle';
            $id = $row->getId();
            return sprintf('<a href="%s" %s onclick="%s" title="%s">%s</a>', $action['url'] . $id , $style, $action['onclick'], $title,$action['caption']);
        }

        if ($action['process'] == 'update_vehicle'){
            $style = '';
            $title = 'Update Vehicle';
            $id = $row->getId();
            return sprintf('<a href="%s" %s title="%s">%s</a>', $action['url'] . $id , $style, $title, $action['caption']);
        }

    }
}
