<?php
/** @var $installer Mage_Core_Model_Resource_Setup
 *
 *  Ticket : BCC-528
 *  Create a static block, will be used in email templates as a promo block
 */
$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();
$identifier = 'email_template_promo_block';
$title = 'Promo Block for Email Template';
$contentCmsBlock = <<<EOT
<p></p>
EOT;
$cmsBlock = Mage::getModel('cms/block')->load($identifier);
if ($cmsBlock->isObjectNew()) {
    $cmsBlock->setTitle($title)
        ->setContent($contentCmsBlock)
        ->setIdentifier($identifier)
        ->setStores(0)
        ->setIsActive(true)
        ->save();
}


$variablePermission = Mage::getModel('admin/variable')->load('silverpop/smtp/enabled', 'variable_name');
$variablePermission->setData('variable_name', 'silverpop/smtp/enabled');
$variablePermission->setData('is_allowed', 1);
$variablePermission->save();

$installer->endSetup();