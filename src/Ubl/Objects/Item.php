<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Item extends UblDataType
{
    public ?string $name = null;
    public ?string $brandName = null;
    public ?string $description = null;
    public ?BuyersItemIdentification $buyersItemIdentification = null;
    public ?SellersItemIdentification $sellersItemIdentification = null;
    public ?ManufacturersItemIdentification $manufacturersItemIdentification = null;
    public ?UblDataTypeListForAdditionalItemIdentification $additionalItemIdentification = null;
    public ?CommodityClassification $commodityClassification = null;
    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
        if (is_array($options)) {
            if (isset($options['buyersItemID'])) {
                $this->buyersItemIdentification = new BuyersItemIdentification(['id' => $options['buyersItemID']]);
            }
            if (isset($options['sellersItemID'])) {
                $this->sellersItemIdentification = new SellersItemIdentification(['id' => $options['sellersItemID']]);
            }
            if (isset($options['manufacturersItemID'])) {
                $this->manufacturersItemIdentification = new ManufacturersItemIdentification(['id' => $options['manufacturersItemID']]);
            }
            if (isset($options['commodityClassification'])) {
                $this->commodityClassification = new CommodityClassification(['itemClassificationCode' => $options['commodityClassification']]);
            }
        }
    }

    public function initMe()
    {
        $this->sellersItemIdentification = new SellersItemIdentification();
        $this->buyersItemIdentification = new BuyersItemIdentification();
        $this->manufacturersItemIdentification = new ManufacturersItemIdentification();
        $this->additionalItemIdentification = new UblDataTypeListForAdditionalItemIdentification(AdditionalItemIdentification::class);
        $this->commodityClassification = new CommodityClassification();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['name', 'ad', 'urun_adi']) && StrUtil::notEmpty($v)) {
            $this->name = $v;
            return true;
        }

        if (in_array($k, ['brandName', 'marka_adi']) && StrUtil::notEmpty($v)) {
            $this->brandName = $v;
            return true;
        }

        if (in_array($k, ['description', 'aciklama']) && StrUtil::notEmpty($v)) {
            $this->description = $v;
            return true;
        }

        if (in_array($k, ['sellersItemID', 'satici_stok_kodu']) && StrUtil::notEmpty($v)) {
            $this->sellersItemIdentification = new SellersItemIdentification(['id' => $v]);
            return true;
        }
        if (in_array($k, ['buyersItemID', 'alici_stok_kodu']) && StrUtil::notEmpty($v)) {
            $this->buyersItemIdentification = new BuyersItemIdentification(['id' => $v]);
            return true;
        }
        if (in_array($k, ['manufacturersItemID', 'uretici_stok_kodu']) && StrUtil::notEmpty($v)) {
            $this->manufacturersItemIdentification = new ManufacturersItemIdentification(['id' => $v]);
            return true;
        } else if (in_array($k, array("additionalItemID", "ek_stok_kodu")) && StrUtil::notEmpty($v)) {
            $schemeID = $options['schemeID'] ?? null;
            $this->additionalItemIdentification->setAdditionalItemID($v, $schemeID);
            return true;
        }

        if (in_array($k, ['commodityClassification', 'commodity_classification']) && StrUtil::notEmpty($v)) {
            $this->commodityClassification = new CommodityClassification(['itemClassificationCode' => $v]);
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // An item must at least have a name to be considered valid.
        return StrUtil::isEmpty($this->name);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Item');

        $this->appendElement($document, $element, 'cbc:Name', $this->name);
        $this->appendElement($document, $element, 'cbc:BrandName', $this->brandName);
        $this->appendElement($document, $element, 'cbc:Description', $this->description);

        if ($this->buyersItemIdentification) {
            $this->appendChild($element, $this->buyersItemIdentification->toDOMElement($document));
        }
        if ($this->sellersItemIdentification) {
            $this->appendChild($element, $this->sellersItemIdentification->toDOMElement($document));
        }
        if ($this->manufacturersItemIdentification) {
            $this->appendChild($element, $this->manufacturersItemIdentification->toDOMElement($document));
        }
        if ($this->commodityClassification) {
            $this->appendChild($element, $this->commodityClassification->toDOMElement($document));
        }
        if ($this->additionalItemIdentification && !$this->additionalItemIdentification->isEmpty()) {
            $this->appendChild($element, $this->additionalItemIdentification->toDOMElement($document));
        }
        return $element;
    }
}