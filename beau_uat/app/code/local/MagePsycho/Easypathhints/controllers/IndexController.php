<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Easypathhints
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Easypathhints_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		return;
			
    }
	public function saveAction()
	{
    	$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));		
    	$post = Mage::app()->getFrontController()->getRequest()->getPost();
        // get the address rather than the id, and save that value
        $store_id = $post['storeloc'];
        $storeLocation = $store_id;
        if($storeId != "") {
            // Load the sote object
            $model = Mage::getModel('storelocator/stores');
            $store_id = $model->load($store_id);
            // get the following data - Beaurepaires West Melbourne - 350 Spencer St WEST MELBOURNE VIC , 3000
            $storeLocation = $store->getTitle()." - ".$store->getStreet()." ".$store->getCity()." ".$store->getRegion()." ".$store->getPostalCode();
        }
        
        
            Mage::getSingleton("core/session")->setStorelocation($storeLocation);
            Mage::getSingleton("core/session")->setDeliveryDate($post['ddate']);		
	}
}