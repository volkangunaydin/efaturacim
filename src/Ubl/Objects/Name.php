<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Name extends UblDataType{
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function isEmpty(){
        return StrUtil::isEmpty($this->textContent);
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement{
        if ($this->isEmpty()) {
            return null;
        }
        // cbc:Name is a simple element with just a text value.
        return $this->createElement($document,'cbc:Name');
    }
    public static function newName($str){
        return new Name(array("value"=>$str));
    }
}