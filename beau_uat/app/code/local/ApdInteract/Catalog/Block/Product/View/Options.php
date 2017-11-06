<?php
class ApdInteract_Catalog_Block_Product_View_Options extends Mage_Catalog_Block_Product_View_Options {
	
	public function __construct()
	{
		parent::__construct();
		$this->addOptionRenderer(
				'default',
				'catalog/product_view_options_type_default'
				//'catalog/product/view/options/type/default.phtml' -- commented out the template file being used
		);
	}
	
}