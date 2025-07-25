<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Item extends UblDataType
{
    public ?string $name = null;
    public ?string $description = null;
    public ?BuyersItemIdentification $buyersItemIdentification = null;
    public ?SellersItemIdentification $sellersItemIdentification = null;
    public ?ManufacturersItemIdentification $manufacturersItemIdentification = null;

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
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['name', 'ad', 'urun_adi']) && StrUtil::notEmpty($v)) {
            $this->name = $v;
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

        return $element;
    }
}