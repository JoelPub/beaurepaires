<?php
class ApdInteract_Vir_Block_Printconsumer extends Mage_Core_Block_Template {

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
            $this->_virModel = Mage::getModel('apdinteract_vir/order')->load($id);
        }
        return $this->_virModel;
    }

    private function _formatPdfDate($string) {
        // in 2016-12-13
        // out '15          10       2015'
        $bits = explode('-', $string);
        if (is_array($bits)) {
            return "{$bits[2]}          {$bits[1]}       {$bits[0]}";
        }
    }

    private function _getCb($bool, $cb_string = 'X') {
        if ($bool) {
            return $cb_string;
        }
        return '';
    }

    public function getPdf() {

        $vir = $this->_getVirData();
//
//        echo '<pre>';
//        print_r($vir->getData());
//        die();

        $pdf = new FPDI(); // & new FPDI();

        //Set the source PDF file
        $pdf->setSourceFile(Mage::getBaseDir('skin') . "/adminhtml/default/default/vir_pdf/vir_consumer.pdf"); // Needs to be PDF 1.4 or lower format

        $tppl = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tppl);
        $pdf->AddPage('P', array($size['w'], $size['h']));
        $pdf->useTemplate($tppl, 0, 0, 225);

//Select Arial italic 10
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);


################## GLOBALS
        $rowshift = 0; // Easier than adjusting all the numbers below
################# Signatures
        $sig = $vir->getCustapprsignatureimage();
        if (!empty($sig)) {
            $pdf->Image($this->_getSignaturePng($sig), 153, 283, null, 7);  // Customer Signature #1
        }
        $sig = $vir->getWorkshopsignatureimage();
        if (!empty($sig)) {
            $pdf->Image($this->_getSignaturePng($sig), 66, 320, null, 8);  // Workshop Signature #2
        }

################# Booking details Fields
        $cols['bdl'][1] = 17;
        $cols['bdl'][2] = 51;
        $cols['bdl'][3] = 103.5;

        $rows['bdl'][1] = 35.5;

        $bookingDate = is_empty_date($vir->getData('booking_date')) ? "00/00/0000" : date('d/m/Y', strtotime($vir->getData('booking_date')));
        $fields['bdl'][1][1] = $vir->getData('appointmentid');
        $fields['bdl'][2][1] = $bookingDate . ' ' . date('g:i a', strtotime($vir->getData('booking_time')));
        $fields['bdl'][3][1] = Mage::helper('apdinteract_vir')->getStoreName($vir->getData('parent_id')); // Store name

################# Top fields

        $cols['top'][1] = 30;
        $cols['top'][2] = 57;
        $cols['top'][3] = 170;
        $cols['top'][4] = 45;
        $cols['top'][5] = 112;
        $cols['top'][6] = 170;

        $rows['top'][1] = 11.5;
        $rows['top'][2] = 18.5;
        $rows['top'][3] = 25.5;
        $rows['top'][4] = 37.5;
        $rows['top'][5] = 44.5;
        $rows['top'][6] = 47;
        $rows['top'][7] = 60.5;
        $rows['top'][8] = 68;
        $rows['top'][9] = 70.5;
        $rows['top'][10] = 74.5;
        $rows['top'][11] = 54;

        $fields['top'][3][1] = $this->_formatPdfDate($vir->getOrderdate()); // Date
        $fields['top'][3][2] = date("g:i a", strtotime($vir->getTimereq())); // '11:37 am'; // Time Req. // 10:00:00
        $fields['top'][3][3] = $vir->getInvoiceno(); // '9120398432568'; // Invoice No.
        $fields['top'][1][6] = $vir->getCustname(); // 'Rev. Ronald C. McDonald Snr.'; // Name
        $fields['top'][2][7] = $vir->getCustaddress(); // Building/Street Number
        $fields['top'][4][11] = $vir->getCustcompany(); // Company name
        $fields['top'][1][8] = $vir->getCustsuburb(); // Suburb + State
        $fields['top'][3][7] = $vir->getCustaddress2(); // Street name
        $fields['top'][3][8] = $vir->getCustpostcode(); // '3000'; // Postcode

        $fields['top'][5][10] = $vir->getCustphoneno(); //Phone +  // TODO add mobile
        $fields['top'][6][10] = $vir->getCustphonemobile(); //Mobile // TODO add mobile
        $fields['top'][1][10] = $vir->getCustemail(); // 'ronald@triplebypass.com.au'; // Email
// ############## Top Checkbox (tcb) FIELDS
        $cb = 'X';

        $cols['tcb'][1] = 17.5;
        $cols['tcb'][2] = 49;
        $cols['tcb'][3] = 80;
        $cols['tcb'][4] = 116.5;
        $cols['tcb'][5] = 148;
        $cols['tcb'][6] = 179.5;

        $rows['tcb'][1] = 88;

        $bal_selected = $vir->getLastbalance() + 1; // (0,1,2 => 1,2,3)
        $align_selected = $vir->getLastwheelalignment() + 4; // (0,1,2 => 4,5,6)

//    lastbalance] => 2
//    lastwheelalignment] => 1

        $fields['tcb'][$bal_selected][1] = $cb; // Bal: 0-6
//        $fields['tcb'][1][1] = $cb; // Bal: 0-6
//        $fields['tcb'][2][1] = $cb; // Bal: 6-12
//        $fields['tcb'][3][1] = $cb; // Bal: 12+
        $fields['tcb'][$align_selected][1] = $cb; // WheelAlign: 0-6
//        $fields['tcb'][4][1] = $cb; // WheelAlign: 0-6
//        $fields['tcb'][5][1] = $cb; // WheelAlign: 6-12
//        $fields['tcb'][6][1] = $cb; // WheelAlign: 12+
// ############## Vehicle Details (vdt) FIELDS

        $cols['vdt'][1] = 17.5;
        $cols['vdt'][2] = 50.5;
        $cols['vdt'][3] = 74.5;
        $cols['vdt'][4] = 127;
        $cols['vdt'][5] = 157;

        $rows['vdt'][1] = 109;
        $rows['vdt'][2] = 120;

        //vehicleyear vehiclemodel vehiclemake vehicleseries
        // Remove make from model
        $model = str_replace($vir->getData('vehiclemake'), '', $vir->getData('vehiclemodel'));

        $fields['vdt'][1][1] = $vir->getData('vehiclemake'); // 'Vauxhall'; // Make
        $fields['vdt'][2][1] = $vir->getData('vehicleyear');
        $fields['vdt'][3][1] = $this->_hideOverflow($model); //'Open Top Cruiser Max Mk III'; // Model
        $fields['vdt'][4][1] = $vir->getData('vehiclerego');
        //$fields['vdt'][3][1] = $vir->getData('vehiclerego');
        $fields['vdt'][1][2] = $vir->getData('vehicleseries'); //'394-MCD'; // Rego
        $fields['vdt'][5][1] = $vir->getData('vehicleodometer'); //'1120394815'; // Odometer
// ############## Comments Text Box

        $comments = $this->_hideOverflow(str_replace(array("\r","\n"), ' ', $vir->getData('vehiclecomments')), 810); //"I recently have had Goodyear Eagle F1 Asymmetric 2's fitted to my car (225-40-18-92Y) and so far they are a great tyre, very grippy, quiet and comfortable. I had great difficulty deciding on which tyre to buy for my car as there are so many different tyres on the market these days. I wanted a tyre that offered great handling and braking in both dry and wet conditions, is quiet and comfortable and has decent rolling resistance. The GY EF1 A2 has got great reviews and I thought that this would be my next tyre of choice. However, when I inquired on prices through several of the larger tyre franchises I was quoted an average of $355 per tyre. To put that into perspective, other premium tyres were quoted at around $100 less per tyre from the likes of Michelin, Pirelli and Continental.";

        $pdf->SetXY(18, 127.5);
        $pdf->MultiCell(97, 5, $comments, 0);

// ############## Car drawing

        $cols['car'][1] = 131.5;
        $cols['car'][2] = 191;

        $rows['car'][1] = 129;
        $rows['car'][2] = 149;
        $rows['car'][3] = 168;

        $src = Mage::getBaseDir('skin') . "/adminhtml/default/default/vir_pdf/circle.png";
        $width = 12;
        if ($vir->getData('vehiclewheelLF')) $image['car'][1][1] = $src; // LF
        if ($vir->getData('vehiclewheelRF')) $image['car'][2][1] = $src; // RF
        if ($vir->getData('vehiclewheelLR')) $image['car'][1][2] = $src; // LR
        if ($vir->getData('vehiclewheelRR')) $image['car'][2][2] = $src; // RR
        if($vir->getData('vehicle_wheel_extra_spare'))$image['car'][2][3] = $src; // Spare Extra


        foreach ($image['car'] as $colnum => $rownums) {
            foreach ($rownums as $rownum => $value) {
                $pdf->Image($value, $cols['car'][$colnum], $rows['car'][$rownum] + $rowshift, $width);
            }
        }

        // Spare
        if ($vir->getData('vehiclewheelSPARE')) $pdf->Image($value, 153, 170 + $rowshift, 30, 13);

// ############## Car text (ctxt)
        $cols['ctxt'][1] = 129;
        $cols['ctxt'][2] = 150;
        $cols['ctxt'][3] = 190;


        $rows['ctxt'][1] = 128;
        $rows['ctxt'][2] = 148;
        $rows['ctxt'][3] = 170;
        $rows['ctxt'][4] = 187;
        $rows['ctxt'][5] = 167;

        $fields['ctxt'][1][1] = $this->_hideOverflow($vir->getData('vehiclewheelLFtorque'), 8); // '302 nm'; // LF
        $fields['ctxt'][3][1] = $this->_hideOverflow($vir->getData('vehiclewheelRFtorque'), 8); //'302 nm'; // RF
        $fields['ctxt'][1][2] = $this->_hideOverflow($vir->getData('vehiclewheelLRtorque'), 8); //'302 nm'; // LR
        $fields['ctxt'][3][2] = $this->_hideOverflow($vir->getData('vehiclewheelRRtorque'), 8); //'302 nm'; // RR
        $fields['ctxt'][3][5] = $this->_hideOverflow($vir->getData('vehicle_wheel_extra_spare_torque'), 8); // Spare Extra
        // $fields['ctxt'][2][3] = $this->_hideOverflow($vir->getData('vehiclewheelSPAREtorque'), 18); //'302 nm'; // Spare
        $fields['ctxt'][2][4] = $this->_hideOverflow($vir->getData('vehiclewheelSPAREtorque'), 38); //'Flat as'; // Spare comment
        //
// ############## Quote / Work Order (qwo)
        $cols['qwo'][1] = 30;
        $cols['qwo'][2] = 47.5;
        $cols['qwo'][3] = 62;
        $cols['qwo'][4] = 72;
        $cols['qwo'][5] = 93;
        $cols['qwo'][6] = 119;
        $cols['qwo'][7] = 152;
        $cols['qwo'][8] = 183;
        $cols['qwo'][9] = 50;
        $cols['qwo'][10] = 43;

        $rows['qwo'][1] = 207;
        $rows['qwo'][2] = 213.5;
        $rows['qwo'][3] = 219.5;
        $rows['qwo'][4] = 225.5;
        $rows['qwo'][5] = 231;
        $rows['qwo'][6] = 237;
        $rows['qwo'][7] = 243;
        $rows['qwo'][8] = 249;
        $rows['qwo'][9] = 255;
        $rows['qwo'][10] = 261;

        $pricetotal = 0;

        $fields['qwo'][1][1] = $this->_hideOverflow($vir->getData('workordertyresdescription'), 78); //'Change tyres, inflate and polish'; // tyres description
        $fields['qwo'][7][1] = $vir->getData('workordertyresqty'); //'5'; // tyres qty
        $fields['qwo'][8][1] = $vir->getData('workordertyresprice'); //'$990'; // tyres price

        if ($vir->getData('workorderbalancesoptionsteel')) $fields['qwo'][4][2] = $cb; // Bal: Steel
        if ($vir->getData('workorderbalancesoptionalloy')) $fields['qwo'][5][2] = $cb; // Bal: Alloy
        if ($vir->getData('workorderbalancesoptionpremium')) $fields['qwo'][6][2] = $cb; // Bal: Premium
        $fields['qwo'][7][2] = $vir->getData('workorderbalancesqty'); // Bal: qty
        $fields['qwo'][8][2] = $vir->getData('workorderbalancesprice'); //'$220'; // Bal: price

        if ($vir->getData('workorderalignoptionstandard')) $fields['qwo'][2][3] = $cb; // Align: Std
        if ($vir->getData('workorderalignoptionthrust')) $fields['qwo'][4][3] = $cb; // Align: Thrust
        if ($vir->getData('workorderalignoption4wheel')) $fields['qwo'][5][3] = $cb; // Align: 4Wheel
        if ($vir->getData('workorderalignoption4wd')) $fields['qwo'][6][3] = $cb; // Align: 4WD
        $fields['qwo'][7][3] = $vir->getData('workorderalignqty'); // '40'; // Align: qty
        $fields['qwo'][8][3] = $vir->getData('workorderalignprice'); //'$200'; // Align: price

        $fields['qwo'][1][4] = $vir->getData('workorderwheelsdescription'); //'Fit wheels, spin, run through roundifier'; // Wheels description workorderwheelsdescription
        $fields['qwo'][7][4] = $vir->getData('workorderwheelsqty'); //'4'; // wheels qty
        $fields['qwo'][8][4] = $vir->getData('workorderwheelsprice'); //'$880'; // wheels price

        $fields['qwo'][3][5] = $vir->getData('workorderbatreportdescription'); //'Charge, spark test, terminal clean, top up'; // Batteries description
        $fields['qwo'][7][5] = $vir->getData('workorderbatreportqty'); //'1'; // Battery qty
        $fields['qwo'][8][5] = $vir->getData('workorderbatreportprice'); //'$70'; // Battery price

        $fields['qwo'][1][6] = $vir->getData('workorderbrakesdescription'); //'Fix broke brake pads, lining'; // brakes description
        $fields['qwo'][7][6] = $vir->getData('workorderbrakesqty'); //'4'; // brakes qty
        $fields['qwo'][8][6] = $vir->getData('workorderbrakesprice'); //'$1200'; // brakes price

        $fields['qwo'][1][7] = $vir->getData('workorderotherdescription'); //'Plate car in gold-platinum alloy'; // other description
        $fields['qwo'][7][7] = $vir->getData('workorderotherqty'); //'1'; // other qty
        $fields['qwo'][8][7] = $vir->getData('workorderotherprice'); //'$1000'; // other price

        $fields['qwo'][10][8] = $vir->getData('workorderpuncturedescription'); //'Remove bullets from tyres and seal holes'; // puncture description
        $fields['qwo'][7][8] = $vir->getData('workorderpunctureqty'); //'4'; // other qty
        $fields['qwo'][8][8] = $vir->getData('workorderpunctureprice'); //'$440'; // other price

        $fields['qwo'][9][9] = $vir->getData('road_hazard_warranty_description');  //'Remove bullets from tyres and seal holes'; // puncture description
        $fields['qwo'][7][9] = $vir->getData('road_hazard_warranty_qty'); //'4'; // other qty
        $fields['qwo'][8][9] = $vir->getData('road_hazard_warranty_price'); //'$440'; // other price

        $fields['qwo'][8][10] = 0; // TOTAL

        // Add the prices together, and align right
        foreach ($fields['qwo'][8] as $rownum=>$rowprice) {
            if ($rownum == 10) {
                $rowprice = $pricetotal; // $4610'; // total
            }

            $pdf->SetXY($cols['qwo'][8], $rows['qwo'][$rownum] + $rowshift);
            $pdf->Cell( 12, 0, Mage::helper('core')->currency($rowprice, true, false), 0, 0, 'R' );
            $pricetotal += $rowprice;
        }
        unset($fields['qwo'][8]); // Don't print the prices again

// ############## Approval checkbox (acb) FIELDS
        $cols['acb'][1] = 16.5;
        $cols['acb'][2] = 125;
        $cols['acb'][3] = 140;
        $cols['acb'][4] = 158.5;
        $cols['acb'][5] = 185;

        $rows['acb'][1] = 271;
        $rows['acb'][2] = 278;
        $rows['acb'][3] = 292.5;
        $rows['acb'][4] = 286.5;
        $rows['acb'][5] = 294;

//$cb = 'X';

        if ($vir->getData('custapprpaycash')) $fields['acb'][2][1] = $cb; // cash
        if ($vir->getData('custapprpayeftpos')) $fields['acb'][3][1] = $cb;  // eftpos
        if ($vir->getData('custapprpayvisamaster')) $fields['acb'][4][1] = $cb; // visa / mcard
        if ($vir->getData('custapprpayamexdiners')) $fields['acb'][5][1] = $cb; // amex / diners

        if ($vir->getData('custapprpayaccount')) $fields['acb'][2][2] = $cb;  // account
        if ($vir->getData('custapprpayfinance')) $fields['acb'][3][2] = $cb; // amex / diners
        if ($vir->getData('custapprpayother')) $fields['acb'][4][2] = $cb; // other

        if ($vir->getData('custapprsubscribe')) $fields['acb'][1][3] = $cb; // service reminders - checkbox is "opt out", hence the if !($vir...)
        if ($vir->getData('custapprcall')) $fields['acb'][2][5] = $cb; // call when vehicle ready

        // $fields['acb'][1][4] = $cb; // collect auto clubs data
// ################ Renderer below here ###################


        foreach ($fields as $section => $colnums) {
            foreach ($colnums as $colnum => $rownums) {
                foreach ($rownums as $rownum => $value) {
                    $pdf->SetXY($cols[$section][$colnum], $rows[$section][$rownum] + $rowshift);
                    $pdf->Write(0, $value);
                }
            }
        }

        $logo_src = Mage::getBaseDir('skin') . "/adminhtml/default/default/vir_pdf/beaurepaires-logo.png";
        $pdf->Image($logo_src, 165, $pdf->GetY()+49, 40); //apply new logo


// ############### PAGE 2 ######################################
// ############### PAGE 2 ######################################
// ############### PAGE 2 ######################################

        unset($cols, $rows, $fields);

        $tppl = $pdf->importPage(2);
        $size = $pdf->getTemplateSize($tppl);
        $pdf->AddPage('P', array($size['w'], $size['h']));
        $pdf->useTemplate($tppl, 0, 0, 225);


################# Top fields

        $cols['top'][1] = 77;
        $cols['top'][2] = 97;
        $cols['top'][3] = 172;
        $cols['top'][4] = 192;

        $rows['top'][1] = 50.5;
        $rows['top'][2] = 58;
        $rows['top'][3] = 65;
        $rows['top'][4] = 79.5;
        $rows['top'][5] = 80;
        $rows['top'][6] = 87;

        $rows['top'][7] = 122.5;
        $rows['top'][8] = 129.5;
        $rows['top'][9] = 136.5;
        $rows['top'][10] = 143.5;
        $rows['top'][11] = 149.5;

        $rows['top'][12] = 179;
        $rows['top'][13] = 186;
        $rows['top'][14] = 193;
        $rows['top'][15] = 200;
        $rows['top'][16] = 207;
        $rows['top'][17] = 212.5;

        $rows['top'][18] = 234;
        $rows['top'][19] = 240;

        $rows['top'][20] = 262.5;
        $rows['top'][21] = 269.5;
        $rows['top'][22] = 276.5;
        $rows['top'][23] = 283.5;
        $rows['top'][24] = 290.5;
        $rows['top'][25] = 297.5;
        $rows['top'][26] = 304.5;

        $fields['top'][1][1] = $vir->getData('wheelalignfrontbeforeleftcamber'); // '1btml'; // Before top camber left
        $fields['top'][2][1] = $vir->getData('wheelalignfrontbeforerightcamber'); // '2btmr'; // Before top camber right
        $fields['top'][3][1] = $vir->getData('wheelalignfrontafterleftcamber'); // '3atml'; // After top camber left
        $fields['top'][4][1] = $vir->getData('wheelalignfrontafterrightcamber'); // '4atmr'; // After top camber right

        $fields['top'][1][2] = $vir->getData('wheelalignfrontbeforeleftcaster'); // '1btsl'; // Before top caster left
        $fields['top'][2][2] = $vir->getData('wheelalignfrontbeforerightcaster'); // '2btsr'; // Before top caster right
        $fields['top'][3][2] = $vir->getData('wheelalignfrontafterleftcaster'); // '3atsl'; // After top caster left
        $fields['top'][4][2] = $vir->getData('wheelalignfrontafterrightcaster'); // '4atsr'; // After top caster right

        $fields['top'][1][3] = $vir->getData('wheelalignfrontbeforelefttoein'); // '1bttl'; // Before top toe left
        $fields['top'][2][3] = $vir->getData('wheelalignfrontbeforerighttoein'); // '2bttr'; // Before top toe right
        $fields['top'][3][3] = $vir->getData('wheelalignfrontafterlefttoein'); // '3attl'; // After top toe left
        $fields['top'][4][3] = $vir->getData('wheelalignfrontafterrighttoein'); // '4attr'; // After top toe right

        $fields['top'][1][4] = $vir->getData('wheelalignfrontbeforeleftcamber2'); // '1btml'; // Before top camber left 2
        $fields['top'][2][4] = $vir->getData('wheelalignfrontbeforerightcamber2'); // '2btmr'; // Before top camber right 2
        $fields['top'][3][4] = $vir->getData('wheelalignfrontafterleftcamber2'); // '3atml'; // After top camber left 2
        $fields['top'][4][4] = $vir->getData('wheelalignfrontafterrightcamber2'); // '4atmr'; // After top camber right 2

        $fields['top'][1][5] = $vir->getData('wheelalignfrontbeforeleftcaster2'); // '1btsl'; // Before top caster left 2
        $fields['top'][2][5] = $vir->getData('wheelalignfrontbeforerightcaster2'); // '2btsr'; // Before top caster right 2
        $fields['top'][3][5] = $vir->getData('wheelalignfrontafterleftcaster2'); // '3atsl'; // After top caster left 2
        $fields['top'][4][5] = $vir->getData('wheelalignfrontafterrightcaster2'); // '4atsr'; // After top caster right 2

        $fields['top'][1][6] = $vir->getData('wheelalignfrontbeforelefttoein2'); // '1bttl'; // Before top toe left 2
        $fields['top'][2][6] = $vir->getData('wheelalignfrontbeforerighttoein2'); // '2bttr'; // Before top toe right 2
        $fields['top'][3][6] = $vir->getData('wheelalignfrontafterlefttoein2'); // '3attl'; // After top toe left 2
        $fields['top'][4][6] = $vir->getData('wheelalignfrontafterrighttoein2'); // '4attr'; // After top toe right 2

        $fields['top'][1][7] = $this->_hideOverflow($vir->getData('wheelalignchecksteerwheelstraightvalue'), 70); //'Steering Wheel is straighter than Rev. Fred Nile';
        if ($vir->getData('wheelalignchecksteerwheelstraight')) $fields['top'][4][7] = $cb;

        $fields['top'][1][8] = $this->_hideOverflow($vir->getData('wheelaligncheckwehicleroadtestedvalue'), 70); //'Vehicle was Road Tested. At 200km/h on Calder Freeway.';
        if ($vir->getData('wheelaligncheckwehicleroadtested')) $fields['top'][4][8] = $cb;

        $fields['top'][1][9] = $this->_hideOverflow($vir->getData('wheelaligncheckcovermatremovedvalue'), 70); //'Covers & Mats changed to pink to match fluffy dice.';
        if ($vir->getData('wheelaligncheckcovermatremoved')) $fields['top'][4][9] = $cb;

        $fields['top'][1][10] = $this->_hideOverflow($vir->getData('wheelaligncheckstickerinstalledvalue'), 70); //'Sticker attached with superglue. It\'s never coming off!';
        if ($vir->getData('wheelaligncheckstickerinstalled')) $fields['top'][4][10] = $cb;

        $fields['top'][1][11] = $this->_hideOverflow($vir->getData('wheelaligncheckcompletedby'), 70); //'Kevin Bacon III (Jnr)';


        $fields['top'][1][12] = $this->_hideOverflow($vir->getData('tyrecheckwheelnutstorquedvalue'), 70); //'Torque is cheap, when it comes to wheel nuts.';
        if ($vir->getData('tyrecheckwheelnutstorqued')) $fields['top'][4][12] = $cb;

        $fields['top'][1][13] = $this->_hideOverflow($vir->getData('tyrecheckmetalvalvecapsfittedvalue'), 70); //'Grind-core shred-metal valve caps. Rooooock.';
        if ($vir->getData('tyrecheckmetalvalvecapsfitted')) $fields['top'][4][13] = $cb;

        $fields['top'][1][14] = $this->_hideOverflow($vir->getData('tyrechecktyresglosssedvalue'), 70); //'Glossed over the tyres, like the rest of the car.';
        if ($vir->getData('tyrechecktyresglosssed')) $fields['top'][4][14] = $cb;

        $fields['top'][1][15] = $this->_hideOverflow($vir->getData('tyrecheckbatterystickerfittedvalue'), 70); //'Battery sticker stuck. Kept electricuting myself.';
        if ($vir->getData('tyrecheckbatterystickerfitted')) $fields['top'][4][15] = $cb;

        $fields['top'][1][16] = $this->_hideOverflow($vir->getData('tyrechecksparetyrestickerfittedvalue'), 70); //'Spare tyre sticker stuck to inside of tyre.';
        if ($vir->getData('tyrechecksparetyrestickerfitted')) $fields['top'][4][16] = $cb;

        $fields['top'][1][17] = $this->_hideOverflow($vir->getData('tyrecheckcompletedby'), 70); //'Captain James Snooze VI (Snr)';

        $fields['top'][1][18] = $this->_hideOverflow($vir->getData('batterychecktestresult'), 80); //'Battery tested and working. Put tongue on terminals. It\'s got quite a good kick.';
        $fields['top'][1][19] = $this->_hideOverflow($vir->getData('batterycheckterminals'), 80); //'Terminals were polished to a sparkling finish. They were shocking before.';

        $fields['top'][2][20] = $this->_hideOverflow($vir->getData('visualbrakefrontcallipers'), 70); //'Front callipers / cyls are stopping the car. Is that normal?';
        $fields['top'][2][21] = $this->_hideOverflow($vir->getData('visualbrakefrontbrakes'), 70); //'90% worn. Put some gaffa on though, she\'ll be right.';
        $fields['top'][2][22] = $this->_hideOverflow($vir->getData('visualbrakefrontdiscs'), 70); //'Discs ok. Removed tasteless 80s gated snare sound from drums.';
        $fields['top'][2][23] = $this->_hideOverflow($vir->getData('visualbrakerearcallipers'), 70); //'Rear callipers / cyls are stopping the car. Is that normal?';
        $fields['top'][2][24] = $this->_hideOverflow($vir->getData('visualbrakerearbrakes'), 70); //'Rear brakes 0% worn. Replaced at full cost.';
        $fields['top'][2][25] = $this->_hideOverflow($vir->getData('visualbrakereardiscs'), 70); //'Sorted out rear discs / drums, using standard prison technique';
        $fields['top'][2][26] = $this->_hideOverflow($vir->getData('visualbrakerearflexiblehoses'), 70); //'My flexible hydraulic brake hose is bigger than yours.';


// ################ Renderer below here ###################


        foreach ($fields as $section => $colnums) {
            foreach ($colnums as $colnum => $rownums) {
                foreach ($rownums as $rownum => $value) {
                    $pdf->SetXY($cols[$section][$colnum], $rows[$section][$rownum] + $rowshift);
                    $pdf->Write(0, $value);
                }
            }
        }


        $storeName = Mage::helper('apdinteract_vir')->getStoreName($vir->getData('parent_id'));
        $datetime  = Mage::getModel('core/date')->date('Y-m-d_H-i');
        $customerName = $vir->getCustname();
        $pdfTitle = $storeName . '_consumer_vir_' . $datetime . '_' . $customerName . '.pdf';

// ########################################
// The magic line:
        $pdf->Output($pdfTitle, "I");
// D = download, I = inline to browser, F = file, S = string (for save in DB, I guess ?!)
// I = testing, D = production.
    }
}