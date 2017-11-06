<?php
/**
 * APD: Change 255 char limit on sliders cms_content field to 65,535 chars
 * @category   Auguria
 * @package    Auguria_Sliders
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */
$installer = $this;
$installer->startSetup();
$installer->run("
		
ALTER TABLE `{$this->getTable('auguria_sliders/sliders')}` MODIFY cms_content TEXT;

");
$installer->endSetup();