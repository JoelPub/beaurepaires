<?php

$installer = $this;
$installer->startSetup();


$content =
    <<<EOF
<p><span>Whatever your vehicle battery needs are, Beaurepaires can provide expert service and advice to help you find the best battery product for you. Beaurepaires stores stock a diverse range of car, 4WD, truck and motorcycle batteries from reputed battery provider, Exide Batteries. &nbsp;</span><span>Did you know that the majority of vehicle breakdowns are battery related? In order to avoid the chance of being stranded with battery problems, Beaurepaires can provide a free battery condition check on your current battery, as well as an expert battery fitting service in store.&nbsp;</span></p>
<p><a class="button radius" title="EXIDE BATTERY SELECTOR" href="http://batteryfitment.com/search.php" target="_blank"> EXIDE BATTERY SELECTOR</a>  Find batteries for your vehicle (new window)</p>
EOF;

$cmsUpdateBlock = Mage::getModel('cms/block')->load('battery_list1');
$cmsUpdateBlock->setContent($content)->save();

$installer->endSetup();
