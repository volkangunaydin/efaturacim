<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class Shipment extends UblDataType
{
    public ?string $id = null;
    public ?UblDataTypeList $goodsItem;
    public ?UblDataTypeList $shipmentStage;
    public ?UblDataTypeList $transportHandlingUnit;
    public ?ShipmentDelivery $delivery = null;
    /**
     * Summary of taxableAmount
     * @var DeclaredCustomsValueAmount
     */
    public ?DeclaredCustomsValueAmount $declaredCustomsValueAmount = null;
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->goodsItem = new UblDataTypeList(GoodsItem::class);
        $this->shipmentStage = new UblDataTypeList(ShipmentStage::class);
        $this->transportHandlingUnit = new UblDataTypeList(TransportHandlingUnit::class);
        $this->declaredCustomsValueAmount = new DeclaredCustomsValueAmount();
        $this->delivery = new ShipmentDelivery();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['declaredCustomsValueAmount', 'matrah']) && NumberUtil::isNumberString($v)) {
            $this->declaredCustomsValueAmount->setValue((float) $v);
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->declaredCustomsValueAmount->setCurrencyID($v);
            return true;
        }

        if (in_array($k, ['delivery', 'Delivery', 'DELIVERY']) && StrUtil::notEmpty($v)) {
            $this->delivery = new ShipmentDelivery($v);
            return true;
        }

        // TransportHandlingUnit için yeni bir element oluştur ve ekle
        if (in_array($k, ['TransportHandlingUnit', 'transportHandlingUnit', 'TRANSPORTHANDLINGUNIT'])) {
            if (is_array($v)) {
                $this->transportHandlingUnit = new UblDataTypeList(TransportHandlingUnit::class);
                $debugArray = array();
                $this->transportHandlingUnit->loadFromArray($v, 0, false, false, $debugArray);
            } elseif ($v instanceof UblDataTypeList) {
                $this->transportHandlingUnit = $v;
            } else {
                // Scalar veya desteklenmeyen tip geldiğinde atama yapma (tip hatasını engelle)
                if (is_null($this->transportHandlingUnit)) {
                    $this->transportHandlingUnit = new UblDataTypeList(TransportHandlingUnit::class);
                }
            }
            return true;
        }

        // TransportEquipment verilerini doğrudan işle
        if (in_array($k, ['transportEquipment', 'TransportEquipment', 'TRANSPORTEQUIPMENT']) && StrUtil::notEmpty($v)) {
            $transportHandlingUnit = new TransportHandlingUnit();
            $transportHandlingUnit->setPropertyFromOptions('id', $v['id'] ?? $v['ID'] ?? '', $v);
            if (isset($v['schemeID']) || isset($v['scheme_id'])) {
                $transportHandlingUnit->setPropertyFromOptions('schemeID', $v['schemeID'] ?? $v['scheme_id'] ?? '', $v);
            }
            $this->transportHandlingUnit->add($transportHandlingUnit);
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
        $declaredCustomsValueAmountIsEmpty = is_null($this->declaredCustomsValueAmount) || $this->declaredCustomsValueAmount->isEmpty();

        // Shipment ancak tüm öğeler BOŞ ise boş kabul edilmeli
        return $idIsEmpty && $deliveryIsEmpty && $goodsItemIsEmpty && $shipmentStageIsEmpty && $transportHandlingUnitIsEmpty && $declaredCustomsValueAmountIsEmpty;
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

        $this->appendChild($element, $this->goodsItem->toDOMElement($document));
        $this->appendChild($element, $this->shipmentStage->toDOMElement($document));
        $this->appendElementList($document, $this->transportHandlingUnit, $element);
        $this->appendChild($element,$this->declaredCustomsValueAmount->toDOMElement($document));
        return $element;
    }
}