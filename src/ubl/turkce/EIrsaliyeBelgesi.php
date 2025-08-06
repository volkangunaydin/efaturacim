<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\DespatchAdviceDocument;

class EIrsaliyeBelgesi extends EBelge{
    /**
     * Summary of ubl
     * @var DespatchAdviceDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        parent::__construct("despatchAdvice");                
    }
    public function getFaturaNo(){
        return $this->ubl->getId();
    }
}
?>