<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\CreditNoteDocument;

class EMustahsilBelgesi extends EBelge{
    /**
     * Summary of ubl
     * @var CreditNoteDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        parent::__construct("creditNote");                
    }
    public function getFaturaNo(){
        return $this->ubl->getId();
    }
}
?>