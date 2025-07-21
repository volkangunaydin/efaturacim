<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class PaymentMeans extends UblDataType
{
    public ?string $paymentMeansCode = null;
    public ?PayeeFinancialAccount $payeeFinancialAccount = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->payeeFinancialAccount = new PayeeFinancialAccount();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['paymentMeansCode', 'odeme_sekli_kodu']) && StrUtil::notEmpty($v)) {
            $this->paymentMeansCode = $v;
            return true;
        }

        // Pass other options to payeeFinancialAccount
        if ($this->payeeFinancialAccount->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // PaymentMeans must have a code.
        return StrUtil::isEmpty($this->paymentMeansCode);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:PaymentMeans');

        $this->appendElement($document, $element, 'cbc:PaymentMeansCode', $this->paymentMeansCode);
        $this->appendChild($element, $this->payeeFinancialAccount->toDOMElement($document));

        return $element;
    }
}