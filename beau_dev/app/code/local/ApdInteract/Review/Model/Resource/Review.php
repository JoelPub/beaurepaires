<?php

class ApdInteract_Review_Model_Resource_Review extends Mage_Review_Model_Resource_Review{


    /**
     * Aggregate
     *
     * @param Mage_Core_Model_Abstract $object
     */
    public function aggregate($object)
    {

        $readAdapter    = $this->_getReadAdapter();
        $writeAdapter   = $this->_getWriteAdapter();
        if (!$object->getEntityPkValue() && $object->getId()) {
            $object->load($object->getReviewId());
        }

        $ratingModel    = Mage::getModel('rating/rating');
        $ratingSummaries= $ratingModel->getEntitySummary($object->getEntityPkValue(), false);

        foreach ($ratingSummaries as $ratingSummaryObject) {
            if ($ratingSummaryObject->getCount()) {
                $ratingSummary = round($ratingSummaryObject->getSum() / $ratingSummaryObject->getCount());
            } else {
                $ratingSummary = $ratingSummaryObject->getSum();
            }

            $reviewsCount = $this->getTotalReviews(
                $object->getEntityPkValue(),
                true,
                $ratingSummaryObject->getStoreId()
            );
            $select = $readAdapter->select()
                ->from($this->_aggregateTable)
                ->where('entity_pk_value = :pk_value')
                ->where('entity_type = :entity_type')
                ->where('store_id = :store_id');
            $bind = array(
                ':pk_value'    => $object->getEntityPkValue(),
                ':entity_type' => $object->getEntityId(),
                ':store_id'    =>$ratingSummaryObject->getStoreId()
            );
            $oldData = $readAdapter->fetchRow($select, $bind);

            $data = new Varien_Object();

            $data->setReviewsCount($reviewsCount)
                ->setEntityPkValue($object->getEntityPkValue())
                ->setEntityType($object->getEntityId())
                ->setRatingSummary(($ratingSummary > 0) ? $ratingSummary : 0)
                ->setStoreId($ratingSummaryObject->getStoreId())
                ->setTotalApproved($this->_collectApprovedReviews($ratingSummaryObject->getStoreId(),$object->getEntityPkValue()));

            $writeAdapter->beginTransaction();
            try {
                if ($oldData['primary_id'] > 0) {
                    $condition = array("{$this->_aggregateTable}.primary_id = ?" => $oldData['primary_id']);
                    $writeAdapter->update($this->_aggregateTable, $data->getData(), $condition);
                } else {
                    $writeAdapter->insert($this->_aggregateTable, $data->getData());
                }
                $writeAdapter->commit();
            } catch (Exception $e) {
                $writeAdapter->rollBack();
            }
        }
    }

    /**
     * @param $storeID
     * @param $productId
     * @return int
     */
    private function _collectApprovedReviews($storeID,$productId){

        $totalApproved = 0;

        $reviewCollection =  Mage::getModel('review/review')->getCollection()
            ->addStoreFilter($storeID)
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->addEntityFilter('product', $productId);

        if($reviewCollection->getSize() > 0){
            $totalApproved = $reviewCollection->getSize();
        }

        return $totalApproved;
    }

}