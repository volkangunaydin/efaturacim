<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;
use PDO;

class RoadTransport extends UblDataType
{
    public ?LicensePlateID $licensePlateID = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe(){
        $this->licensePlateID = new LicensePlateID();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['licensePlateID', 'plaka_id']) && StrUtil::notEmpty($v)) {
            $this->licensePlateID->setValue($v);
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return StrUtil::isEmpty($this->licensePlateID);        
    }
    public function toDOMElement(DOMDocument $document){
        $element = $document->createElement('cac:RoadTransport');
        $this->appendChild($element, $this->licensePlateID->toDOMElement($document));
        return $element;
    }
}