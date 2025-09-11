<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;
use PDO;

class TransportEquipment extends UblDataType
{
    public ?ID $id = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe(){
        $this->id = new ID();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->id->setValue($v);
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->id->attributes["schemeID"] = $v;
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return StrUtil::isEmpty($this->id);        
    }
    public function toDOMElement(DOMDocument $document){
        $element = $document->createElement('cac:TransportEquipment');
        $this->appendChild($element, $this->id->toDOMElement($document));
        return $element;
    }
}