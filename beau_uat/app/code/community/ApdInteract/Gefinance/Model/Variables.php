<?php
class ApdInteract_Gefinance_Model_Variables
{
	/* Request Variables*/
	const MERCHANTID = 'merchantID';
	const MERCHANTREFERENCECODE = 'merchantReferenceCode';
	const ORDERPAGE_SERIALNUMBER = 'orderPage_serialNumber';
	const ORDERPAGE_VERSION = 'orderPage_version';
        const ORDERPAGE_TIMESTAMP = 'orderPage_timestamp';
	const PURCHASETOTALS_GRANDTOTALAMOUNT = 'purchaseTotals_grandTotalAmount';
	const PURCHASETOTALS_CURRENCY = 'purchaseTotals_currency';
	const ITEMCOUNT = 'itemCount';
	const PROMOCODE = 'promoCode';
	const PROMODESCRIPTION = 'promoDescription';
	const MERCHANT_EAPPSDATA = 'merchant_eappsData';
	const EAPPS_HOPDATA = 'eapps_hopData';
        
	/*configs*/
	const ORDERPAGE_VERSION_DATA = '7';
	const PURCHASETOTALS_CURRENCY_DATA = '036';
	
	/* Postback Variables*/
	
	const REASONCODE = 'reasonCode';
	const REASONMESSAGE = 'reasonMessage';
	const DECISION = 'decision';
	const REQUESTID = 'requestID';
	const DECISION_REJECT = 'REJECT';
	const DECISION_ACCEPT = 'ACCEPT';
	const DECISION_ERROR = 'ERROR';
	const DECISION_CANCELLED = 'CANCELLED';
	const CCAUTHREPLY_AMOUNT = 'ccAuthReply_amount'; 
	

}