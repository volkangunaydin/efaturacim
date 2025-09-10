<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;


class LicensePlateID extends UblDataType{
    public function initMe(): void{
        $this->attributes["schemeID"] = "PLAKA"; 
        $this->defaultTagName = "cbc:LicensePlateID";
    }
    
    public function setPropertyFromOptions($k, $v, $options){
        if (in_array($k, ['licensePlateID', 'plaka_id', 'value']) && StrUtil::notEmpty($v)) {
            $this->setValue($v);
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->attributes["schemeID"] = $v;
            return true;
        }
        return false;
    }

    public function setValue($value){
        $this->textContent = $value;
    }
    
    public function toDOMElement(DOMDocument $document){
        if ($this->isEmpty()) {
            return null;
        }
        return $this->createElement($document, $this->defaultTagName);
    }
} 