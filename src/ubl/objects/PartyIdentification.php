<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class PartyIdentification extends UblDataType
{
    public ?string $id = null;
    public ?string $schemeID = null; // e.g., "VKN" or "TCKN"

    public function __construct(?string $id = null, ?string $schemeID = null)
    {
        $this->id = $id;
        $this->schemeID = $schemeID;
    }
    public function setValue($value,$schemeID=null){
        $this->id = $value;
        $this->schemeID = $schemeID;
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){
        return false;
    }
    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:PartyIdentification');
        $this->appendElement($document, $element, 'cbc:ID', $this->id, ['schemeID' => $this->schemeID]);
        return $element;
    }
    public function isEmpty(){
        if(StrUtil::isEmpty($this->id) && StrUtil::isEmpty($this->schemeID)){
            return true;
        }
        return false;        
    }
}