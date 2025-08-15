<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class PricingExchangeRate extends UblDataType
{
    public ?string $sourceCurrencyCode = null;
    public ?string $targetCurrencyCode = 'TRY';

    public ?float $calculationRate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['sourceCurrencyCode', 'sourceCurrency']) && StrUtil::notEmpty($v)) {
            $this->sourceCurrencyCode = $v;
            return true;
        }

        if (in_array($k, ['targetCurrencyCode', 'targetCurrency']) && StrUtil::notEmpty($v)) {
            $this->targetCurrencyCode = $v;
            return true;
        }

        if (in_array($k, ['calculationRate']) && is_numeric($v)) {
            $this->calculationRate = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->calculationRate);
    }

    public function isTry(): bool
    {
        return $this->sourceCurrencyCode === 'TRY';
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        if ($this->isTry()) {
            return null;
        }

        $element = $document->createElement('cac:PricingExchangeRate');

        $this->appendElement($document, $element, 'cbc:SourceCurrencyCode', $this->sourceCurrencyCode);
        $this->appendElement($document, $element, 'cbc:TargetCurrencyCode', $this->targetCurrencyCode);
        $this->appendElement($document, $element, 'cbc:CalculationRate', number_format($this->calculationRate, 2, '.', ''));

        return $element;
    }
}