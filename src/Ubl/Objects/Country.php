<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Country extends UblDataType{
    public $identificationCode = null; // e.g., "TR"
    public $name = null;               // e.g., "Türkiye"

    public function __construct( $name = null,$identificationCode = null)
    {
        if(StrUtil::notEmpty($identificationCode)){
            $this->identificationCode = $identificationCode;
            $this->name = $name;
        }        
    }    
    public function isEmpty(): bool{
        return StrUtil::isEmpty($this->identificationCode) && StrUtil::isEmpty($this->name);
    }
    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("ulke_kodu","code","id")) && StrUtil::notEmpty($v)){
            $this->identificationCode = $v;
        }else if(in_array($k,array("ulke_adi","ad","ulke")) && StrUtil::notEmpty($v)){
            $this->name = $v;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement{
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:Country');
        if ($this->identificationCode !== null) {
            $this->appendElement($document, $element, 'cbc:IdentificationCode', $this->identificationCode);
        }
        if ($this->name !== null) {
            $this->appendElement($document, $element, 'cbc:Name', $this->name);
        }
        return $element;
    }
    public function getIdentificationCode(){
        return $this->identificationCode;
    }
    public function getName(){
        return $this->name;
    }
}