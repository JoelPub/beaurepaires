<?php

class ApdInteract_Catalog_Model_Source_Services extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    public function getAllOptions() {
        return array(
            array(
                'label' => '',
                'value' => ''
            ),
            array(
                'label' => 'Tyre Fitting',
                'value' => 'Tyre Fitting'
            ),
            array(
                'label' => 'Wheel Fitting',
                'value' => 'Wheel Fitting'
            ),
            array(
                'label' => 'Battery',
                'value' => 'Battery'
            ),
            array(
                'label' => 'Mechanical',
                'value' => 'Mechanical'
            )
        );
    }

}
