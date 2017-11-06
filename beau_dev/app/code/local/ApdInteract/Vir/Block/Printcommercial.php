<?php

class ApdInteract_Vir_Block_Printcommercial extends Mage_Core_Block_Template {

    private $_JsignatureClass;
    private $_virModel;

    public function _construct() {
        require_once( Mage::getBaseDir('lib') . '/pdf/fpdf/fpdf.php');
        require_once( Mage::getBaseDir('lib') . '/pdf/fpdi/fpdi.php');
        require_once( Mage::getBaseDir('lib') . '/jSignature/jSignature.php');
        parent::_construct();
    }

    private function _hideOverflow($string, $maxlength = 35) {
        if (strlen($string) > $maxlength) {
            $string = substr($string, 0, $maxlength) . '...';
        }
        return $string;
    }

    private function _getJsignatureClass() {
        ////Example use:        
        //$converter = new jSignatureConverter();
        //$sig = 'data:image/jsignature;base30,8K100000Z3101110000101210101000000Y21112010122675978644511000000Z132111454778987764_2Ubdgfehc964675967Za8597969556576978567546443010323221Y14586bfijkec85420220100000Z1_bSZ545232010Y156764565565414300000Z1465556544_4N0476766554235112100Z65554556586452100Y1112_eB00000000110Z1_7BZ899886554465_eC5342435554433120213022211_5IZ475422000Y1423565555745354_muZ45333745262230000000Y31222443442234346864_1VZ1Y223375537345665767865334434535345310Z112_mM02112201103000_3uc7bab95648795Z5_mB994755_4JZ231231_nT0000322312211110000000003744223433132_2N5575899686646a65Z876a785730Y14344746563_rv34535831Z57645444542100Y103636594854354_5PZ21323544423221210Y13855464221001221201_sw65757888975454434532000Z2347468635643454556_2vZ1000000Y235346789a8536565445244323342134212';
        //$png_path = $converter->setBase30($sig)->getPng();

        if (!isset($this->_JsignatureClass)) {


            $this->_JsignatureClass = new jSignatureConverter();
        }
        return $this->_JsignatureClass;
    }

    private function _getSignaturePng($base30) {
        // Converts raw sig data to PNG, writes to temp file, returns file path
        return $this->_getJsignatureClass()->setBase30($base30)->getPng();
    }

    private function _getVirData() {
        if (!isset($this->_virModel)) {
            $id = Mage::app()->getRequest()->getParam('id');
            $this->_virModel = Mage::getModel('apdinteract_vir/ordercommercial')->load($id);
        }
        return $this->_virModel;
    }

    private function _formatPdfDate($string) {
        // in 2016-12-13
        // out '15      10     2015'
        $bits = explode('-', $string);
        if (is_array($bits)) {
            return "{$bits[2]}      {$bits[1]}     {$bits[0]}";
        }
    }

    private function _getCb($bool, $cb_string = 'X') {
        if ($bool) {
            return $cb_string;
        }
        return '';
    }

    private function _getShortPayment($vir) {
        $paymentname = array();
        if($vir->getData('custapprpaycash')==1)
            $paymentname[] ="Cash";
        if($vir->getData('custapprpayvisamaster')==1)
            $paymentname[] ="Visa/M'card";
        if($vir->getData('custapprpayaccount')==1)
            $paymentname[] ="Account";
        if($vir->getData('custapprpayother')==1)
            $paymentname[] ="Other";
        if($vir->getData('custapprpayeftpos')==1)
            $paymentname[] ="Eftpos";
        if($vir->getData('custapprpayamexdiners')==1)    
            $paymentname[] ="Amex/Diners";
        if($vir->getData('custapprpayfinance')==1)    
            $paymentname[] ="Finance";
        return implode(',', $paymentname);
    }

    public function getPdf() {

        $vir = $this->_getVirData();
//        
//        echo '<pre>';        
//        print_r($vir->getData());
//        die();


        $pdf = new FPDI(); // & new FPDI();

        //Set the source PDF file
        $pdf->setSourceFile(Mage::getBaseDir('skin') . "/adminhtml/default/default/vir_pdf/vir_commercial.pdf"); // Needs to be PDF 1.4 or lower format

        $tppl = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tppl);
        $pdf->AddPage('P', array($size['w'], $size['h']));
        $pdf->useTemplate($tppl, 0, 0, 210); //225

//Select Arial italic 10
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
		$pdf->SetAutoPageBreak(true, 1);

		################## GLOBALS
		//$generatedDate = date('Y-m-d');
		//generatedTime = date("H-i");
		$generatedTime = Mage::getModel('core/date')->date('Y-m-d_H-i');
		$wheelmark = '[    ]';

		################# Signatures
		//// TODO: Tweak position and size once real sign on glass sigs are there
        $sig = $vir->getData('customersignatureimage');
        if (!empty($sig)) {
            $pdf->Image($this->_getSignaturePng($sig), 145, 255, null, 8);  // Customer Signature #1
        }
        
				################# Signatures
		// TODO: Tweak position and size once real sign on glass sigs are there
		//$pdf->Image('small_signature/signature1.png',65,224 + $rowshift,30);  // Signature #1
		//$pdf->Image('small_signature/signature2.png',145,230 + $rowshift,30); // Signature #2
		//$pdf->Image('small_signature/signature3.png',145,256 + $rowshift,30); // Signature #3
        
		$bookingDate = is_empty_date($vir->getData('booking_date')) ? "00/00/0000" : date('d/m/Y', strtotime($vir->getData('booking_date')));
		
		$pdf->SetXY(30, 19); //Booking
        $pdf->Write(0, $vir->getData('appointmentid'));
		
		$pdf->SetXY(82, 19); //Booking Date
        $pdf->Write(0, $bookingDate . ' ' . date('g:i a', strtotime($vir->getData('booking_time'))));
		
		$pdf->SetXY(140, 17); //Store
        $pdf->MultiCell(45, 4, $storeName = Mage::helper('apdinteract_vir')->getStoreName($vir->getData('parent_id')), 0, 'L');
		
		$pdf->SetXY(30, 26.5); //Created
        $pdf->Write(0, $this->_formatPdfDate($vir->getData('inspectiondate')));
		
		$pdf->SetXY(83, 26.5); //Invoice
        $pdf->Write(0, $vir->getData('Invoicenumber'));
		
		$pdf->SetXY(30, 32.5); //Bay No.
        $pdf->Write(0, $vir->getData('baynumber'));
		
		$pdf->SetXY(83, 32.5); //Order Number
        $pdf->Write(0, $vir->getData('ordernumber'));
		
		$pdf->SetXY(130, 30.5); //Cash or Account
        $pdf->MultiCell(60, 4, $this->_getShortPayment($vir), 0, 'L');
		
		$pdf->SetXY(30, 39.5); //Customer
        $pdf->Write(0, $customerName = $vir->getData('customername'));
		
		$pdf->SetXY(160, 39.5); //Phone
        $pdf->Write(0, $vir->getData('phonenumber'));
		
		$pdf->SetXY(46, 46); //Building
        $pdf->Write(0, $vir->getData('addressline1'));
		
		$pdf->SetXY(141, 46); //Street
        $pdf->Write(0, $vir->getData('addressline2'));
		
		$pdf->SetXY(30, 52.5); //Suburb
        $pdf->Write(0, $vir->getData('suburb'));
		
		$pdf->SetXY(141, 52); //Postcode
        $pdf->Write(0, $vir->getData('postcode'));
		
		$pdf->SetXY(30, 59); //Rego No
        $pdf->Write(0, $vir->getData('regonumber'));
		
		$pdf->SetXY(106, 59); //Fleet No
        $pdf->Write(0, $vir->getData('fleetnumber'));
		
		$pdf->SetXY(160, 59); //Speedo/Hub
        $pdf->Write(0, $vir->getData('speedohubreading'));
        
		$pdf->SetXY(45, 111); //Work Required
        $pdf->Write(0, $vir->getData('workrequireddone'));
		
		// Prime Mover
		if($vir->getData('wheelp2')) {
			$pdf->SetXY(14.5, 78); //p2
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp2a')) {
			$pdf->SetXY(27, 78); //p2a
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp1')) {
			$pdf->SetXY(14.5, 88); //p1
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp1a')) {
			$pdf->SetXY(27, 88); //p1a
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp6')) {
			$pdf->SetXY(42.5, 69); //p6
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp10')) {
			$pdf->SetXY(56.3, 69); //p10
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp5')) {
			$pdf->SetXY(42.5, 77.5); //p5
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp9')) {
			$pdf->SetXY(56.3, 77.5); //p9
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp4')) { 
			$pdf->SetXY(42.5, 87.5); //p4
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp8')) {
            $pdf->SetXY(56.3, 87.5); //p8
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp3')) {
			$pdf->SetXY(42.5, 97); //p3
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelp7')) {
			$pdf->SetXY(56.3, 97); //p7
			$pdf->Write(0, $wheelmark);
		}
		
		// Trailer A
		if($vir->getData('wheela4')) {
			$pdf->SetXY(74, 69); //a4
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela8')) {
			$pdf->SetXY(87.3, 69); //a8
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela12')) {
			$pdf->SetXY(100.5, 69); //a12
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela3')) {
			$pdf->SetXY(74, 77.5); //a3
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela7')) {
			$pdf->SetXY(87.3, 77.5); //a7
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela11')) {
			$pdf->SetXY(100.5, 77.8); //a11
			$pdf->Write(0, $wheelmark);
		}
	
		if($vir->getData('wheela2')) {
			$pdf->SetXY(74, 87.5); //a2
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela6')) {
			$pdf->SetXY(87.3, 87.5); //a6
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela10')) {
			$pdf->SetXY(101, 87.5); //a10
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela1')) {
			$pdf->SetXY(74, 97); //a1
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela5')) {
			$pdf->SetXY(87.3, 97); //a5
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheela9')) {
			$pdf->SetXY(100.5, 97); //a9
			$pdf->Write(0, $wheelmark);
		}
		
		// Trailer B
		if($vir->getData('wheelb4')) {
			$pdf->SetXY(118.5, 69); //b4
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb8')) {
			$pdf->SetXY(131.5, 69); //b8
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb12')) {
			$pdf->SetXY(145, 69); //b12
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb3')) {
			$pdf->SetXY(118.5, 77.5); //b3
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb7')) {
			$pdf->SetXY(131.5, 77.5); //b7
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb11')) {
			$pdf->SetXY(145.5, 77.5); //b11
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb2')) {
			$pdf->SetXY(118.5, 87.5); //b2
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb6')) {
			$pdf->SetXY(131.5, 87.5); //b6
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb10')) {
			$pdf->SetXY(146, 87.5); //b10
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb1')) {
			$pdf->SetXY(118.5, 97); //b1
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb5')) {
			$pdf->SetXY(131.5, 97); //b5
			$pdf->Write(0, $wheelmark);
		}
		
		if($vir->getData('wheelb9')) {
			$pdf->SetXY(145, 97); //b9
			$pdf->Write(0, $wheelmark);
		}

        // Spare
        if($vir->getData('spare4')) {
            $pdf->SetXY(164, 69); //s4
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare8')) {
            $pdf->SetXY(177, 69); //s8
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare12')) {
            $pdf->SetXY(190.5, 69); //s12
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare3')) {
            $pdf->SetXY(164, 77.5); //s3
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare7')) {
            $pdf->SetXY(177, 77.5); //s7
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare11')) {
            $pdf->SetXY(190.5, 77.5); //s11
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare2')) {
            $pdf->SetXY(164, 87.5); //s2
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare6')) {
            $pdf->SetXY(177, 87.5); //s6
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare10')) {
            $pdf->SetXY(190.7, 87.5); //s10
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare1')) {
            $pdf->SetXY(164, 97); //s1
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare5')) {
            $pdf->SetXY(177, 97); //s5
            $pdf->Write(0, $wheelmark);
        }
        
        if($vir->getData('spare9')) {
            $pdf->SetXY(190.5, 97); //s9
            $pdf->Write(0, $wheelmark);
        }
	
		$gridCoordsY = array(
			'newsteer' => 121,
			'newdrive' => 128,
			'newtrailer' => 134,
			'rdttrailer' => 140,
			'rdtdrive' => 147,
			'casing' => 153,
			'puncture' => 159,
			'scrap' => 166,
			'rotate' => 172,
			'tube' => 179,
			'fitsteelalloylttrk' => 185,
			'balancestalloyltpowder' => 192,
			'rustband' => 198,
			'valvestem' => 205,
			'valveext' => 211
		);
		
		foreach($gridCoordsY as $key => $yCoord) {
			$pdf->SetXY(60, $yCoord); 
			$pdf->Write(0, $vir->getData($key.'size'));
			
			$pdf->SetXY(90, $yCoord); 
			$pdf->Write(0, $vir->getData($key.'pattern'));
			
			$pdf->SetXY(123, $yCoord);
			$pdf->Write(0, $vir->getData($key.'qty'));
			
			$pdf->SetXY(135, $yCoord); 
			$pdf->Write(0, $this->_hideOverflow($vir->getData($key.'comments')));
		}
				
		$pdf->SetXY(60, 226); 
		$pdf->Write(0, $vir->getData('workperformedandcheckedby'));
		
		$pdf->SetXY(130, 232); 
		$pdf->Write(0, $vir->getData('nutstentionedby'));
		
		$pdf->SetXY(94, 233); 
		$pdf->Write(0, 'X');
		
		if ($vir->getData('customeralertnuts')) {
			$pdf->SetXY(10.3, 256); 
			$pdf->Write(0, 'X');
		}
		
		if($vir->getData('customeralertforkliftnuts')) {
			$pdf->SetXY(10.3, 262); 
			$pdf->Write(0, 'X');
		}
		
		$pdf->SetXY(9, 286); 
		$pdf->Write(0, 'Beaurepaires Vehicle Inspection Report, Page 1');
		
		$logo_src = Mage::getBaseDir('skin') . "/adminhtml/default/default/vir_pdf/beaurepaires-logo.png";
        $pdf->Image($logo_src, 135, 269, 65); //apply new logo

		// ########################################
		// The magic line:
		$filename = "{$storeName}commercial_vir{$generatedTime}_{$customerName}.pdf";
		$pdf->Output($filename, "I");
		// D = download, I = inline to browser, F = file, S = string (for save in DB, I guess ?!)
		// I = testing, D = production.
    }

}
