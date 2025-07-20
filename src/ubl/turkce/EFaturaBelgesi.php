<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;

class EFaturaBelgesi{
    /**
     * Summary of ubl
     * @var InvoiceDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        $this->ubl = new InvoiceDocument();
        if(!is_null($faturaNo)){ $this->ubl->setId($faturaNo); }
        $this->ubl->setUuid();
        
    }
    public function setSaticiBilgileri($options=null,$clear=false){
        $this->ubl->accountingSupplierParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function setAliciBilgileri($options=null,$clear=false){
        $this->ubl->accountingCustomerParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function &getSatici(){
        return $this->ubl->accountingSupplierParty;
    }
    public function &getAlici(){
        return $this->ubl->accountingCustomerParty;
    }
    public function ekleSiparis($sipKodu=null,$tarih=null){
        return $this;
    }
    public function ekleIrsaliye($irsKodu=null,$tarih=null){
        return $this;
    }
    
    public function showAsXml($showOutput=true){
        $xmlString = $this->ubl->toXml();        
        return PreviewUtil::previewXml($xmlString,showOutput: $showOutput);        
    }
    public function showAsJson($showOutput=true){
        $xmlString = $this->ubl->toJson();        
        return PreviewUtil::previewJson($xmlString,showOutput: $showOutput);        
    }    
}
?>