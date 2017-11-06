<?php

/**
 * Created a new profile 'New Canonical Urls' in Dataflow - Advanced Profiles
 * 3/15/2016 BCC - 156
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$data = array (
		'name' => 'New Canonical Urls',
		'created_at' => Mage::getSingleton('core/date')->gmtDate(),
		'store_id' => 0,
		'actions_xml' => <<<EOF
<action type="dataflow/convert_adapter_io" method="load">
    <var name="type">file</var>
    <var name="path">var/import</var>
    <var name="filename"><![CDATA[new_canonical_url.csv]]></var>
    <var name="format"><![CDATA[csv]]></var>
</action>
<action type="dataflow/convert_parser_csv" method="parse">
    <var name="delimiter"><![CDATA[,]]></var>
    <var name="enclose"><![CDATA["]]></var>
    <var name="fieldnames">true</var>
    <var name="number_of_records">1</var>
    <var name="decimal_separator"><![CDATA[.]]></var>
    <var name="adapter">apdinteract_rel/convert_adapter_url</var>
    <var name="method">saveRow</var>
</action>
EOF
);

$profile = Mage::getModel('dataflow/profile');
$profile->setData($data)->save();
$installer->endSetup();
