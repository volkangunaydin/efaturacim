<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\Ubl\CreditNoteDocument;
use Efaturacim\Util\Ubl\DespatchAdviceDocument;
use Efaturacim\Util\Ubl\InvoiceDocument;
use Efaturacim\Util\Ubl\Preview\UblPreview;
use Efaturacim\Util\Ubl\Preview\XsltUtil;
use Efaturacim\Util\Ubl\UblDocument;
use Efaturacim\Util\Utils\Cache\MemoryCache;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\IO\IO_Util;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\Pdf\PdfUtil;
use Efaturacim\Util\Utils\PreviewUtil;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;

class EBelge{
    /**
     * Summary of ubl
     * @var InvoiceDocument|DespatchAdviceDocument|CreditNoteDocument|UblDocument
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
    public function isOK(){
        if(!is_null($this->ubl) && $this->ubl instanceof UblDocument){
            return true;
        }
        return false;
    }
    public function isFatura(){
        if($this->ubl instanceof InvoiceDocument){
            return true;
        }
        return false;
    }
    public function isMustahsil(){
        if($this->ubl instanceof CreditNoteDocument){
            return true;
        }
        return false;
    }
    public function isEIrsaliye(){
        return $this->isIrsaliye();
    }
    public function isIrsaliye(){
        if($this->ubl instanceof DespatchAdviceDocument){
            return true;
        }
        return false;
    }
    public function isEFatura(){
        if($this->ubl instanceof InvoiceDocument){
            $profile = $this->ubl->getProfileId();            
            return !in_array("".$profile,array("EARSIVFATURA","earsivfatura"));
        }
        return false;
    }
    public function isEArsivFatura(){
        if($this->ubl instanceof InvoiceDocument){
            $profile = $this->ubl->getProfileId();                  
            return in_array("".$profile,array("EARSIVFATURA","earsivfatura"));
        }
        return false;
    }
    public function isEMustahsil(){
        if($this->ubl instanceof CreditNoteDocument){
            return true;
        }
        return false;
    }
    /**
     * @return EBelge
     */
    public static function fromXmlFile($filePath=null,$readOnly=false,$forceToCreate=false,$options=null){
        return self::fromXmlContent(IO_Util::readFileAsString($filePath),$readOnly,$forceToCreate,$options);
    }
    /**
     * @return EBelge
     */
    public static function fromXmlContent($xmlString=null,$readOnly=false,$forceToCreate=false,$options=null){
        if (is_string($xmlString) && !empty($xmlString) && Options::ensureParam($options) && $options instanceof Options) {
            $debug = $options->getAs(array("debug_document","debug_this_array","debug"),false,CastUtil::$DATA_BOOL);
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
                    $belge->ubl->setOptions($options);
                    $belge->ubl->loadFromXml($xmlString,$debug);
                    if($readOnly){
                        $belge->ubl->orgXmlString = $xmlString;
                    }
                    return $belge;
                }
            }            
        }
        if($forceToCreate){
            $belge = new EBelge();
            $belge->ubl = new InvoiceDocument();
            $belge->ubl->loadFromXml($xmlString,$debug);
            return $belge;
        }
        return null;
    }
    public function getBelgeNo(){
        return $this->ubl->getId();
    }
    public function getBelgeTip(){
        if($this->ubl){
            return $this->ubl->getProfileId();
        }
        return null;
    }
    public function getGonderenVkn(){
        $gonderen = $this->getSatici();        
        if($gonderen){
            return $gonderen->getVknOrTckn();
        }
        return null;
    }
    public function getGonderenUnvan(){
        $gonderen = $this->getSatici();
        if($gonderen){
            return $gonderen->getName();
        }
        return null;
    }    
    public function getAliciVkn(){
        $alici = $this->getAlici();
        if($alici){
            return $alici->getVknOrTckn();
        }
        return null;
    }
    public function getAliciUnvan(){
        $alici = $this->getAlici();
        if($alici){
            return $alici->getName();
        }
        return null;
    }      
    public function getCopyIndicator(){
        return $this->ubl->getCopyIndicator();
    }
    public function getBelgeGuid(){
       return  $this->ubl->getGUID();
    }
    public function getBelgeTarihi($asDbDateTime=true){
        if($asDbDateTime){
            return DateUtil::newDate($this->ubl->getIssueDate()." ".$this->ubl->getIssueTime());
        }else{
            return DateUtil::newDate($this->ubl->getIssueDate()." ".$this->ubl->getIssueTime())->toDbDateTime();
        }        
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
        $null = null;        
        if($this->ubl instanceof InvoiceDocument){            
            return $this->ubl->accountingSupplierParty; 
        }else if($this->ubl instanceof CreditNoteDocument){
            return $this->ubl->accountingSupplierParty; 
        }else if($this->ubl instanceof DespatchAdviceDocument){
            return $this->ubl->despatchSupplierParty; 
        }            
        return $null;
    }
    public function &getAlici(){
        $null = null;
        if($this->ubl instanceof InvoiceDocument){
            return $this->ubl->accountingCustomerParty;
        }else if($this->ubl instanceof DespatchAdviceDocument){
            return $this->ubl->deliveryCustomerParty;
        }else if($this->ubl instanceof CreditNoteDocument){
            return $this->ubl->accountingCustomerParty;
            //|| $this->ubl instanceof DespatchAdviceDocument || $this->ubl instanceof CreditNoteDocument || $this->ubl instanceof DespatchAdviceDocument                        
        }
        return $null;
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
        if($this->isFatura()){
            return $this->ubl->invoiceLine->getCount();        
        }else if($this->isMustahsil()){
            return $this->ubl->creditNoteLine->getCount();
        }else if($this->isIrsaliye()){
            return $this->ubl->despatchLine->getCount();            
        }        
        return 0;
    }
    public function getHtmlResult($useCache=true){
        $r = new SimpleResult();
        if(!is_null($this->ubl) ){
            $xmlString = $this->ubl->orgXmlString;
            if(StrUtil::isEmpty($xmlString) && !is_null($this->ubl)){
                $xmlString = $this->ubl->toXml();
            }
            if(StrUtil::notEmpty($xmlString) ){
                $xsltString = $this->ubl->getXsltStringOrDefaultXslt();
                if(StrUtil::notEmpty($xsltString)){                                        
                    if($useCache){
                        $cacheKey = MemoryCache::getKey($xmlString.$xsltString);                        
                        if(MemoryCache::hasKey($cacheKey)){
                            $r->setIsOk(true);
                            $r->value = MemoryCache::get($cacheKey);
                            return $r;
                        }
                    }
                    $r->setIsOk(true);
                    $r->value = XsltUtil::getHtmlFromXml($xmlString,$xsltString,array());                    
                    if($useCache && StrUtil::notEmpty($r->value)){
                        MemoryCache::set($cacheKey,$r->value);
                    }
                }                
            }
        }        
        return $r;
    }
    public function getHtmlString(){
        return $this->getHtmlResult()->value;        
    }
    public function getPdfResult($useCache=true){    
        $r = new SimpleResult();        
        if(!is_null($this->ubl) ){
            $html = $this->getHtmlString($useCache);
            if(StrUtil::notEmpty($html)){
                if($useCache){
                    $cacheKey = MemoryCache::getKey($html);                        
                    if(MemoryCache::hasKey($cacheKey)){
                        $r->setIsOk(true);
                        $r->value = MemoryCache::get($cacheKey);
                        return $r;
                    }
                }
                $resPdf = PdfUtil::getPdfFromHtml($html,array("template"=>"ubl"));                                
                if($resPdf->isOK()){
                    $r->setIsOk(true);
                    $r->value = $resPdf->value;
                    if($useCache){
                        MemoryCache::set($cacheKey,$r->value);
                    }    
                }else{
                    return $resPdf;
                }
            }
        }
        return $r;
    }
    public function getPdfString(){
        return $this->getPdfResult()->value;
    }
    public function getKontrolResult(){        
        return EBelgeKontolUtil::getKontrolResult($this->ubl);   
    }
}
?>