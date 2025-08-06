<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class UblDataTypeForMoney extends UblDataType{
    public function initMe(): void{
        $this->attributes["currencyID"] = "TRY"; 
    }
    public function isEmpty(){        
        return StrUtil::isEmpty($this->textContent);
    }    
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function setCurrencyID($code=null,$updateDeeper=false){
        $this->attributes["currencyID"] = $code; 
    }
    public function setValue($number,$decimal=2){
        $this->textContent = NumberUtil::asCleanNumber($number,$decimal);
    }
    public function setTextContent($str){
        //if($str==0){ \Vulcan\V::dump($str); }
        $this->setValue($str);        
    }
    public function toNumber(){
        return NumberUtil::asNumber($this->textContent,0);
    }
 
    public function toDOMElement(DOMDocument $document): ?DOMElement{                
        if ($this->isEmpty()) {
            return null;
        }        
        return $this->createElement($document,$this->defaultTagName);
    }    
}