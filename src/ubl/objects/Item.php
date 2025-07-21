<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Item extends UblDataType
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $sellersItemID = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
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
            $this->sellersItemID = $v;
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

        if (StrUtil::notEmpty($this->sellersItemID)) {
            $sellersItemIdentificationElement = $this->appendElement($document, $element, 'cac:SellersItemIdentification', null);
            $this->appendElement($document, $sellersItemIdentificationElement, 'cbc:ID', $this->sellersItemID);
        }

        return $element;
    }
}