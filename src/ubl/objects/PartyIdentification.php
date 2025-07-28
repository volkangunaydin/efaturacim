<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class PartyIdentification extends UblDataType
{
    public ?ID $id = null;    
    public function initMe(){
        $this->id = new ID();
    }

    public function setValue($value,$schemeID=null){
        $this->id->textContent = $value;        
        if(StrUtil::notEmpty($schemeID)){
            $this->id->attributes['schemeID'] = $schemeID;
        }        
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $this->createElement($document,'cac:PartyIdentification');        
        $element->appendChild($this->id->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        if(is_null($this->id) || $this->id->isEmpty()){
            return true;
        }
        return false;        
    }
}