<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Country extends UblDataType{
    public $identificationCode = "TR"; // e.g., "TR"
    public $name = "TURKIYE";               // e.g., "Türkiye"

    public function __construct( $name = null,$identificationCode = null)
    {
        if(StrUtil::notEmpty($identificationCode)){
            $this->identificationCode = $identificationCode;
            $this->name = $name;
        }        
    }
    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("ulke_kodu","code","id")) && StrUtil::notEmpty($v)){
            $this->identificationCode = $v;
        }else if(in_array($k,array("ulke_adi","ad","ulke")) && StrUtil::notEmpty($v)){
            $this->name = $v;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document): DOMElement{
        $element = $document->createElement('cac:Country');
        if ($this->identificationCode !== null) {
            $this->appendElement($document, $element, 'cbc:IdentificationCode', $this->identificationCode);
        }
        if ($this->name !== null) {
            $this->appendElement($document, $element, 'cbc:Name', $this->name);
        }
        return $element;
    }
}