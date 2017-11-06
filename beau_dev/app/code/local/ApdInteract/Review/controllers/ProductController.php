<?php

require_once Mage::getModuleDir('controllers', 'Mage_Review') . DS . 'ProductController.php';

class ApdInteract_Review_ProductController extends Mage_Review_ProductController {

    /**
     * Submit new review action
     *
     */
    public function postAction() {
        if (!$this->_validateFormKey()) {
            // returns to the product item page
            $this->_redirectReferer();
            return;
        }

        if ($data = Mage::getSingleton('review/session')->getFormData(true)) {
            $rating = array();
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPost();
            $rating = $this->getRequest()->getParam('ratings', array());
        }

        if (($product = $this->_initProduct()) && !empty($data)) {
            $session = Mage::getSingleton('core/session');
            /* @var $session Mage_Core_Model_Session */
            $review = Mage::getModel('review/review')->setData($this->_cropReviewData($data));
            /* @var $review Mage_Review_Model_Review */

            $validate = $review->validate();
            if ($validate === true) {
                try {
                    $review->setEntityId($review->getEntityIdByCode(Mage_Review_Model_Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($product->getId())
                            ->setStatusId(Mage_Review_Model_Review::STATUS_PENDING)
                            ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                            ->setStoreId(Mage::app()->getStore()->getId())
                            ->setStores(array(Mage::app()->getStore()->getId()))
                            ->save();

                    foreach ($rating as $ratingId => $optionId) {
                        Mage::getModel('rating/rating')
                                ->setRatingId($ratingId)
                                ->setReviewId($review->getId())
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->addOptionVote($optionId, $product->getId());
                    }

                    $review->aggregate();
                    $this->_notifyAdmin($product, $data, $rating);
                    $session->addSuccess($this->__('Your review has been accepted for moderation.'));
                } catch (Exception $e) {
                    $session->setFormData($data);
                    $session->addError($this->__('Unable to post the review.'));
                }
            } else {
                $session->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $session->addError($errorMessage);
                    }
                } else {
                    $session->addError($this->__('Unable to post the review.'));
                }
            }
        }

        if ($redirectUrl = Mage::getSingleton('review/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }


        $this->_redirectReferer();
    }

    private function _getFromMail($identity) {

        switch ($identity) {
            case 'general':
                $from_email = Mage::getStoreConfig('trans_email/ident_general/email');
                $from_name = Mage::getStoreConfig('trans_email/ident_general/name');
                break;
            case 'sales':
                $from_email = Mage::getStoreConfig('trans_email/ident_sales/email');
                $from_name = Mage::getStoreConfig('trans_email/ident_sales/name');
                break;
            case 'support':
                $from_email = Mage::getStoreConfig('trans_email/ident_support/email');
                $from_name = Mage::getStoreConfig('trans_email/ident_support/name');
                break;
            case 'custom1':
                $from_email = Mage::getStoreConfig('trans_email/ident_custom1/email');
                $from_name = Mage::getStoreConfig('trans_email/ident_custom1/name');
                break;
            case 'custom2':
                $from_email = Mage::getStoreConfig('trans_email/ident_custom2/email');
                $from_name = Mage::getStoreConfig('trans_email/ident_custom2/name');
                break;
        }
        $from = array('name' => $from_name, 'email' => $from_email);

        return $from;
    }

    private function _notifyAdmin($product, $data, $rating) {
        // Transactional Email Template's ID
        $templateId = Mage::getStoreConfig('apdinteract_review_section/apdinteract_review_group/apdinteract_review_email_template');
        $identitySenderArray = $this->_getFromMail(Mage::getStoreConfig('apdinteract_review_section/apdinteract_review_group/apdinteract_review_identity'));
        $identityRecipientArray = $this->_getFromMail(Mage::getStoreConfig('apdinteract_review_section/apdinteract_review_group/apdinteract_review_recipient'));

        // Set sender information			

        $sender = array('name' => $identitySenderArray['name'],
            'email' => $identitySenderArray['email']);

        // Set recipient information
        $recipientEmail = $identityRecipientArray['email'];
        $recipientName = $identityRecipientArray['name'];

        // Get Store ID		
        $store = Mage::app()->getStore()->getId();
        $img = "<img width=200 src=" . Mage::helper('catalog/image')->init($product, 'thumbnail') . ">";
        // Set variables that can be used in email template
        $vars = array('product_name' => $product->getName(),
            'product_url' => $product->getProductUrl(),
            'image' => $img,
            'customer' => $data['nickname'],
            'title' => $data['title'],
            'msg' => $data['detail'],
            'rating' => $rating[1],
            'recipient' => $recipientName
        );


        $translate = Mage::getSingleton('core/translate');
        $session = Mage::getSingleton('core/session');

        Mage::getModel('core/email_template')
                ->sendTransactional($templateId, $sender, $recipientEmail, $recipientName, $vars);
        $translate->setTranslateInline(true);
    }

    /**
     * Submit Vote review
     * Return JSON data
     */
    public function voteAction() {

        $param = $this->getRequest()->getParams();
        $reviewId = $param['review_id'];
        $vote = $param['vote'];
        $voteDetails = array();
        $return = array();

        try {

            $currentReview = $this->_getReview($reviewId)[0];

            $upVote = $vote == 1 ? $currentReview['up_vote'] + 1 : $currentReview['up_vote'];
            $downVote = $vote == 0 ? $currentReview['down_vote'] + 1 : $currentReview['down_vote'];

            $voteReview = $this->_ModelReview()->load($reviewId);
            $voteReview->setData('up_vote', $upVote);
            $voteReview->setData('down_vote', $downVote);
            $voteReview->save();

            if ($voteReview) {
                $voteDetails = $this->_helperReview()->getVoteReview($voteReview);
            }

            $return = array('error' => 0, 'message' => '', 'voteDetails' => $voteDetails['details']);
        } catch (Exception $e) {
            $return = array('error' => 1, 'message' => $e->getMessage(), 'voteDetails' => array());
        }

        echo json_encode($return);
    }

    /**
     * Custom Fetch Data from Review Table
     * @param $reviewId
     * @return mixed
     */
    protected function _getReview($reviewId) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT down_vote,up_vote FROM review WHERE review_id = '{$reviewId}'";
        return $readConnection->fetchAll($query);
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _ModelReview() {
        return Mage::getModel('review/review');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _helperReview() {
        return Mage::helper('apdinteract_review');
    }

}
