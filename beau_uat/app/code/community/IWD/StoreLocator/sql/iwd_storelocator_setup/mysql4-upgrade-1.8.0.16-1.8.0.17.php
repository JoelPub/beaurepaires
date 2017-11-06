<?php
try{
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
		ALTER TABLE {$this->getTable('storelocator/region')} ADD `page_title` TEXT NOT NULL , ADD `page_description` TEXT NOT NULL;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='ACT Tyres Stores With Low Prices & Quick Fittings', page_description='Get low prices on a great range of tyres across ACT at our Beaurepaires ACT stores. Find out closes ACT tyre store now.' WHERE url_key='act' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Adelaide Tyres At 29 SA Locations - Beaurepaires!', page_description='Beaurepaires has the leading tyres, low prices and quick fittings across Adelaide. View online our Adelaide tyres available and book in your fitting!' WHERE url_key='adelaide' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Brisbane Tyres At 50 Beaurepaires QLD Locations', page_description='Beaurepaires is Brisbane\'s leading tyre dealer with tyre stores across Queensland. Use our \'store locator\' to find your nearest Brisbane tyre location.' WHERE url_key='brisbane' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Beaurepaires Tyres Stores In Canberra With Quick Fittings', page_description='Get the leading tyre brands at low prices across our Canberra Beaurepaires stores. View our Canberra tyre stores online now.' WHERE url_key='canberra' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='View our 4 Darwin Tyre Stores With Low Prices', page_description='View our Darwin tyre stores offering low prices, expert advice and assistance. You can book online or call your local tyre store.' WHERE url_key='darwin' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Geelong Tyres At Low Prices - View Our \'Top 3\' Stores', page_description='View Beaurepaires Geelong tyre stores offering low prices and the leading brands. Get fitted quickly at your nearest Geelong tyre store.' WHERE url_key='geelong' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Gold Coast Tyres At 48 QLD Tyre Stores - Beaurepaires', page_description='Find out local Gold Coast tyre stores at Beaurepaires with great service and advice. View our low prices and book a Gold Coast tyre fitting today.' WHERE url_key='gold-coast' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Hobart Tyres At Beaurepaires - Approved by Experts', page_description='Visit and purchase all your needs when you visit Beaurepaires Hobart. It offers top quality tyres approved by experts. Check us out today!' WHERE url_key='hobart' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Melbourne Tyres At Over 80 VIC Locations - Beaurepaires', page_description='Beaurepaires has the best advice, range and service at 80+ Melbourne Tyre Stores. Find your nearest Melbourne tyre store online!' WHERE url_key='melbourne' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Newcastle Tyres At 4 Excellent Locations - Beaurepaires', page_description='Get fitted with a leading tyre at our Newcastle tyre stores offering low tyre prices. View our Newcastle stores and book in your car. ' WHERE url_key='newcastle' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Northern Territory Beaurepaires Tyres Stores', page_description='View Norther Territory Beaurepaires tyre stores offering great service and advice. View our low prices online and make a booking.' WHERE url_key='northern-territory' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='NSW Beaurepaires Stores With Low Prices On Tyres!', page_description='Beaurepaires has NSW tyre stores in all locations including Sydney & Newcastle. Find out local NSW tyre store today.' WHERE url_key='nsw' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Tyres Stores In Queensland With Low Prices - Beaurepaires', page_description='View Beaurepaires Queensland tyre stores located from Gold Coast to Cairns. Compare our tyres and stores and make a booking today.' WHERE url_key='queensland' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Sydney Tyres At 58 locations across NSW - Beaurepaires', page_description='Get great service and advice at our Sydney Tyre stores and compare our full range. Contact your locate Sydney tyres store today!' WHERE url_key='sydney' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='View Beaurepaires Stores Across Tasmania - Quick Fittings', page_description='Get fitted today on a great range of low price tyres at our Tasmania Tyre stores. Find your local Tasmania Beaurepaires store today!' WHERE url_key='tasmania' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Victoria Beaurepaires Tyre Store Locations - Book Today', page_description='Find the Victoria tyre store that\'s close to you with Beaurepaires store locator tool. Book a quick fitting now on our range of low price tyres.' WHERE url_key='victoria' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='WA Beaurepaires Stores With Quick Fittings', page_description='View our complete list of Beaurepaires WA tyre stores from Perth to Kalgoorlie. Compare our WA shops and book online now!' WHERE url_key='wa' LIMIT 1;                    
		");

$installer->endSetup();
}catch (Exception $e){
    
}