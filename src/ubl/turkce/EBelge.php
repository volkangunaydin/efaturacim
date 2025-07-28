<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;

class EBelge{
    /**
     * Summary of ubl
     * @var InvoiceDocument
     */
    public $ubl = null;
    public function __construct($type=null){
        if(in_array("".$type,array("irsaliye","eirsaliye"))){

        }else{
            $this->ubl = new InvoiceDocument();            
        }
    }
    public function getBelgeNo(){
        return $this->ubl->getId();
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
}
?>