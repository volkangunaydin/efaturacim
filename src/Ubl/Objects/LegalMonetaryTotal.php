<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class LegalMonetaryTotal extends UblDataType
{
    /**     
     * @var LineExtensionAmount
     */
    public $lineExtensionAmount = null;
    /**     
     * @var TaxExclusiveAmount
     */
    public $taxExclusiveAmount = null;
    /**     
     * @var TaxInclusiveAmount
     */
    public $taxInclusiveAmount = null;
    /**
     * @var AllowanceTotalAmount
     */
    public $allowanceTotalAmount = null;
    /**
     * @var ChargeTotalAmount
     */
    public $chargeTotalAmount = null;
    /**
     * @var PayableAmount
     */
    public $payableAmount = null;
    /**
     * @var PayableRoundingAmount
     */
    public $payableRoundingAmount = null;    

    
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:LegalMonetaryTotal");
        $this->lineExtensionAmount = new LineExtensionAmount();
        $this->taxExclusiveAmount  = new TaxExclusiveAmount();
        $this->taxInclusiveAmount  = new TaxInclusiveAmount();
        $this->allowanceTotalAmount= new AllowanceTotalAmount();
        $this->chargeTotalAmount   = new ChargeTotalAmount();
        $this->payableAmount       = new PayableAmount();
        $this->payableRoundingAmount = new PayableRoundingAmount();
    }
    public function setPropertyFromOptions($k, $v, $options): bool {
        if(in_array($k, ['lineExtensionAmount', 'lineExtensionAmountCurrencyID'])){
            if($k === 'lineExtensionAmount') {
                $this->lineExtensionAmount->setValue($v);
            } else {
                $this->lineExtensionAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['taxExclusiveAmount', 'taxExclusiveAmountCurrencyID'])){
            if($k === 'taxExclusiveAmount') {
                $this->taxExclusiveAmount->setValue($v);
            } else {
                $this->taxExclusiveAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['taxInclusiveAmount', 'taxInclusiveAmountCurrencyID'])){
            if($k === 'taxInclusiveAmount') {
                $this->taxInclusiveAmount->setValue($v);
            } else {
                $this->taxInclusiveAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['allowanceTotalAmount', 'allowanceTotalAmountCurrencyID'])){
            if($k === 'allowanceTotalAmount') {
                $this->allowanceTotalAmount->setValue($v);
            } else {
                $this->allowanceTotalAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['chargeTotalAmount', 'chargeTotalAmountCurrencyID'])){
            if($k === 'chargeTotalAmount') {
                $this->chargeTotalAmount->setValue($v);
            } else {
                $this->chargeTotalAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['payableAmount', 'payableAmountCurrencyID'])){ 
            if($k === 'payableAmount') {
                $this->payableAmount->setValue($v);
            } else {
                $this->payableAmount->setCurrencyID($v);
            }
            return true;
        }
        if(in_array($k, ['payableRoundingAmount', 'payableRoundingAmountCurrencyID'])){ 
            if($k === 'payableRoundingAmount') {
                $this->payableRoundingAmount->setValue($v);
            } else {
                $this->payableRoundingAmount->setCurrencyID($v);
            }
            return true;
        }
        return false;
    }
    public function setCurrencyID($code=null){
        $this->attributes["currencyID"] = $code;         
    }
    public function isEmpty(): bool
    {
        // LegalMonetaryTotal is essential and should at least have a payable amount.
        return is_null($this->payableAmount);
    }
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true)   {
        return parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug);
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $this->createElement($document,$this->defaultTagName);        
        $this->appendChild($element,$this->lineExtensionAmount->toDOMElement($document));    
        $this->appendChild($element,$this->taxExclusiveAmount->toDOMElement($document));    
        $this->appendChild($element,$this->taxInclusiveAmount->toDOMElement($document));    
        $this->appendChild($element,$this->allowanceTotalAmount->toDOMElement($document));    
        $this->appendChild($element,$this->chargeTotalAmount->toDOMElement($document));    
        $this->appendChild($element,$this->payableAmount->toDOMElement($document));    
        $this->appendChild($element,$this->payableRoundingAmount->toDOMElement($document));    
        return $element;
    }
    public function getPayableAmount(){
        return $this->payableAmount->toNumber();
    }
    public function getLineExtensionAmount(){
        return $this->lineExtensionAmount->toNumber();
    }
    public function getTaxExclusiveAmount(){
        return $this->taxExclusiveAmount->toNumber();
    }
    public function getTaxInclusiveAmount(){
        return $this->taxInclusiveAmount->toNumber();
    }
    public function getAllowanceTotalAmount(){
        return $this->allowanceTotalAmount->toNumber();
    }
    public function getChargeTotalAmount(){
        return $this->chargeTotalAmount->toNumber();
    }
    public function getPayableRoundingAmount(){
        return $this->payableRoundingAmount->toNumber();
    }
}
?>