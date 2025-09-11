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
    public ?ShipmentDelivery $delivery = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->goodsItem = new UblDataTypeList(GoodsItem::class);
        $this->shipmentStage = new UblDataTypeList(ShipmentStage::class);
        $this->transportHandlingUnit = new UblDataTypeList(TransportHandlingUnit::class);
        $this->delivery = new ShipmentDelivery();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }

        if (in_array($k, ['delivery', 'Delivery', 'DELIVERY']) && StrUtil::notEmpty($v)) {
            $this->delivery = new ShipmentDelivery($v);
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        $idIsEmpty = is_null($this->id);
        $deliveryIsEmpty = is_null($this->delivery) || $this->delivery->isEmpty();
        $goodsItemIsEmpty = is_null($this->goodsItem) || $this->goodsItem->isEmpty();
        $shipmentStageIsEmpty = is_null($this->shipmentStage) || $this->shipmentStage->isEmpty();
        $transportHandlingUnitIsEmpty = is_null($this->transportHandlingUnit) || $this->transportHandlingUnit->isEmpty();

        // Shipment ancak tüm öğeler BOŞ ise boş kabul edilmeli
        return $idIsEmpty && $deliveryIsEmpty && $goodsItemIsEmpty && $shipmentStageIsEmpty && $transportHandlingUnitIsEmpty;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:Shipment');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        
        if ($this->delivery && !$this->delivery->isEmpty()) {
            $deliveryElement = $this->delivery->toDOMElement($document);
            if ($deliveryElement) {
                $this->appendChild($element, $deliveryElement);
            }
        }
        
        $this->appendChild($element,$this->goodsItem->toDOMElement($document));
        $this->appendChild($element,$this->shipmentStage->toDOMElement($document));
        $this->appendChild($element,$this->transportHandlingUnit->toDOMElement($document));

        return $element;
    }
}