<?php

class ApdInteract_Index_Model_Observer  extends Enterprise_Index_Model_Observer{
    
    public function reindexSearch() {
        $model = Mage::getModel('searchindex/index');
        $indexes = $model->getCollection();

         foreach ($indexes as $index) {
            $indexId = $index->getId();
            $indexModel = $model->load($indexId);
            //Mage::register('current_model', $indexModel, true);
            try {
                $indexModel->validate();
                $indexModel->reindexAll();
                Mage::log($indexId, null, 'indexes2.log');
            } catch (Exception $e) {
                Mage::log('Search Indexing not successful: '.$e);
            }
        }
    }
    
     public function refreshIndex(Mage_Cron_Model_Schedule $schedule)
    {                 
        /** @var $helper Enterprise_Index_Helper_Data */
        $helper = Mage::helper('enterprise_index');

        /** @var $lock Mage_Index_Model_Lock */
        $lock   = Mage_Index_Model_Lock::getInstance();

        if ($lock->setLock(self::REINDEX_FULL_LOCK, true)) {

            /**
             * Workaround for fatals and memory crashes: Invalidating indexers that are in progress
             * Successful lock setting is considered that no other full reindex processes are running
             */
            $this->_invalidateInProgressIndexers();

            $client = Mage::getModel('enterprise_mview/client');
            try {

                //full re-index
                $inactiveIndexes = $this->_getInactiveIndexersByPriority();
                $rebuiltIndexes = array();
                foreach ($inactiveIndexes as $inactiveIndexer) {
                    $tableName  = (string)$inactiveIndexer->index_table;
                    $actionName = (string)$inactiveIndexer->action_model->all;
                    $client->init($tableName);
                    if ($actionName) {
                        $client->execute($actionName);
                        $rebuiltIndexes[] = $tableName;
                    }
                }

                //re-index by changelog
                $indexers = $helper->getIndexers(true);
                foreach ($indexers as $indexerName => $indexerData) {
                    $indexTable = (string)$indexerData->index_table;
                    $actionName = (string)$indexerData->action_model->changelog;
                    $client->init($indexTable);
                    if (isset($actionName) && !in_array($indexTable, $rebuiltIndexes)) {
                        $client->execute($actionName);
                    }
                }

            } catch (Exception $e) {
                $lock->releaseLock(self::REINDEX_FULL_LOCK, true);
                throw $e;
            }

            $lock->releaseLock(self::REINDEX_FULL_LOCK, true);
        } else {
            throw new Enterprise_Index_Exception("Can't lock indexer process.");
        }
        
        $this->reindexSearch();

        return $this;
    }
}

