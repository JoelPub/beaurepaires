<?php

class ApdInteract_Core_Model_Cookie extends Mage_Core_Model_Cookie {

    /**
     * Retrieve cookie lifetime
     *
     * @return int
     */
    public function getLifetime() {
        if (!is_null($this->_lifetime)) {
            $lifetime = $this->_lifetime;
        } else {
            $lifetime = (int) Mage::app()->getStore()->isAdmin() ?
                    Mage::getStoreConfig('admin/security/session_cookie_lifetime') : Mage::getStoreConfig(self::XML_PATH_COOKIE_LIFETIME, $this->getStore());
        }
        if (!is_numeric($lifetime)) {
            $lifetime = 3600;
        }

        return $lifetime;
    }

}
