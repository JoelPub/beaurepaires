<?php
class ApdInteract_SocialMedia_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Check Module is Enable or Disable
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getModuleStatus()
	{
		return Mage::getStoreConfig('social_media/socialmediasetting/socialmedia_enabled');
	}
	/**
	 * get facebook link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getFacebookUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/facebookurl');
	}
	/**
	 * get Linkedin link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getLinkedinUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/linkedinurl');
	}
	/**
	 * get Twitter link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getTwitterUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/twitterurl');
	}
	/**
	 * get Youtube link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getYoutubeUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/youtubeurl');
	}
	/**
	 * get Google + link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getGoogleUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/googleplusurl');
	}
        /**
	 * get Vimeo link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getVimeoUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/vimeourl');
	}
        /**
	 * get Instagram link Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getInstagramUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/instagramurl');
	}
        /**
	 * get Pinterest Details
	 *
	 * @return ApdInteract_SocialMedia_Helper_Data
	 */
	public function getPinterestUrl()
	{
		return Mage::getStoreConfig('social_media/socialmediashare/pinteresturl');
	}
        
}
