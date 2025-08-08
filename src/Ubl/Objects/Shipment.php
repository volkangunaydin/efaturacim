<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Shipment extends UblDataType
{
    public ?string $id = null;
    public ?UblDataTypeList $goodsItem;
    public ?UblDataTypeList $shipmentStage;
    public ?UblDataTypeList $transportHandlingUnit;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->goodsItem = new UblDataTypeList(GoodsItem::class);
        $this->shipmentStage = new UblDataTypeList(ShipmentStage::class);
        $this->transportHandlingUnit = new UblDataTypeList(TransportHandlingUnit::class);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->id);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:Shipment');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendChild($element,$this->goodsItem->toDOMElement($document));
        $this->appendChild($element,$this->shipmentStage->toDOMElement($document));
        $this->appendChild($element,$this->transportHandlingUnit->toDOMElement($document));

        return $element;
    }
}