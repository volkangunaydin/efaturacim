<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class RoadTransport extends UblDataType
{
    public ?LicensePlateID $licensePlateID = null;    
    public function initMe(){
        $this->licensePlateID = new LicensePlateID();
    }

    public function setValue($value,$schemeID=null){
        $this->licensePlateID->textContent = $value;        
        if(StrUtil::notEmpty($schemeID)){
            $this->licensePlateID->attributes['schemeID'] = $schemeID;
        }        
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){        
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->licensePlateID->textContent = $v;
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->licensePlateID->attributes['schemeID'] = $v;
            return true;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $this->createElement($document,'cac:RoadTransport');        
        $element->appendChild($this->licensePlateID->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        if(is_null($this->licensePlateID) || $this->licensePlateID->isEmpty()){
            return true;
        }
        return false;        
    }
    public function getValue(){
        return $this->licensePlateID->getValue();
    }
}