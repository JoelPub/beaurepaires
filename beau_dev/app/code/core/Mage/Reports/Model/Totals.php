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
 * @package     Mage_Reports
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 *  Totals Class
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Reports_Model_Totals
{
    /**
     * Retrieve count totals
     *
     * @param Mage_Adminhtml_Block_Report_Grid $grid
     * @param string $from
     * @param string $to
     * @return Varien_Object
     */
    public function countTotals($grid, $from, $to)
    {
        $columns = array();
        foreach ($grid->getColumns() as $col) {
            $columns[$col->getIndex()] = array("total" => $col->getTotal(), "value" => 0);
        }

        $count = 0;
        $report = $grid->getCollection()->getReportFull($from, $to);
        foreach ($report as $item) {
            if ($grid->getSubReportSize() && $count >= $grid->getSubReportSize()) {
                continue;
            }
            $data = $item->getData();

            foreach ($columns as $field=>$a) {
                if ($field !== '') {
                    $columns[$field]['value'] = $columns[$field]['value'] + (isset($data[$field]) ? $data[$field] : 0);
                }
            }
            $count++;
        }
        $data = array();
        foreach ($columns as $field => $a) {
            if ($a['total'] == 'avg') {
                if ($field !== '') {
                    if ($count != 0) {
                        $data[$field] = $a['value']/$count;
                    } else {
                        $data[$field] = 0;
                    }
                }
            } else if ($a['total'] == 'sum') {
                if ($field !== '') {
                    $data[$field] = $a['value'];
                }
            } else if (strpos($a['total'], '/') !== FALSE) {
                if ($field !== '') {
                    $data[$field] = 0;
                }
            }
        }

        $totals = new Varien_Object();
        $totals->setData($data);

        return $totals;
    }
}
