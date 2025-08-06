<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class TaxSubtotal extends UblDataType
{
    /**
     * Summary of taxableAmount
     * @var TaxableAmount
     */
    public ?TaxableAmount $taxableAmount = null;
    public ?float $percent       = null;

    /**
     * Summary of taxAmount
     * @var TaxAmount
     */
    public ?TaxAmount $taxAmount = null;    

    public ?TaxCategory $taxCategory = null;

    public function __construct($options = null)
    {
        parent::__construct($options);                
        //\Vulcan\V::dump($options);
    }
    public function initMe(){
        $this->taxCategory   = new TaxCategory();
        $this->taxableAmount = new TaxableAmount();
        $this->taxAmount     = new TaxAmount();
        $this->defaultTagName = "cac:TaxSubtotal";
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['taxableAmount', 'matrah']) && NumberUtil::isNumberString($v)) {
            $this->taxableAmount->setValue((float)$v);
            return true;
        }

        if (in_array($k, ['taxAmount', 'vergi_tutari']) && NumberUtil::isNumberString($v)) {
            $this->taxAmount->setValue((float)$v);
            return true;
        }
        if (in_array($k, ['percent',  'kdv_oran','kdv_orani']) && NumberUtil::isNumberString($v)) {
            $this->percent = $v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->taxableAmount->setCurrencyID($v);
            $this->taxAmount->setCurrencyID($v);
            return true;
        }
        if (in_array($k, ['name']) && StrUtil::notEmpty($v)) {
            $this->taxCategory->taxScheme->name = $v; 
            return true;
        }
        if (in_array($k, ['TaxTypeCode','taxTypeCode','vergi_kodu']) && StrUtil::notEmpty($v)) {            
            $this->taxCategory->taxScheme->taxTypeCode  = $v;
            return true;
        }
        // Pass other options to taxCategory
        if ($this->taxCategory->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {        
        return false;
        // A tax subtotal is considered empty if the tax amount is not set,
        // or if the mandatory tax category is empty.
        return is_null($this->taxAmount) || is_null($this->taxCategory) || $this->taxCategory->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $this->createElement($document,$this->defaultTagName);
        $this->appendChild($element,$this->taxableAmount->toDOMElement($document));
        $this->appendChild($element,$this->taxAmount->toDOMElement($document));
        if(!is_null($this->percent)){
            $this->appendElement($document, $element, 'cbc:Percent',NumberUtil::asCleanNumber($this->percent));
        }
        $this->appendChild($element, $this->taxCategory->toDOMElement($document));

        return $element;
    }
    public function getTaxSchemeTaxTypeCode(){
        if($this->taxCategory && $this->taxCategory instanceof TaxCategory && $this->taxCategory->taxScheme && $this->taxCategory->taxScheme instanceof TaxScheme){
            return $this->taxCategory->taxScheme->taxTypeCode;
        }
        return null;
    }
    public function isVat(){
        if($this->taxCategory && $this->taxCategory->taxScheme && $this->taxCategory->taxScheme->taxTypeCode && $this->taxCategory->taxScheme->taxTypeCode=="0015"){
            return true;
        }
        return false;
    }
    public function getPercent(){
        return $this->percent;
    }
    public function getTaxAmount(){
        return $this->taxAmount->toNumber();
    }
    public function getTaxableAmount(){
        return $this->taxableAmount->toNumber();
    }
    public function getTaxExemptionReason(){
        return $this->taxCategory->taxExemptionReason;
    }
    public function getTaxExemptionReasonCode(){
        return $this->taxCategory->taxExemptionReasonCode;
    }
}
?>