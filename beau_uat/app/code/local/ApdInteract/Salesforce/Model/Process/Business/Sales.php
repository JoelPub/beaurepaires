<?php
class ApdInteract_Salesforce_Model_Process_Business_Sales
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	public function process() {
	
		echo "export sales \n";
		$sales = Mage::getModel("apdinteract_salesforce/process_business_export_sales");
		$sales->process();
                
                echo "export sales order item(s)";
                Mage::Helper('apdinteract_salesforce')->mapOrderDetails();               
                
                echo "export order comments history \n";
		$credit_memo = Mage::getModel("apdinteract_salesforce/process_business_export_sales_orderhistory");
		$credit_memo->process();
                
		echo "export payment transactions \n";
		$transactions = Mage::getModel("apdinteract_salesforce/process_business_export_sales_transactions");
		$transactions->process();		
		
                echo "export invoices \n";
		$invoice = Mage::getModel("apdinteract_salesforce/process_business_export_sales_invoice_invoice");
		$invoice->process();
                
                echo "export invoice items \n";
		$invoice_items = Mage::getModel("apdinteract_salesforce/process_business_export_sales_invoice_item");
		$invoice_items->process();
                
                echo "export invoices comment \n";
		$invoice_comment = Mage::getModel("apdinteract_salesforce/process_business_export_sales_invoice_comment");
		$invoice_comment->process();
                
                echo "export credit memos \n";
		$credit_memo = Mage::getModel("apdinteract_salesforce/process_business_export_sales_creditmemo_creditmemo");
		$credit_memo->process();
                
                echo "export credit memo items \n";
		$credit_memo = Mage::getModel("apdinteract_salesforce/process_business_export_sales_creditmemo_item");
		$credit_memo->process();
                
                echo "export credit memo comments \n";
		$credit_memo = Mage::getModel("apdinteract_salesforce/process_business_export_sales_creditmemo_comment");
		$credit_memo->process();
		
		return $this;
	}
	
}