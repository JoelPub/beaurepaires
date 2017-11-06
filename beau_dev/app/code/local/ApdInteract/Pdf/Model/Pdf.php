<?php

// include autoloader
require_once Mage::getBaseDir('lib') .  '/dompdf/autoload.inc.php';;
use Dompdf\Dompdf;

class ApdInteract_Pdf_Model_Pdf extends Mage_Core_Model_Abstract
{

    protected $_pdfTitle = '';

    /**
     * @var string
     */
    protected $_paperSize = 'A4';

    /**
     * @var string
     */
    protected $_paperOrientation = 'portrait';

    /**
     * @var
     */
    protected $_domPdf;

    /**
     * ApdInteract_Pdf_Model_Pdf constructor.
     */
    public function __construct() {
        parent::__construct ();

        $this->_domPdf = new Dompdf;
        $this->_domPdf->setPaper($this->_paperSize, $this->_paperOrientation);
    }

    /**
     * @param null $title
     * @return $this
     */
    public function setTitle($title = null){

        if(!empty($title)){
            $this->_pdfTitle = $title;
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function _getTitle(){
        return $this->_pdfTitle;
    }


    /**
     *  convert HTML to PDF
     */
    public function sendToPdf($html,$options)
    {
        $this->_domPdf->loadHtml($html);
        $this->_domPdf->render();
        $this->_domPdf->stream($this->_getTitle(),$options);
    }
}
