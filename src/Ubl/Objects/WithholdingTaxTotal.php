<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Utils\Number\NumberUtil;

class WithholdingTaxTotal extends UblDataType
{
    /**
     * Summary of taxAmount
     * @var TaxAmount
     */
    public  ?TaxAmount $taxAmount = null;    

    /**
     * @var UblDataTypeList
     */
    public ?UblDataTypeList $taxSubtotal;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
        $this->taxAmount   = new TaxAmount();
        $this->taxSubtotal = new UblDataTypeList(TaxSubtotal::class);
    }
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true) {
        return parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug);
    }
    public function addTaxSubtotal(array $options, $context = null): self
    {
        $this->taxSubtotal->add(new TaxSubtotal($options), null, null, $context);
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['taxAmount', 'vergi_tutari']) && NumberUtil::isNumberString($v)) {
            $this->taxAmount->setValue((float)$v);
            return true;
        }

        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->taxAmount->setCurrencyID($v);
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

        $element = $this->createElement($document,'cac:WithholdingTaxTotal');
        $this->appendChild($element,$this->taxAmount->toDOMElement($document));
        $this->appendChild($element,$this->taxSubtotal->toDOMElement($document));
        return $element;
    }
    public function getVatDefIndex($create = true)
    {
        foreach ($this->taxSubtotal->list as $key => $subtotal) {
            if ($subtotal instanceof TaxSubtotal) {
                if ($subtotal->getTaxSchemeTaxTypeCode() == "0015") {
                    return $key;
                }
            }
        }
        if ($create) {
            $this->addTaxSubtotal(array("taxTypeCode" => "0015", "name" => "KDV", "percent" => 0));
            return $this->getVatDefIndex(false);
        }
        return false;
    }
    public function setVatRate($rate)
    {
        $key = $this->getVatDefIndex();
        if (!is_null($key)) {
            $this->taxSubtotal->list[$key]->options->setValue("percent", $rate);
            $this->taxSubtotal->list[$key]->percent = $rate;
        }
    }
    public function setVatValue($val)
    {
        $key = $this->getVatDefIndex();
        if (!is_null($key)) {
            $this->taxSubtotal->list[$key]->taxAmount = $val;
        }
    }
    public function setVatTaxableAmount($val)
    {
        $key = $this->getVatDefIndex();
        if (!is_null($key)) {
            $this->taxSubtotal->list[$key]->taxableAmount = $val;
        }
    }
    public function onBeforeAdd($context = null)
    {
        if (Options::ensureParam($context) && $context instanceof Options) {

        }
    }
}