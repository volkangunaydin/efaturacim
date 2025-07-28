<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;

class EFaturaBelgesi extends EBelge{
    /**
     * Summary of ubl
     * @var InvoiceDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        parent::__construct("efatura");                
    }
    public function getFaturaNo(){
        return $this->ubl->getId();
    }
}
?>