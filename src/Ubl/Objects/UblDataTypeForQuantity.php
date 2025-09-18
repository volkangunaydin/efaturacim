<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class UblDataTypeForQuantity extends UblDataType{
    public function initMe(){
        // Don't set unitCode by default - only when explicitly provided
        // $this->attributes["unitCode"] = "C62"; // Default to "unit"]
    }
    public function isEmpty(){        
        return StrUtil::isEmpty($this->textContent);
    }    
    public function setCode($code=null){
        if ($code !== null && $code !== '') {
            $this->attributes["unitCode"] = $code;
        } else {
            // Remove unitCode attribute if it exists
            unset($this->attributes["unitCode"]);
        }
    }    
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function setQuantity($number,$decimal=4){
        $this->textContent = NumberUtil::asCleanNumber($number,$decimal);
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