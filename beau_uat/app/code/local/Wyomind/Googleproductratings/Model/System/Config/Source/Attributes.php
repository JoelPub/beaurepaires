<?php

class Wyomind_Googleproductratings_Model_System_Config_Source_Attributes
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


        /* R�cup�rer l'id du type d'attributs */
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tableEet = $resource->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];



        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId)
                ->addSetInfo()
                ->getData();


        usort($attributesList, array("Wyomind_Googleproductratings_Model_System_Config_Source_Attributes", "cmp"));
        $attributes = array();
        $attributes[] = array("value" => "", 'label' => "-- not set --");
        $attributes[] = array("value" => "entity_id", 'label' => "Product ID");

        foreach ($attributesList as $attribute) {

            if (!empty($attribute['frontend_label']) && $attribute["is_unique"]) {
                $attributes[] = array("value" => $attribute['attribute_code'], 'label' => $attribute['frontend_label']);
            }
        }
        foreach ($attributesList as $attribute) {


            if (!empty($attribute['frontend_label']) && !$attribute["is_unique"]) {
                $attributes[] = array("value" => $attribute['attribute_code'], 'label' => $attribute['frontend_label']);
            }
        }
        return $attributes;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() 
    {
        /* R�cup�rer l'id du type d'attributs */
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tableEet = $resource->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];

        function cmp($a, $b) 
        {

            return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
        }

        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId)
                ->addSetInfo()
                ->getData();


        usort($attributesList, "cmp");
        $attributes = array();

        foreach ($attributesList as $attribute) {


            if (!empty($attribute['frontend_label']))
                $attributes[] = array("value" => $attribute['attribute_id'], 'label' => $attribute['frontend_label']);
        }



        return $attributes;
    }

}
