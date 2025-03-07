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



$installer = $this;

$installer->startSetup();
$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('searchsphinx/synonym')}`;");
$installer->run("
CREATE TABLE `{$installer->getTable('searchsphinx/synonym')}` (
   `synonym_id` int(11)      unsigned NOT NULL auto_increment,
   `word`       varchar(255) NOT NULL default '',
   `synonyms`   text         NOT NULL default '',
   `store`      int(11)      unsigned NOT NULL,
    PRIMARY KEY (`synonym_id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;
");

$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('searchsphinx/stopword')}`;");
$installer->run("
CREATE TABLE `{$installer->getTable('searchsphinx/stopword')}` (
   `stopword_id` int(11)      unsigned NOT NULL auto_increment,
   `word`        varchar(255) NOT NULL default '',
   `store`       int(11)      unsigned NOT NULL,
    PRIMARY KEY (`stopword_id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;
");

$installer->endSetup();
