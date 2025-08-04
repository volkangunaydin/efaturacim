<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class AllowanceCharge extends UblDataType
{
    public ?bool $chargeIndicator = false; // false for Allowance, true for Charge
    public ?string $allowanceChargeReason = null;
    public ?float $multiplierFactorNumeric = null; // e.g., 0.15 for 15%
    public ?float $amount = null;
    public ?string $amountCurrencyID = 'TRY';
    public ?float $baseAmount = null;
    public ?string $baseAmountCurrencyID = 'TRY';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe(){
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['chargeIndicator', 'is_charge']) && is_bool($v)) {
            $this->chargeIndicator = $v;
            return true;
        }
        if (in_array($k, ['allowanceChargeReason', 'reason', 'sebep', 'aciklama']) && StrUtil::notEmpty($v)) {
            $this->allowanceChargeReason = $v;
            return true;
        }
        if (in_array($k, ['multiplierFactorNumeric', 'rate', 'oran']) && is_numeric($v)) {
            $this->multiplierFactorNumeric = (float)$v;
            return true;
        }
        if (in_array($k, ['amount', 'tutar']) && is_numeric($v)) {
            $this->amount = (float)$v;
            return true;
        }
        if (in_array($k, ['baseAmount', 'matrah']) && is_numeric($v)) {
            $this->baseAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->amountCurrencyID = $v;
            $this->baseAmountCurrencyID = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:AllowanceCharge');

        $this->appendElement($document, $element, 'cbc:ChargeIndicator', $this->chargeIndicator ? 'true' : 'false');
        $this->appendElement($document, $element, 'cbc:AllowanceChargeReason', $this->allowanceChargeReason);

        if ($this->multiplierFactorNumeric !== null) {
            $this->appendElement($document, $element, 'cbc:MultiplierFactorNumeric', number_format($this->multiplierFactorNumeric, 4, '.', ''));
        }

        $this->appendElement($document, $element, 'cbc:Amount', number_format($this->amount, 2, '.', ''), ['currencyID' => $this->amountCurrencyID]);
        $this->appendElement($document, $element, 'cbc:BaseAmount', number_format($this->baseAmount, 2, '.', ''), ['currencyID' => $this->baseAmountCurrencyID]);

        return $element;
    }
}