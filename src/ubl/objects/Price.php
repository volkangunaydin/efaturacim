<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Price extends UblDataType
{
    public ?float $priceAmount = null;
    public ?string $priceAmountCurrencyID = 'TRY';

    public ?float $baseQuantity = null;
    public ?string $baseQuantityUnitCode = 'C62';

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['priceAmount', 'fiyat', 'tutar']) && is_numeric($v)) {
            $this->priceAmount = (float)$v;
            return true;
        }

        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->priceAmountCurrencyID = $v;
            return true;
        }

        if (in_array($k, ['baseQuantity', 'birim_miktar']) && is_numeric($v)) {
            $this->baseQuantity = (float)$v;
            return true;
        }

        if (in_array($k, ['baseQuantityUnitCode', 'birim_kodu']) && StrUtil::notEmpty($v)) {
            $this->baseQuantityUnitCode = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->priceAmount);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Price');

        $this->appendElement($document, $element, 'cbc:PriceAmount', number_format($this->priceAmount, 2, '.', ''), ['currencyID' => $this->priceAmountCurrencyID]);

        if ($this->baseQuantity !== null) {
            $this->appendElement($document, $element, 'cbc:BaseQuantity', number_format($this->baseQuantity, 2, '.', ''), ['unitCode' => $this->baseQuantityUnitCode]);
        }

        return $element;
    }
}