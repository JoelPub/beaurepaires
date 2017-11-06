<?php
$installer = $this;
$installer->startSetup();
$sql="--
-- Table structure for table `apdinteract_campaign`
--

CREATE TABLE `apdinteract_campaign_setup` (
  `entity_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `cms_page` varchar(255) NOT NULL,
  `thank_you` varchar(255) NOT NULL,
  `edm` varchar(255) NOT NULL,    
  `active` int(1) NOT NULL DEFAULT 1,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apdinteract_campaign_setup`
--
ALTER TABLE `apdinteract_campaign_setup`
  ADD PRIMARY KEY (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apdinteract_campaign_setup`
--
ALTER TABLE `apdinteract_campaign_setup`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";

$installer->run($sql);
$installer->endSetup();