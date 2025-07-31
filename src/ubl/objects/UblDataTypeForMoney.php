<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Vulcan\Base\Util\MathUtil\NumberUtil;


class UblDataTypeForMoney extends UblDataType{
    public function initMe(): void{
        $this->attributes["currencyID"] = "TRY"; 
    }
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function setCurrencyID($code=null){
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