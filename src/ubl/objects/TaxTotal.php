<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class TaxTotal extends UblDataType
{
    public ?float $taxAmount = null;
    public ?string $taxAmountCurrencyID = 'TRY';

    /**
     * @var UblDataTypeList
     */
    public $taxSubtotal;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->taxSubtotal = new UblDataTypeList(TaxSubtotal::class);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function addTaxSubtotal(array $options): self
    {
        $this->taxSubtotal->add(new TaxSubtotal($options));
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['taxAmount', 'toplam_vergi_tutari']) && is_numeric($v)) {
            $this->taxAmount = (float)$v;
            return true;
        }

        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->taxAmountCurrencyID = $v;
            return true;
        }

        if (in_array(strtolower($k), ['taxsubtotal', 'subtotals', 'vergi_detaylari']) && is_array($v)) {
            foreach ($v as $subtotalOptions) {
                if (is_array($subtotalOptions)) {
                    $this->addTaxSubtotal($subtotalOptions);
                }
            }
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->taxAmount) || $this->taxSubtotal->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:TaxTotal');

        $this->appendElement($document, $element, 'cbc:TaxAmount', number_format($this->taxAmount, 2, '.', ''), ['currencyID' => $this->taxAmountCurrencyID]);

        foreach ($this->taxSubtotal->list as $subtotal) {
            $this->appendChild($element, $subtotal->toDOMElement($document));
        }

        return $element;
    }
}