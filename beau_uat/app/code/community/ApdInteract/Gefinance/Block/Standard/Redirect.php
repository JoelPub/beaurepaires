<?php

class ApdInteract_Gefinance_Block_Standard_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {

        $standard = Mage::getModel('gefinance/paymentmethod');
        $fields   = $standard->getStandardCheckoutFormFields();
        $main     = $fields['variables'];
        $items    = $fields['items'];
        $form     = new Varien_Data_Form();
        $apiUrl   = Mage::helper('gefinance')->getConfig('api_url');
        $form->setAction($apiUrl)->setId('gefinance_standard_checkout')->setName('gefinance_standard_checkout')->setMethod('POST')->setUseContainer(true);
        $fields = NULL;
        $values = "";
        $cssFile = Mage::getDesign()->getSkinUrl('dist/static/app.css');

        foreach ($main as $field => $value) {

            $form->addField($field, 'hidden', array(
                'name' => $field,
                'value' => $value
            ));

            if (isset($fields)) {
                $fields .= "," . $field;
            } else {
                $fields = $field;
            }

            $values .= $field . "=" . $value . ",";



        }
        foreach ($items as $field => $value) {

            foreach ($value as $f => $v) {
                $form->addField($f, 'hidden', array(
                    'name' => $f,
                    'value' => $v
                ));

                if (isset($fields)) {
                    $fields .= "," . $f;
                } else {
                    $fields = $f;
                }

                $values .= $f . "=" . $v . ",";



            }

        }

        ////echo $values;
        $pubkey = Mage::helper('gefinance')->getConfig('ge_key'); // this is a different key to the AES key referenced below.
        //$pubkey = Mage::helper('gefinance')->getAesKeyBase64Encoded();

        $signedFieldsPublicSignature = Mage::helper('gefinance')->hopHash($fields, $pubkey);

        // or use GE's own library
//        $signedFieldsPublicSignature = Mage::helper('gefinance')->encryptGeString($fields);

        $values .= 'signedFieldsPublicSignature=' . $signedFieldsPublicSignature;


        $orderPage_signaturePublic = Mage::helper('gefinance')->hopHash($values, $pubkey);

//        $orderPage_signaturePublic = Mage::helper('gefinance')->encryptGeString($values);


        $orderPage_signedFields = $fields;
        $form->addField("orderPage_signaturePublic", 'hidden', array(
            'name' => "orderPage_signaturePublic",
            'value' => $orderPage_signaturePublic
        ));
        $form->addField("orderPage_signedFields", 'hidden', array(
            'name' => "orderPage_signedFields",
            'value' => $orderPage_signedFields
        ));

        $idSuffix     = Mage::helper('core')->uniqHash();
        $submitButton = new Varien_Data_Form_Element_Submit(array(
            'value' => $this->__('Click here if you are not redirected within 10 seconds...')
        ));
        $id = "submit_to_gefinance_button_{$idSuffix}";
        $submitButton->setId($id);
        $form->addElement($submitButton);

        $html = '<!DOCTYPE html>
                <!--[if IE 8]>         <html class="no-js lt-ie9 offcanvas" lang="en"> <![endif]-->
                <!--[if IE 9]>         <html class="no-js lt-ie10 lt-ie9 offcanvas" lang="en"> <![endif]-->
                <!--[if gt IE 8]><!--> <html class="no-js offcanvas" lang="en"> <!--<![endif]-->';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=10.0,initial-scale=1.0" />';
        $html .= '<meta http-equiv="X-UA-Compatible" content="IE=Edge" />';
        $html .= '<title>Redirecting you to the Interest Free page</title>';
        $html .= '<link rel="stylesheet" type="text/css" href="'.$cssFile.'" media="all" />';
        $html .= '</head>';
        $html .= '<body><div class="redirect-panel">';
        $html .= '<div class="loading-icon"><i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></div>';
        $html .= '<div class="main">';
        $html .= '<div class="lock-icon"><i class="fa fa-lock" aria-hidden="true"></i></div>';
        $html .= '<div class="message">';
        $html .= $this->__('Redirecting you to the <strong>Interest Free</strong> page');
        $html .= '</div>';
        $html .= '<div class="form">';
        $html .= $form->toHtml();
        $html .= '<label for="'.$id.'">Click here if you are not redirected within 10 seconds...</label>';
        $html .= '</div></div></div>';
        $html .= '<script>document.getElementById("gefinance_standard_checkout").submit();</script>';
        $html .= '</body></html>';

        return $html;
    }
}
