<?php
$installer = $this;
$installer->startSetup();
$sql="--
-- Table structure for table `apdinteract_campaign`
--

CREATE TABLE `apdinteract_campaign` (
  `entity_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `postcode` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apdinteract_campaign`
--
ALTER TABLE `apdinteract_campaign`
  ADD PRIMARY KEY (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apdinteract_campaign`
--
ALTER TABLE `apdinteract_campaign`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";

$installer->run($sql);
$installer->endSetup();
	 