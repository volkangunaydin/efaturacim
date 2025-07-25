<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\StrUtil;

class InvoicedQuantity extends UblDataType{
    public function initMe(){
        $this->attributes["unitCode"] = "C62"; // Default to "unit"]
    }
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function setCode($code=null){
        $this->attributes["unitCode"] = $code; // Default to "unit"]
    }
    public function setQuantity($number,$decimal=4){
        $this->textContent = NumberUtil::asCleanNumber($number,$decimal);
    }
    public function toNumber(){
        return NumberUtil::asNumber($this->textContent,0);
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement{
        if ($this->isEmpty()) {
            return null;
        }        
        return $this->createElement($document,'cbc:InvoicedQuantity');
    }
}