<?php

class IWD_StoreLocator_Block_Adminhtml_List_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('storelocator/container.phtml');
    }

    public function getRegionsUrl() {
        return $this->getUrl('adminhtml/json/countryRegion');
    }

    public function getValueArray() {
        $adminUserModel = Mage::getModel('admin/user')->getCollection();
        $data_array = array();

        foreach ($adminUserModel as $a) {
            $data_array[] = array('value' => $a->getId(), 'label' => $a->getFirstname() . " " . $a->getLastname() . " - " . $a->getEmail());
        }
        return($data_array);
    }

    private function _getAdmin() {
        $isAllowed = Mage::helper('storelocator')->isAllowed();
        return ($isAllowed) ? "" : ";display:none;";
    }

    protected function _prepareForm() {

        Mage::getModel('storelocator/image')->clearCache();

        /* @var $model IWD_StoreLocator_Model_Stores */
        $model = Mage::registry('storelocator_store');


        $isElementDisabled = false;

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('storelocator')->__('Store Information')));

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name' => 'entity_id',
            ));
        }


        try {
            $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
            $config->setData(
                    Mage::helper('storelocator')->recursiveReplace(
                            '/slocator/', '/' . (string) Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/', $config->getData()
                    )
            );
        } catch (Exception $ex) {
            $config = null;
        }

        $addoptions = array();
        $stores_new = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
        $option = array(
            'label' => Mage::helper('adminhtml')->__('All Store Views'),
            'value' => 0
        );

        if (!in_array($option, $stores_new)) {
            $addoptions[] = $option;
            $stores_new = array_merge($addoptions, $stores_new);
        }


        $fieldset->addField('store_id', 'multiselect', array(
            'name' => 'stores[]',
            'label' => Mage::helper('cms')->__('Store View'),
            'title' => Mage::helper('cms')->__('Store View'),
            'required' => true,
            'values' => $stores_new,
        ));

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => Mage::helper('storelocator')->__('Title'),
            'title' => Mage::helper('storelocator')->__('Title'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('type_id', 'select', array(
            'name' => 'type_id',
            'label' => Mage::helper('storelocator')->__('Type'),
            'title' => Mage::helper('storelocator')->__('Type'),
            'required' => true,
            'values' => Mage::getModel('storecategory/options')->toOptionArray(),
            'disabled' => $isElementDisabled
        ));


        $fieldset->addField('admin_users', 'multiselect', array(
            'label' => Mage::helper('storelocator')->__('Users Allowed'),
            'class' => 'required-entry',
            'name' => 'admin_users',
            'style' => 'width:600px' . $this->_getAdmin(),
            'values' => $this->getValueArray(),
            'required' => false
        ));



        $fieldset->addField('url', 'text', array(
            'name' => 'url',
            'label' => Mage::helper('storelocator')->__('Url Key'),
            'title' => Mage::helper('storelocator')->__('Url Key'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('storelocator')->__('Status'),
            'title' => Mage::helper('storelocator')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => $model->getAvailableStatuses(),
            'disabled' => $isElementDisabled,
        ));

        $fieldset->addField('position', 'text', array(
            'name' => 'position',
            'label' => Mage::helper('storelocator')->__('Position'),
            'title' => Mage::helper('storelocator')->__('Position'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));
        

        $fieldset->addField('static_block_identifier', 'text', array(
            'name' => 'static_block_identifier',
            'label' => Mage::helper('storelocator')->__('Marketing Message Static Block'),
            'title' => Mage::helper('storelocator')->__('Marketing Message Static Block'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $fieldset = $form->addFieldset('address_fieldset', array('legend' => Mage::helper('storelocator')->__('Address')));


        $fieldset->addField('country_id', 'select', array(
            'name' => 'country_id',
            'label' => Mage::helper('storelocator')->__('Country'),
            'title' => Mage::helper('storelocator')->__('Country'),
            'required' => true,
            'values' => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
            'disabled' => $isElementDisabled
        ));
        if (!$model->getId()) {
            $model->setCountryId('AU');
        }
        $fieldset->addField('region', 'select', array(
            'name' => 'region',
            'label' => Mage::helper('storelocator')->__('State'),
            'title' => Mage::helper('storelocator')->__('State'),
            'required' => true,
            'disabled' => $isElementDisabled,
        ));


        $fieldset->addField('region_id', 'hidden', array(
            'name' => 'region_id',
            'label' => Mage::helper('storelocator')->__('State ID'),
            'title' => Mage::helper('storelocator')->__('State ID '),
            'required' => true,
            'disabled' => $isElementDisabled,
        ));


        $regionElement = $form->getElement('region');
        $regionElement->setRequired(true);
        if ($regionElement) {
            $regionElement->setRenderer(Mage::getModel('adminhtml/customer_renderer_region'));
        }

        $regionElement = $form->getElement('region_id');
        if ($regionElement) {
            $regionElement->setNoDisplay(true);
        }

        $country = $form->getElement('country_id');
        if ($country) {
            $country->addClass('countries');
        }


        $fieldset->addField('parent_region_id', 'select', array(
            'name' => 'parent_region_id',
            'label' => Mage::helper('storelocator')->__('Area'),
            'title' => Mage::helper('storelocator')->__('Area'),
            'required' => true,
            'disabled' => $isElementDisabled,
            'values' => Mage::helper('storelocator')->getRegionValues(),
        ));

        $fieldset->addField('city', 'text', array(
            'name' => 'city',
            'label' => Mage::helper('storelocator')->__('City'),
            'title' => Mage::helper('storelocator')->__('City'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));


        $fieldset->addField('postal_code', 'text', array(
            'name' => 'postal_code',
            'label' => Mage::helper('storelocator')->__('Zip/Postal Code'),
            'title' => Mage::helper('storelocator')->__('Zip/Postal Code'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('street', 'text', array(
            'name' => 'street',
            'label' => Mage::helper('storelocator')->__('Street'),
            'title' => Mage::helper('storelocator')->__('Street'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('website', 'text', array(
            'name' => 'website',
            'label' => Mage::helper('storelocator')->__('Website'),
            'title' => Mage::helper('storelocator')->__('Website'),
            'disabled' => $isElementDisabled,
        ));

        $fieldset->addField('desc', 'editor', array(
            'name' => 'desc',
            'label' => Mage::helper('storelocator')->__('Description'),
            'title' => Mage::helper('storelocator')->__('Description'),
            'disabled' => $isElementDisabled,
            'style' => 'height:15em',
            'config' => $config,
            'wysiwyg' => true
        ));

        $fieldset->addField('page_title', 'text', array(
            'name' => 'page_title',
            'label' => Mage::helper('storelocator')->__('Page Title'),
            'title' => Mage::helper('storelocator')->__('Page Title')
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('storelocator')->__('Meta Description'),
            'title' => Mage::helper('storelocator')->__('Meta Description')
        ));

        $fieldset->addField('phone', 'text', array(
            'name' => 'phone',
            'label' => Mage::helper('storelocator')->__('Phone Number'),
            'title' => Mage::helper('storelocator')->__('Phone Number'),
            'required' => false,
            'checked' => $model->getData('') == 1 ? true : false,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('email', 'text', array(
            'name' => 'email',
            'label' => Mage::helper('storelocator')->__('Email address'),
            'title' => Mage::helper('storelocator')->__('Email address'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));

        $data = $model->getData();

        $fieldset = $form->addFieldset('image_fieldset', array('legend' => Mage::helper('storelocator')->__('Image & Icon')));
        $fieldset = $form->addFieldset('extra_fieldset', array('legend' => Mage::helper('storelocator')->__('Extra Data')));

        $fieldset->addField('channel', 'text', array('name' => 'channel', 'label' => Mage::helper('storelocator')->__('Channel'), 'title' => Mage::helper('storelocator')->__('Channel'), 'required' => false));
        $fieldset->addField('mon_open', 'text', array('name' => 'mon_open', 'label' => Mage::helper('storelocator')->__('Monday Open'), 'title' => Mage::helper('storelocator')->__('Monday Open'), 'required' => false));
        $fieldset->addField('mon_close', 'text', array('name' => 'mon_close', 'label' => Mage::helper('storelocator')->__('Monday Close'), 'title' => Mage::helper('storelocator')->__('Monday Close'), 'required' => false));
        $fieldset->addField('tue_open', 'text', array('name' => 'tue_open', 'label' => Mage::helper('storelocator')->__('Tuesday Open'), 'title' => Mage::helper('storelocator')->__('Tuesday Open'), 'required' => false));
        $fieldset->addField('tue_close', 'text', array('name' => 'tue_close', 'label' => Mage::helper('storelocator')->__('Tuesday Close'), 'title' => Mage::helper('storelocator')->__('Tuesday Close'), 'required' => false));
        $fieldset->addField('wed_open', 'text', array('name' => 'wed_open', 'label' => Mage::helper('storelocator')->__('Wednesday Open'), 'title' => Mage::helper('storelocator')->__('Wednesday Open'), 'required' => false));
        $fieldset->addField('wed_close', 'text', array('name' => 'wed_close', 'label' => Mage::helper('storelocator')->__('Wednesday Close'), 'title' => Mage::helper('storelocator')->__('Wednesday Close'), 'required' => false));
        $fieldset->addField('thu_open', 'text', array('name' => 'thu_open', 'label' => Mage::helper('storelocator')->__('Thursday Open'), 'title' => Mage::helper('storelocator')->__('Thursday Open'), 'required' => false));
        $fieldset->addField('thu_close', 'text', array('name' => 'thu_close', 'label' => Mage::helper('storelocator')->__('Thursday Close'), 'title' => Mage::helper('storelocator')->__('Thursday Close'), 'required' => false));
        $fieldset->addField('fri_open', 'text', array('name' => 'fri_open', 'label' => Mage::helper('storelocator')->__('Friday Open'), 'title' => Mage::helper('storelocator')->__('Friday Open'), 'required' => false));
        $fieldset->addField('fri_close', 'text', array('name' => 'fri_close', 'label' => Mage::helper('storelocator')->__('Friday Close'), 'title' => Mage::helper('storelocator')->__('Friday Close'), 'required' => false));
        $fieldset->addField('sat_open', 'text', array('name' => 'sat_open', 'label' => Mage::helper('storelocator')->__('Saturday Open'), 'title' => Mage::helper('storelocator')->__('Saturday Open'), 'required' => false));
        $fieldset->addField('sat_close', 'text', array('name' => 'sat_close', 'label' => Mage::helper('storelocator')->__('Saturday Close'), 'title' => Mage::helper('storelocator')->__('Saturday Close'), 'required' => false));        
        $fieldset->addField('sunday_open', 'text', array('name' => 'sunday_open', 'label' => Mage::helper('storelocator')->__('Sunday Open'), 'title' => Mage::helper('storelocator')->__('Sunday Open'), 'required' => false));
        $fieldset->addField('sunday_close', 'text', array('name' => 'sunday_close', 'label' => Mage::helper('storelocator')->__('Sunday Close'), 'title' => Mage::helper('storelocator')->__('Sunday Close'), 'required' => false));
        $fieldset->addField('public_holiday_open', 'text', array('name' => 'public_holiday_open', 'label' => Mage::helper('storelocator')->__('Public Holiday Open'), 'title' => Mage::helper('storelocator')->__('Public Holiday Open'), 'required' => false));
        $fieldset->addField('public_holiday_close', 'text', array('name' => 'public_holiday_close', 'label' => Mage::helper('storelocator')->__('Public Holiday Close'), 'title' => Mage::helper('storelocator')->__('Public Holiday Close'), 'required' => false));
        $fieldset->addField('man_firstname', 'text', array('name' => 'man_firstname', 'label' => Mage::helper('storelocator')->__('Manager Firstname'), 'title' => Mage::helper('storelocator')->__('Manager Firstname'), 'required' => false));
        $fieldset->addField('man_lastname', 'text', array('name' => 'man_lastname', 'label' => Mage::helper('storelocator')->__('Manager Lastname'), 'title' => Mage::helper('storelocator')->__('Manager Lastname'), 'required' => false));
        $fieldset->addField('man_background', 'text', array('name' => 'man_background', 'label' => Mage::helper('storelocator')->__('Manager Background'), 'title' => Mage::helper('storelocator')->__('Manager Background'), 'required' => false));
        $fieldset->addField('comm_tyres', 'checkbox', array('name' => 'comm_tyres', 'checked' => $model->getData('comm_tyres') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Commercial Tyres'), 'title' => Mage::helper('storelocator')->__('Commercial Tyres'), 'required' => false));
        $fieldset->addField('cons_tyres', 'checkbox', array('name' => 'cons_tyres', 'checked' => $model->getData('cons_tyres') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Consumer Tyres'), 'title' => Mage::helper('storelocator')->__('Consumer Tyres'), 'required' => false));
        $fieldset->addField('tyre_brands', 'text', array('name' => 'tyre_brands', 'label' => Mage::helper('storelocator')->__('Tyre Brands'), 'title' => Mage::helper('storelocator')->__('Tyre Brands'), 'required' => false));
        $fieldset->addField('brake_fitting', 'checkbox', array('name' => 'brake_fitting', 'checked' => $model->getData('brake_fitting') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Brake Fitting'), 'title' => Mage::helper('storelocator')->__('Brake Fitting'), 'required' => false));
        $fieldset->addField('wheel_balancing_service', 'checkbox', array('name' => 'wheel_balancing_service', 'checked' => $model->getData('wheel_balancing_service') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Wheel Balancing Service'), 'title' => Mage::helper('storelocator')->__('Wheel Balancing Service'), 'required' => false));
        $fieldset->addField('wheel_alignment_service', 'checkbox', array('name' => 'wheel_alignment_service', 'checked' => $model->getData('wheel_alignment_service') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Wheel Alignment Service'), 'title' => Mage::helper('storelocator')->__('Wheel Alignment Service'), 'required' => false));
        $fieldset->addField('batteries_available', 'checkbox', array('name' => 'batteries_available', 'checked' => $model->getData('batteries_available') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Batteries Available'), 'title' => Mage::helper('storelocator')->__('Batteries Available'), 'required' => false));
        $fieldset->addField('nitrogen_available', 'checkbox', array('name' => 'nitrogen_available', 'checked' => $model->getData('nitrogen_available') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Nitrogen Available'), 'title' => Mage::helper('storelocator')->__('Nitrogen Available'), 'required' => false));
        $fieldset->addField('servicing_suburbs', 'text', array('name' => 'servicing_suburbs', 'label' => Mage::helper('storelocator')->__('Servicing Suburbs'), 'title' => Mage::helper('storelocator')->__('Servicing Suburbs'), 'required' => false));
        $fieldset->addField('wheelchair_access', 'checkbox', array('name' => 'wheelchair_access', 'checked' => $model->getData('wheelchair_access') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Wheelchair Access'), 'title' => Mage::helper('storelocator')->__('Wheelchair Access'), 'required' => false));
        $fieldset->addField('drop_off', 'checkbox', array('name' => 'drop_off', 'checked' => $model->getData('drop_off') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Offers Drop-off to work'), 'title' => Mage::helper('storelocator')->__('Offers Drop-off to work'), 'required' => false));
        $fieldset->addField('has_mobility_fleet', 'checkbox', array('name' => 'has_mobility_fleet', 'checked' => $model->getData('has_mobility_fleet') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Has Mobility Fleet'), 'title' => Mage::helper('storelocator')->__('Has Mobility Fleet'), 'required' => false));
        $fieldset->addField('waiting_area', 'checkbox', array('name' => 'waiting_area', 'checked' => $model->getData('waiting_area') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Waiting Area'), 'title' => Mage::helper('storelocator')->__('Waiting Area'), 'required' => false));
        $fieldset->addField('guest_wifi', 'checkbox', array('name' => 'guest_wifi', 'checked' => $model->getData('guest_wifi') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Guest Wifi'), 'title' => Mage::helper('storelocator')->__('Guest Wifi'), 'required' => false));
        $fieldset->addField('guest_tablet', 'checkbox', array('name' => 'guest_tablet', 'checked' => $model->getData('guest_tablet') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Guest Tablet'), 'title' => Mage::helper('storelocator')->__('Guest Tablet'), 'required' => false));
        $fieldset->addField('coffee_tea', 'checkbox', array('name' => 'coffee_tea', 'checked' => $model->getData('coffee_tea') == 1 ? true : false, 'label' => Mage::helper('storelocator')->__('Coffee & Tea'), 'title' => Mage::helper('storelocator')->__('Coffee & Tea'), 'required' => false));
        $fieldset->addField('shopping_nearby', 'text', array('name' => 'shopping_nearby', 'label' => Mage::helper('storelocator')->__('Nearby Shopping Centre (in distance)'), 'title' => Mage::helper('storelocator')->__('Nearby Shopping Centre (in distance)'), 'required' => false));
        $fieldset->addField('cafe_nearby', 'text', array('name' => 'cafe_nearby', 'label' => Mage::helper('storelocator')->__('Nearby cafe (in distance)'), 'title' => Mage::helper('storelocator')->__('Nearby cafe (in distance)'), 'required' => false));
        $fieldset->addField('public_transport', 'text', array('name' => 'public_transport', 'label' => Mage::helper('storelocator')->__('Distance to public transport'), 'title' => Mage::helper('storelocator')->__('Distance to public transport'), 'required' => false));
        $fieldset->addField('off_street_parking', 'checkbox', array(
            'name' => 'off_street_parking',
            'checked' => $model->getData('off_street_parking') == 1 ? true : false,
            'label' => Mage::helper('storelocator')->__('Off-street parking'),
            'title' => Mage::helper('storelocator')->__('Off-street parking'),
            'required' => false
        ));

        $fieldset->addField('nps_ratings_score', 'text', array(
            'label' => Mage::helper('storelocator')->__('NPS Ratings Score'),
            'title' => Mage::helper('storelocator')->__('NPS Ratings Score'),
            'name' => 'nps_ratings_score',
            'required' => false,
        ));

        $fieldset->addField('windscreen_wipers', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Windscreen Wipers'),
            'title' => Mage::helper('storelocator')->__('Windscreen Wipers'),
            'name' => 'windscreen_wipers',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('windscreen_wipers') == 1 ? true : false,
        ));

        $fieldset->addField('flat_tyres_repair', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Flat Tyre Repair'),
            'title' => Mage::helper('storelocator')->__('Flat Tyre Repair'),
            'name' => 'flat_tyres_repair',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('flat_tyres_repair') == 1 ? true : false,
        ));

        $fieldset->addField('road_hazard_warranty', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Road Hazard Warranty'),
            'title' => Mage::helper('storelocator')->__('Road Hazard Warranty'),
            'name' => 'road_hazard_warranty',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('road_hazard_warranty') == 1 ? true : false,
        ));

        $fieldset->addField('payment_eftpos', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Payment EFTPOS'),
            'title' => Mage::helper('storelocator')->__('Payment EFTPOS'),
            'name' => 'payment_eftpos',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('payment_eftpos') == 1 ? true : false,
        ));

        $fieldset->addField('payment_card', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Payment Visa, Mastercard'),
            'title' => Mage::helper('storelocator')->__('Payment Visa, Mastercard'),
            'name' => 'payment_card',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('payment_card') == 1 ? true : false,
        ));

        $fieldset->addField('payment_interest_free_terms', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Payment Interest Free Terms'),
            'title' => Mage::helper('storelocator')->__('Payment Interest Free Terms'),
            'name' => 'payment_interest_free_terms',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('payment_interest_free_terms') == 1 ? true : false,
        ));

        $fieldset->addField('email_copy_invoices', 'checkbox', array(
            'label' => Mage::helper('storelocator')->__('Email Copy Invoices'),
            'title' => Mage::helper('storelocator')->__('Email Copy Invoices'),
            'name' => 'email_copy_invoices',
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getData('email_copy_invoices') == 1 ? true : false,
        ));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return Mage::helper('storelocator')->__('Store Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return Mage::helper('storelocator')->__('Store Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab() {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden() {
        return false;
    }

    /**
     * Return JSON object with countries associated to possible websites
     *
     * @return string
     */
    public function getDefaultCountriesJson() {
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false, true);
        $result = array();
        foreach ($websites as $website) {
            $result[$website['value']] = Mage::app()->getWebsite($website['value'])->getConfig(
                    Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY
            );
        }

        return Mage::helper('core')->jsonEncode($result);
    }

}
