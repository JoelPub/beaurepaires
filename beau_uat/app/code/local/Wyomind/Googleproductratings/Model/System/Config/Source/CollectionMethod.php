<?php

class Wyomind_Googleproductratings_Model_System_Config_Source_CollectionMethod
{

    function cmp($a, $b) 
    {

        return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() 
    {


        $attributes[] = array("value" => "unsolicited", 'label' => "Unsolicited");
        $attributes[] = array("value" => "post_fulfillment", 'label' => "Post fulfillment");


        return $attributes;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() 
    {
        $attributes[] = array("value" => "", 'label' => "-- not set --");
        $attributes[] = array("value" => "entity_id", 'label' => "Product ID");


        return $attributes;
    }

}
