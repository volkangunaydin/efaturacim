<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class PaymentTerms extends UblDataType
{
    public ?string $note = null;
    public ?string $paymentDueDate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['note', 'not']) && StrUtil::notEmpty($v)) {
            $this->note = $v;
            return true;
        }
        if (in_array($k, ['paymentDueDate', 'vade_tarihi', 'odeme_tarihi']) && StrUtil::notEmpty($v)) {
            $this->paymentDueDate = DateUtil::getAsDbDate($v);
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        // PaymentTerms is considered empty if it has no note and no due date.
        return StrUtil::isEmpty($this->note) && StrUtil::isEmpty($this->paymentDueDate);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:PaymentTerms');

        $this->appendElement($document, $element, 'cbc:Note', $this->note);
        $this->appendElement($document, $element, 'cbc:PaymentDueDate', $this->paymentDueDate);

        return $element;
    }
}