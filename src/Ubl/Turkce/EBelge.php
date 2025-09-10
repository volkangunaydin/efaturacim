<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\Ubl\CreditNoteDocument;
use Efaturacim\Util\Ubl\DespatchAdviceDocument;
use Efaturacim\Util\Ubl\InvoiceDocument;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\IO\IO_Util;
use Efaturacim\Util\Utils\PreviewUtil;

class EBelge{
    /**
     * Summary of ubl
     * @var InvoiceDocument|DespatchAdviceDocument|CreditNoteDocument|null
     */
    public $ubl = null;
    public function __construct($type=null){
        if(in_array("".$type,array("irsaliye","eirsaliye","despatchAdvice"))){
            $this->ubl = new DespatchAdviceDocument();
        }else if(in_array("".$type,array("fatura","einvoice","invoice"))){
            $this->ubl = new InvoiceDocument();            
        }else if(in_array("".$type,array("mustahsil","emustahsil","creditNote"))){
            $this->ubl = new CreditNoteDocument();
        }
    }
    public static function fromXmlFile($filePath=null){
        return self::fromXmlContent(IO_Util::readFileAsString($filePath));
    }
    public static function fromXmlContent($xmlString=null){
        if (is_string($xmlString) && !empty($xmlString)) {
            if (preg_match('/<([a-zA-Z0-9_:]+)/', $xmlString, $matches)) {
                $rootTagName = $matches[1];
                // Remove namespace prefix if present to get the local name.
                if (strpos($rootTagName, ':') !== false) {
                    $parts = explode(':', $rootTagName);
                    $rootTagName = end($parts);
                }
                $belge = null;
                switch ($rootTagName) {
                    case 'Invoice':
                        $belge = new EFaturaBelgesi();
                        break;
                    case 'DespatchAdvice':
                        $belge = new EIrsaliyeBelgesi();
                        break;
                    case 'CreditNote':
                        $belge = new EMustahsilBelgesi();
                        break;
                }
                if ($belge) {
                    $belge->ubl->loadFromXml($xmlString);
                    return $belge;
                }
            }            
        }
        return null;
    }
    public function getBelgeNo(){
        return $this->ubl->getId();
    }
    public function getCopyIndicator(){
        return $this->ubl->getCopyIndicator();
    }
    public function getBelgeGuid(){
       return  $this->ubl->getGUID();
    }
    public function getBelgeTarihi(){
        return DateUtil::newDate($this->ubl->getIssueDate()." ".$this->ubl->getIssueTime());
    }
    public function setSaticiBilgileri($options=null,$clear=false){
        $this->ubl->despatchSupplierParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function setAliciBilgileri($options=null,$clear=false){
        $this->ubl->deliveryCustomerParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function &getSatici(){
        return $this->ubl->accountingSupplierParty;
    }
    public function &getAlici(){
        return $this->ubl->accountingCustomerParty;
    }
    public function ekleSiparis($sipKodu=null,$tarih=null){
        if(is_null($tarih)){ $tarih = date('Y-m-d'); }
        $this->ubl->addToOrderList($sipKodu,$tarih);
        return $this;
    }
    public function ekleIrsaliye($irsKodu=null,$tarih=null){
        if(is_null($tarih)){ $tarih = date('Y-m-d'); }        
        $this->ubl->addToDespatchList($irsKodu,$tarih);
        return $this;
    }
    public function ekleNot($noteStr){
        $this->ubl->addNote($noteStr);
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
    public function showAsArray($showOutput=true){
        $arr = $this->ubl->toArrayOrObject();        
        return PreviewUtil::previewPhpVar($arr,showOutput: $showOutput);        
    }
    public static function fromJson($jsonString){                
        return self::smart($jsonString,"json");        
    }    
    public static function smart($strOrObject,$type=null,$debug=false){        
        $a = new static();
        $a->ubl->loadSmart($strOrObject,$type,$debug);
        return $a;        
    }    
    public function ekleSatir($name,$quantity=1,$price=0,$kdv=20){        
        return $this->ekleSatirFromArray(array("name" => $name,"quantity" => $quantity,"price" => $price,"kdv" => $kdv));
    }   
    public function ekleSatirFromArray($arrProps){        
        $this->ubl->addLineFromArray($arrProps);
        return $this;
    }
    public function rebuildValues(){
        $this->ubl->rebuildValues();
        return $this;
    }
    public function toXmlString(){
        return $this->ubl->toXml();        
    }
    public function debug(){
        EBelgeDebugger::debug($this);
    }
    public function getSatirSayisi(){
        if($this->ubl && property_exists($this->ubl, 'invoiceLine')){
            return $this->ubl->invoiceLine->getCount();        
        }        
        return 0;
    }
}
?>