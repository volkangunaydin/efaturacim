<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class PartyName extends UblDataType
{
    public ?Name $name = null;    
    public function initMe(){
        $this->name = new Name();
    }

    public function setPropertyFromOptions($k,$v,$options){     
        if($k=="name"){
            $this->name->textContent = $v;            
        }   
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $this->createElement($document,'cac:PartyName');        
        $element->appendChild($this->name->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        if(is_null($this->name) || $this->name->isEmpty()){
            return true;
        }
        return false;        
    }
}