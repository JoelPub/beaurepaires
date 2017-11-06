<?php
class ApdInteract_Bookings_Adminhtml_BookingsbackendController extends Mage_Adminhtml_Controller_Action
{

    protected $adminUserId;
    protected $bookingStoreIds;
    protected $defaultError = array('error' => 1, 'message' => 'Invalid API configuration','data' => array());
    protected $bookingStatus = array(
        1 => 'Not Started',
        2 => 'In Progress',
        3 => 'Cancelled',
        4 => 'On Hold',
        5 => 'Ready For Customer Pickup',
        6 => 'Completed'
    );


    /**
     * Initialize booking details
     */
    protected function _initBooking(){

        $admin =  Mage::getSingleton('admin/session')->getUser();
        $storeData = Mage::helper('bookings')->getStoreIds($admin->getId());

        $this->adminUserId =  $admin->getId();
        $this->bookingStoreIds = implode(',',$storeData);

        Mage::getSingleton('core/session')->setBookingStoreIds($this->bookingStoreIds);
    }

    public function indexAction()
    {

        $this->_initBooking();

        $this->loadLayout();
        $this->_title($this->__("Store Manager Console"));
        $this->renderLayout();



    }


    /**
     * Retrieve Booking Details
     * Return JSON Format
     */
    public function getBookingAction(){

        $bookingStoreId = Mage::getSingleton('core/session')->getBookingStoreIds();
        $arrayData = array();
        $apiUrl = Mage::getStoreConfig('booking/api/get_booking');
        $paramStartDate = Mage::app()->getRequest()->getParam('startDate',Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        $paramEndDate = Mage::app()->getRequest()->getParam('endDate',Mage::getModel('core/date')->date('Y-m-d H:i:s'));

        //set time 24 hours
        $startDate =   date('Y-m-d 00:00:00',strtotime($paramStartDate));
        $endDate   =   date('Y-m-d 23:59:59',strtotime($paramEndDate));

        $postData['data'] = array(
            'bookingIds' => $bookingStoreId,
            'startDate'  => $startDate,
            'endDate'    => $endDate
        );

        if($apiUrl != '') {
            $response = Mage::helper('apdinteract_order')->connectCurl(Mage::getStoreConfig('booking/api/get_booking'), json_encode($postData));
            $returnData = json_decode($response, true);
            if ((bool)count($returnData['data'])) {
                $arrayData['error'] = 0;
                $arrayData['message'] = null;
                foreach ($returnData['data'] as $item) {
                    if(!is_null($item['customer_info'])) {
                        $custInfo = json_decode($item['customer_info']);
                        $item['customer_firstname'] = $custInfo->first_name;
                        $item['customer_lastname'] = $custInfo->last_name;
                    }

                    $arrayData['data'][] = array(
                        "id" => $item['id'],
                        "state" => array_search($item['magento_vir_status'], $this->bookingStatus),
                        "name" => $item['customer_firstname'] . ' ' . $item['customer_lastname'],
                        "serviceType" => $item['service_type'],
                        "bayNumber" => $item['bay_firstname'] . ' ' . $item['bay_lastname'],
                        "startTime" => $item['start_datetime'],
                        "finishTime" => $item['end_datetime'],
                    );
                }
            } else {
                if($returnData == null){
                    $arrayData = $this->defaultError;
                }else{
                    $arrayData = $returnData;
                }
            }
        }else{
            $arrayData = $this->defaultError;
        }
        $arrayData['parameter'] = $postData;
        echo Mage::helper('core')->jsonEncode($arrayData);
    }

    /**
     *  Update Booking state both Magento VIR and Beau Calendar
     *  Return JSON Format
     */
    public function updateBookingAction(){

        $arrayData = array();
        $bookingId = Mage::app()->getRequest()->getParam('id',false);
        $bookingState = Mage::app()->getRequest()->getParam('state',false);

        if($bookingId != false && $bookingState != false){

            $statusLabel = $this->bookingStatus[$bookingState];
            try {

                $response =   Mage::Helper('sync')->update_booking_status($bookingId, $statusLabel);  //update vir status at booking calendar

                if((bool)$response->getBody()){
                    // update booking Status on Magento VIR
                    Mage::helper('apdinteract_vir')->updateVirStatus($bookingId, $statusLabel);
                }

                $arrayData['success'] = true;
                $arrayData['message'] = null;

            } catch (Exception $e) {

                $arrayData['success'] = false;
                $arrayData['message'] =  $e->getMessage();
            }

        }else{
            $arrayData['success'] = false;
            $arrayData['message'] =  "Parameter is empty";
        }

        echo Mage::helper('core')->jsonEncode($arrayData);
    }


    private function checksecurity() {
        $urallowed = Mage::getStoreConfig('sync/sync/apdinteract_order_customerapi', Mage::app()->getStore());
        $refer = $_SERVER["HTTP_REFERER"];
        if (strpos($refer, $urallowed) !== false) {
            return;
        } else {
            return 'false';
        }


    }

    protected function _isAllowed()
    {
        $isAllowed = Mage::getSingleton('admin/session')->isAllowed('apdinteract_vir/bookingsbackend');
        return $isAllowed;
    }

}