<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

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

    
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:LegalMonetaryTotal");
        $this->lineExtensionAmount = new LineExtensionAmount();
        $this->taxExclusiveAmount  = new TaxExclusiveAmount();
        $this->taxInclusiveAmount  = new TaxInclusiveAmount();
        $this->allowanceTotalAmount= new AllowanceTotalAmount();
        $this->chargeTotalAmount   = new ChargeTotalAmount();
        $this->payableAmount       = new PayableAmount();
    }
    public function setPropertyFromOptions($k, $v, $options): bool {
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
    
        /*
        $this->appendElement($document, $element, 'cbc:LineExtensionAmount', number_format($this->lineExtensionAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:TaxExclusiveAmount', number_format($this->taxExclusiveAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:TaxInclusiveAmount', number_format($this->taxInclusiveAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:AllowanceTotalAmount', number_format($this->allowanceTotalAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:ChargeTotalAmount', number_format($this->chargeTotalAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:PayableAmount', number_format($this->payableAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
*/
        //\Vulcan\V::dump($this);
        return $element;
    }
}