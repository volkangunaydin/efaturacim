<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class DeliveryTerms extends UblDataType
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
    public function setPropertyFromOptions($k, $v, $options){        
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $schemeID = $options['schemeID'] ?? null;
            $this->id = new ID();
            $this->id->textContent = $v;
            if (StrUtil::notEmpty($schemeID)) {
                $this->id->attributes['schemeID'] = $schemeID;
            }
            return true;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $this->createElement($document,'cac:DeliveryTerms');        
        $element->appendChild($this->id->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        if(is_null($this->id) || $this->id->isEmpty()){
            return true;
        }
        return false;        
    }
    public function getValue(){
        return $this->id->getValue();
    }
}