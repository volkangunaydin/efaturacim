<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class TransportHandlingUnit extends UblDataType
{
    public ?UblDataTypeList $actualPackage;
    public ?TransportEquipment $transportEquipment = null;

    public function initMe()
    {
        $this->actualPackage = new UblDataTypeList(ActualPackage::class);
        $this->transportEquipment = new TransportEquipment();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if ($this->transportEquipment->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        $this->actualPackage = new UblDataTypeList(ActualPackage::class);
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->actualPackage) && is_null($this->transportEquipment) || $this->transportEquipment->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        $element = $document->createElement('cac:TransportHandlingUnit');
        $this->appendChild($element, $this->actualPackage->toDOMElement($document));
        $this->appendChild($element, $this->transportEquipment->toDOMElement($document));

        return $element;
    }
}