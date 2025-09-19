<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class PaymentExchangeRate extends UblDataType
{
    public ?string $sourceCurrencyCode = null;
    public ?string $targetCurrencyCode = 'TRY';
    public ?float $calculationRate = null;
    public ?string $date = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['sourceCurrencyCode', 'sourceCurrency', 'SourceCurrencyCode']) && StrUtil::notEmpty($v)) {
            $this->sourceCurrencyCode = $v;
            return true;
        }

        if (in_array($k, ['targetCurrencyCode', 'targetCurrency']) && StrUtil::notEmpty($v)) {
            $this->targetCurrencyCode = $v;
            return true;
        }

        if (in_array($k, ['calculationRate', 'CalculationRate']) && is_numeric($v)) {
            $this->calculationRate = $v;
            return true;
        }
        if (in_array($k, ['date', 'Date']) && StrUtil::notEmpty($v)) {
            $this->date = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->calculationRate) && is_null($this->date);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:PaymentExchangeRate');

        $this->appendElement($document, $element, 'cbc:SourceCurrencyCode', $this->sourceCurrencyCode);
        $this->appendElement($document, $element, 'cbc:TargetCurrencyCode', $this->targetCurrencyCode);
        $this->appendElement($document, $element, 'cbc:CalculationRate', $this->calculationRate);
        $this->appendElement($document, $element, 'cbc:Date', $this->date);
        return $element;
    }
}