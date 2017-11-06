<?php

class ApdInteract_Review_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_reviewsCollection;

    public function getProduct() {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }

    
    private function _getStores() {
        
        $stores = array();
        $allStores = Mage::app()->getStores();
        foreach ($allStores as $_eachStoreId => $val) 
        {       
            
            $stores[] = Mage::app()->getStore($_eachStoreId)->getId();
        
        }
        
        return $stores;
    }

    private function _getReviewQuery($productId) {
        
        $stores = $this->_getStores();

        $query = Mage::getModel('review/review')->getCollection()
                ->distinct(true)
                ->addStoreFilter($stores)
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->addEntityFilter('product', $productId);
        return $query;
    }

   
    public function getReviewsCollection() {
        if (null === $this->_reviewsCollection) {
            $this->_reviewsCollection = Mage::getModel('review/review')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                    ->addEntityFilter('product', $this->getProduct()->getId())
                    ->setPageSize(5) // retrieved the last 5 latest reviews
                    ->setCurPage(1)
                    ->setDateOrder();
        }
       
        return $this->_reviewsCollection;
    }

    public function getReviewCount($productId = false) {
        if(!$productId)
        $productId = $this->getProduct()->getId();
        
        $query = $this->_getReviewQuery($productId);
        return $query->count();
    }
    
    public function getStarRating($reviewId) {        
       
        $resource = Mage::getSingleton('core/resource');
        $adapter = $resource->getConnection('core_read');
       
        $table = $resource->getTableName('rating/rating_option_vote');
        $where = $adapter->quoteInto("review_id = ?", $reviewId);
        $select = $adapter->select('percent')->from($table)->where($where);                    
        return $adapter->fetchAll($select);
        
	}


    /**
     * Return Review Vote Details
     * @param $review
     * @return mixed
     */
    public function getVoteReview($review) {

        $voteDetails = array();
        (int) $upVote = $review->getUpVote();
        (int) $downVote = $review->getDownVote();

        $totalVote = $upVote + $downVote;
        if ($upVote == 1 && $totalVote == 1) {
            $voteDetails['details'] = "{$upVote} person found this helpful.";
        } elseif ($upVote >= 1 && $totalVote != 1) {
            $voteDetails['details'] = "{$upVote} of {$totalVote} people found this helpful.";
        }

        $voteDetails['total_up_vote'] = $upVote;
        $voteDetails['total_down_vote'] = $downVote;

        return $voteDetails;
    }

    public function sendToSf($id,$rating,$action=null) {
        
        $post = $this->_getReviewFromId($id);
        $data = $post->getData();        
        $product = $this->_getProduct($data['entity_pk_value']);        
        $request = Mage::helper('apdinteract_requestprice')->getLoginUserDetails();        
        $request["detail"] = $data['detail'];
        $request["review_id"] = $data['review_id'];
        $request["nickname"] = $data['nickname'];
        $request["product_name"] = $product->getName();
        $request["score"] = $rating[1];
        $request["product_sku"] = $product->getSku();
        $request["status"] = $this->_getStatus($post['status_id']);
        $request["website"] = 'Beaurepaires';
        $request["title"] = $data['title'];
        $request["product"] = $data['product'];

       $sao = Mage::getModel("apdinteract_salesforce/process_business_review", $request);
        try {
            if($action=='update')                
                $result = $sao->update()->getResult();
            else                
                $result = $sao->add()->getResult();   
                        
            if (!isset($result->id)):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'review_error.log');            
            else:
                Mage::getModel('apdinteract_salesforce/dictionary')->saveDictionary($post,$result->id);
                Mage::log($result->id, null, 'review_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    private function _getReviewFromId($id,$rating) {
        $review = Mage::getModel('review/review')->load($id);               
        return $review;
        
    }
    
    private function _getProduct($id) {
        
        $product = Mage::getModel('catalog/product')
        ->load($id);
        
         return $product;
        
    }
    
    private function _getStatus($id) {
        $status = array(1=>"Approved",2=>"Pending",3=>"Not Approved");
        return $status[$id];
    }
    
    public function deleteToSf($review_id){
        $sao = Mage::getModel("apdinteract_salesforce/process_business_review", $review_id);
        try {            
                $result = $sao->delete()->getResult();               
            
            if (!isset($result->id)):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'review_error.log');          
            else:                
                Mage::log($result->id, null, 'review_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function displayTotalReview($approvedReview){

        $html = "";
        if($approvedReview == 1){
            $html = "{$approvedReview} Review(s)";
        }elseif($approvedReview > 1){
            $html = "{$approvedReview} Review(s)";
        }

        return $html;
    }

}
