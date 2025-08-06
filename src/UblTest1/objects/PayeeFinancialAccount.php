<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class PayeeFinancialAccount extends UblDataType
{
    public ?string $id = null; // IBAN
    public ?string $currencyCode = 'TRY';
    public ?string $paymentNote = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'iban']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['currencyCode', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->currencyCode = $v;
            return true;
        }
        if (in_array($k, ['paymentNote', 'odeme_notu']) && StrUtil::notEmpty($v)) {
            $this->paymentNote = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        // A financial account must have an ID (IBAN) to be valid.
        return StrUtil::isEmpty($this->id);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:PayeeFinancialAccount');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:CurrencyCode', $this->currencyCode);
        $this->appendElement($document, $element, 'cbc:PaymentNote', $this->paymentNote);

        return $element;
    }
}