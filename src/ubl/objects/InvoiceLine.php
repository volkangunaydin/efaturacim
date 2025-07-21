<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class InvoiceLine extends UblDataType
{
    public ?string $id = null;
    public ?string $note = null;
    public ?float $invoicedQuantity = null;
    public ?string $invoicedQuantityUnitCode = 'C62'; // Default to "unit"
    public ?float $lineExtensionAmount = null;
    public ?string $lineExtensionAmountCurrencyID = 'TRY';

    /**
     * @var UblDataTypeList
     */
    public $allowanceCharge;

    public ?TaxTotal $taxTotal = null;
    public ?Item $item = null;
    public ?Price $price = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->allowanceCharge = new UblDataTypeList(AllowanceCharge::class);
        $this->taxTotal = new TaxTotal();
        $this->item = new Item();
        $this->price = new Price();

        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function addAllowanceCharge(array $options): self
    {
        $this->allowanceCharge->add(new AllowanceCharge($options));
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'line_id', 'sira_no']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['note', 'not']) && StrUtil::notEmpty($v)) {
            $this->note = $v;
            return true;
        }
        if (in_array($k, ['invoicedQuantity', 'quantity', 'miktar']) && is_numeric($v)) {
            $this->invoicedQuantity = (float)$v;
            return true;
        }
        if (in_array($k, ['invoicedQuantityUnitCode', 'unitCode', 'birim_kodu']) && StrUtil::notEmpty($v)) {
            $this->invoicedQuantityUnitCode = $v;
            return true;
        }
        if (in_array($k, ['lineExtensionAmount', 'line_amount', 'satir_tutari']) && is_numeric($v)) {
            $this->lineExtensionAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->lineExtensionAmountCurrencyID = $v;
            return true;
        }

        // Handle allowance charges array
        if (in_array(strtolower($k), ['allowancecharges', 'discounts', 'charges', 'iskontolar']) && is_array($v)) {
            foreach ($v as $acOptions) {
                if (is_array($acOptions)) {
                    $this->addAllowanceCharge($acOptions);
                }
            }
            return true;
        }

        // Pass other options to children
        if ($this->item->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        if ($this->price->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        if ($this->taxTotal->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // An invoice line must have an ID, a quantity, an amount, and an item.
        return StrUtil::isEmpty($this->id) || is_null($this->invoicedQuantity) || is_null($this->lineExtensionAmount) || $this->item->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:InvoiceLine');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:Note', $this->note);

        $this->appendElement($document, $element, 'cbc:InvoicedQuantity', number_format($this->invoicedQuantity, 2, '.', ''), ['unitCode' => $this->invoicedQuantityUnitCode]);

        $this->appendElement($document, $element, 'cbc:LineExtensionAmount', number_format($this->lineExtensionAmount, 2, '.', ''), ['currencyID' => $this->lineExtensionAmountCurrencyID]);

        foreach ($this->allowanceCharge->list as $ac) {
            $this->appendChild($element, $ac->toDOMElement($document));
        }

        $this->appendChild($element, $this->taxTotal->toDOMElement($document));
        $this->appendChild($element, $this->item->toDOMElement($document));
        $this->appendChild($element, $this->price->toDOMElement($document));

        return $element;
    }
}