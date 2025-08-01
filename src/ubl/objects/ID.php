<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class ID extends UblDataType{
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }    
    public function toDOMElement(DOMDocument $document): ?DOMElement{
        if ($this->isEmpty()) {
            return null;
        }
        // cbc:Note is a simple element with just a text value.
        return $this->createElement($document,'cbc:ID');
    }
    public static function newNote($str){
        return new Note(array("value"=>$str));
    }
}