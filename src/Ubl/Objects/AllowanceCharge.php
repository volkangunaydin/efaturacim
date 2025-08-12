<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class AllowanceCharge extends UblDataType
{
    /**    
     * @var UblDataTypeForBool
     */
    public  $chargeIndicator = null;
    /**    
     * @var UblDataTypeForString
     */
    public $allowanceChargeReason = null;
    /**    
     * @var UblDataTypeForNumeric
     */    
    public $multiplierFactorNumeric = null; // e.g., 0.15 for 15%
    /**    
     * @var Amount
     */    
    public  $amount = null;
    /**    
     * @var UblDataTypeForMoney
     */   
    public  $baseAmount = null;

    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:AllowanceCharge");        
        $this->chargeIndicator         = new UblDataTypeForBool(null,false,"cbc:ChargeIndicator");
        $this->allowanceChargeReason   = new UblDataTypeForString(null,false,"cbc:AllowanceChargeReason");
        $this->multiplierFactorNumeric = new UblDataTypeForNumeric(null,false,"cbc:MultiplierFactorNumeric");
        $this->amount                  = new Amount();
        $this->baseAmount              = new UblDataTypeForMoney(null,false,"cbc:BaseAmount");
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['amount', 'tutar']) && NumberUtil::isNumberString($v)) {
            $this->amount->setValue((float)$v);
            return true;
        }

        if (in_array($k, ['baseAmount', 'taban_tutar']) && NumberUtil::isNumberString($v)) {
            $this->baseAmount->setValue((float)$v);
            return true;
        }

        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->amount->setCurrencyID($v);
            $this->baseAmount->setCurrencyID($v);
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->amount) || $this->amount->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }        
        if(StrUtil::notEmpty($this->defaultTagName)){
            $element = $this->createElement($document,$this->defaultTagName);        
            $this->appendChild($element,$this->chargeIndicator->toDOMElement($document));
            $this->appendChild($element,$this->allowanceChargeReason->toDOMElement($document));
            $this->appendChild($element,$this->multiplierFactorNumeric->toDOMElement($document));
            $this->appendChild($element,$this->amount->toDOMElement($document));
            $this->appendChild($element,$this->baseAmount->toDOMElement($document));
        }                
        return $element;
    }
    public function toNumber(){
        return $this->amount->toNumber();
    }
    public function getValue(){
        return $this->toNumber();
    }
}