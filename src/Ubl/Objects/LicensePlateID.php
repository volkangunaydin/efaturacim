<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;


class LicensePlateID extends UblDataType{
    public function isEmpty(){
        return !StrUtil::notEmpty($this->textContent);
    }
    public function setPropertyFromOptions($k,$v,$options){
        if (in_array($k, ['id', 'ID', 'value']) && StrUtil::notEmpty($v)) {
            $this->setValue($v);
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->attributes["schemeID"] = $v;
            return true;
        }
        return false;
    }    
    public function toDOMElement(DOMDocument $document): ?DOMElement{
        if ($this->isEmpty()) {
            return null;
        }
        // cbc:Note is a simple element with just a text value.
        return $this->createElement($document,'cbc:LicensePlateID');
    }
    public static function newNote($str){
        return new LicensePlateID(array("value"=>$str));
    }
    public function setValue($value){
        $this->textContent = $value;
    }
}