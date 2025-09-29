<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;

class CreditNoteLine extends UblDataType
{
    public ?ID $id = null;
    public ?InvoicedQuantity $invoicedQuantity = null;
    public ?LineExtensionAmount $lineExtensionAmount = null;
    public ?TaxExclusiveAmount $taxExclusiveAmount = null;
    public ?TaxInclusiveAmount $taxInclusiveAmount = null;
    public ?AllowanceTotalAmount $allowanceTotalAmount = null;
    public ?ChargeTotalAmount $chargeTotalAmount = null;
    public ?PayableAmount $payableAmount = null;
    public ?Delivery $delivery = null;
    public ?DespatchLineReference $despatchLineReference = null;

    /**     
     * @var Note
     */
    public ?Note $note = null;
    /**
     * @var UblDataTypeList
     */
    public $allowanceCharge;
    /**
     * @var UblDataTypeList
     */
    public $withholdingTaxTotal;

    public ?TaxTotal $taxTotal = null;
    public ?Item $item = null;
    public ?Price $price = null;



    public function initMe()
    {
        $this->id = new ID();
        $this->invoicedQuantity = new InvoicedQuantity();
        $this->allowanceCharge = new UblDataTypeList(AllowanceCharge::class);
        $this->withholdingTaxTotal = new UblDataTypeList(WithholdingTaxTotal::class);
        $this->lineExtensionAmount = new LineExtensionAmount();
        $this->taxExclusiveAmount = new TaxExclusiveAmount();
        $this->taxInclusiveAmount = new TaxInclusiveAmount();
        $this->allowanceTotalAmount = new AllowanceTotalAmount();
        $this->chargeTotalAmount = new ChargeTotalAmount();
        $this->payableAmount = new PayableAmount();
        $this->taxTotal = new TaxTotal();
        $this->item = new Item();
        $this->price = new Price();
        $this->note = new Note();
        $this->delivery = new Delivery();
        $this->despatchLineReference = new DespatchLineReference();
    }
    public function addAllowanceCharge(array $options): self
    {
        $this->allowanceCharge->add(new AllowanceCharge($options));
        return $this;
    }

    public function addWithholdingTaxTotal(array $options): self
    {
        $this->withholdingTaxTotal->add(new WithholdingTaxTotal($options));
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'line_id', 'sira_no']) && StrUtil::notEmpty($v)) {
            $this->id->textContent = $v;
            return true;
        }
        if (in_array($k, ['price', 'fiyat', 'birim_fiyat']) && NumberUtil::isPositiveNumber($v)) {
            $this->price = new Price(array('priceAmount' => $v));
            return true;
        }
        if (in_array($k, ['note', 'not']) && StrUtil::notEmpty($v)) {
            $this->note = Note::newNote($v);
            return true;
        }
        if (in_array($k, ['invoicedQuantity', 'quantity', 'miktar']) && is_numeric($v)) {
            $this->invoicedQuantity->setQuantity($v);
            return true;
        }
        if (in_array($k, ['invoicedQuantityUnitCode', 'unitCode', 'birim_kodu']) && StrUtil::notEmpty($v)) {
            $this->invoicedQuantity->setCode($v);
            return true;
        }
        if (in_array($k, ['lineExtensionAmount', 'line_amount', 'satir_tutari']) && is_numeric($v)) {
            $this->lineExtensionAmount->setValue($v);
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->lineExtensionAmount->setCurrencyID($v);
            return true;
        }
        if (in_array($k, ['despatchLineReference', 'satir_referansi']) && StrUtil::notEmpty($v)) {
            $this->despatchLineReference = $v;
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
        if (in_array($k, ['kdv', 'kdv_orani']) && StrUtil::notEmpty($v)) {
            $this->setVatRate($v);
            return true;
        }
        if (in_array($k, ['kdv_tutar', 'kdv_tutari']) && StrUtil::notEmpty($v)) {
            $this->setVatValue($v);
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // An invoice line must have an ID, a quantity, an amount, and an item.
        //|| is_null($this->invoicedQuantity)  || $this->item->isEmpty()
        return is_null($this->id) || $this->id->isEmpty();
    }
    public function getInvoicedQuantity()
    {
        if ($this->invoicedQuantity) {
            return $this->invoicedQuantity;
        }
        return 0;
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:CreditNoteLine');

        $this->appendChild($element, $this->id->toDOMElement($document));

        $this->appendChild($element, $this->note->toDOMElement($document));

        $this->appendChild($element, $this->invoicedQuantity->toDOMElement($document));

        $this->appendChild($element, $this->lineExtensionAmount->toDOMElement($document));

        $this->appendChild($element, $this->delivery->toDOMElement($document));

        foreach ($this->allowanceCharge->list as $ac) {
            $this->appendChild($element, $ac->toDOMElement($document));
        }

        foreach ($this->withholdingTaxTotal->list as $wtt) {
            $this->appendChild($element, $wtt->toDOMElement($document));
        }
        $this->appendChild($element, $this->despatchLineReference->toDOMElement($document));

        $this->appendChild($element, $this->taxTotal->toDOMElement($document));
        $this->appendChild($element, $this->item->toDOMElement($document));
        $this->appendChild($element, $this->price->toDOMElement($document));

        return $element;
    }
    public static function newLine($props)
    {
        $line = new CreditNoteLine($props);
        return $line;
    }
    public function onBeforeAdd($context = null)
    {
        if (Options::ensureParam($context) && $context instanceof Options) {
            if (is_null($this->id)) {
                $this->id = new ID();
                $nid = $context->getAsInt("nextLineId");
                if ($nid > 0) {
                    $this->id->textContent = $nid;
                } else {
                    $this->id->textContent = StrUtil::getGUID();
                }
            }
            if (is_null($this->lineExtensionAmount)) {
                $this->lineExtensionAmount = new LineExtensionAmount();
                $this->lineExtensionAmount->setValue($this->invoicedQuantity * NumberUtil::coalesce($this->price->priceAmount, 0));
            }
        }
    }
    public function setVatRate($rate)
    {
        $this->taxTotal->setVatRate($rate);
    }
    public function setVatValue($val)
    {
        $this->taxTotal->setVatValue($val);
    }
    public function calculateLineExtensionAmount()
    {
        return NumberUtil::asMoneyVal($this->invoicedQuantity->toNumber() * NumberUtil::coalesce($this->price->priceAmount, 0));
    }
    public function getLineExtensionAmount()
    {
        if (!is_null($this->lineExtensionAmount)) {
            return $this->lineExtensionAmount->toNumber();
        }
        return $this->calculateLineExtensionAmount();
    }

    public function getTaxExclusiveAmount()
    {
        $lineExtensionAmount = $this->getLineExtensionAmount();
        $allowanceTotalAmount = $this->getAllowanceTotalAmount();
        $chargeTotalAmount = $this->getChargeTotalAmount();

        // TaxExclusiveAmount: lineExtensionAmount - indirimler + vergiler
        $taxExclusiveAmount = $lineExtensionAmount - $allowanceTotalAmount + $chargeTotalAmount;

        return NumberUtil::asMoneyVal($taxExclusiveAmount);
    }

    public function getTaxInclusiveAmount()
    {
        $taxExclusiveAmount = $this->getTaxExclusiveAmount();
        $taxAmount = $this->taxTotal->taxAmount->toNumber();
        return NumberUtil::asMoneyVal($taxExclusiveAmount + $taxAmount);
    }

    public function getAllowanceTotalAmount()
    {
        return $this->allowanceCharge->sum(function ($line) {
            if ($line instanceof AllowanceCharge && $line->chargeIndicator->getValue() == false) {
                return $line->toNumber();
            }
        });
    }

    public function getChargeTotalAmount()
    {
        return $this->allowanceCharge->sum(function ($line) {
            if ($line instanceof AllowanceCharge && $line->chargeIndicator->getValue() == true) {
                return $line->toNumber();
            }
        });
    }

    public function getPayableAmount()
    {
        return $this->getTaxInclusiveAmount();
    }
    public function ensureOptions()
    {
        if (is_null($this->options)) {
            $this->options = new Options();
        }
    }
    public function getContextArray()
    {
        $this->ensureOptions();
        $arr = $this->options->getAs("context_array", array());
        if (count($arr) == 0) {
            $this->rebuildValues();
            $arr = $this->options->getAs("context_array", array());
        }
        return $arr;
    }
    public function loadFromArray($arr, $depth, $isDebug, $dieOnDebug, &$debugArray){
        parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug, $debugArray);
    }
    public function onAfterLoadComplete($arr, $debugArray)
    {
        //$this->showAsXml();
    }
    public function rebuildValues()
    {
        $this->lineExtensionAmount->setValue($this->calculateLineExtensionAmount());
        $kdvKey = $this->taxTotal->getVatDefIndex(false);
        if (!is_null($kdvKey)) {
            $kdv = &$this->taxTotal->taxSubtotal->list[$kdvKey];
            if ($kdv instanceof TaxSubtotal) {
                // KDV matrahı: lineExtensionAmount + vergiler
                $taxableAmount = $this->lineExtensionAmount->toNumber();

                // AllowanceCharge'dan vergileri topla
                if ($this->allowanceCharge && $this->allowanceCharge->list) {
                    foreach ($this->allowanceCharge->list as $allowanceCharge) {
                        if (
                            $allowanceCharge instanceof AllowanceCharge &&
                            isset($allowanceCharge->chargeIndicator) &&
                            $allowanceCharge->chargeIndicator === true
                        ) {
                            $taxableAmount += NumberUtil::asMoneyVal($allowanceCharge->amount ?? 0);
                        }
                    }
                }

                $kdv->taxableAmount->setValue($taxableAmount);
                $kdv->taxAmount->setValue(NumberUtil::asMoneyVal($taxableAmount * $kdv->percent / 100));
            }
        }

        // Calculate LegalMonetaryTotal values for this line
        $this->calculateLegalMonetaryTotal();
    }

    /**
     * Calculate LegalMonetaryTotal values for this invoice line
     */
    private function calculateLegalMonetaryTotal()
    {
        // Get calculated values using get methods
        $lineExtensionAmount = $this->getLineExtensionAmount();
        $taxExclusiveAmount = $this->getTaxExclusiveAmount();
        $taxInclusiveAmount = $this->getTaxInclusiveAmount();
        $allowanceTotalAmount = $this->getAllowanceTotalAmount();
        $chargeTotalAmount = $this->getChargeTotalAmount();
        $payableAmount = $this->getPayableAmount();

        // Store calculated values in context for parent document
        $arr = [
            "lineExtensionAmount" => $lineExtensionAmount,
            "taxExclusiveAmount" => $taxExclusiveAmount,
            "taxInclusiveAmount" => $taxInclusiveAmount,
            "allowanceTotalAmount" => $allowanceTotalAmount,
            "chargeTotalAmount" => $chargeTotalAmount,
            "payableAmount" => $payableAmount
        ];
        $this->ensureOptions();
        $this->options->setValue("context_array", $arr);
    }
    public function getVatAsArray()
    {
        $arr = array();
        if ($this->taxTotal) {
            foreach ($this->taxTotal->taxSubtotal->list as $taxSubtotal) {
                if ($taxSubtotal instanceof TaxSubtotal && $taxSubtotal->isVat()) {
                    $arr = array(
                        "percent" => $taxSubtotal->getPercent()
                        ,
                        "taxAmount" => $taxSubtotal->getTaxAmount()
                        ,
                        "taxableAmount" => $taxSubtotal->getTaxableAmount()
                        ,
                        "taxExemptionReason" => $taxSubtotal->getTaxExemptionReason()
                        ,
                        "taxExemptionReasonCode" => $taxSubtotal->getTaxExemptionReasonCode()
                    );
                }
            }
        }
        return $arr;
    }
}