<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class PayeeFinancialAccount extends UblDataType
{
    public ?ID $id = null;    
    public ?string $currencyCode = 'TRY';
    public ?string $paymentNote = null;
    public function initMe(){
        $this->id = new ID();
    }

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setValue($value,$schemeID=null){
        $this->id->textContent = $value;        
        if(StrUtil::notEmpty($schemeID)){
            $this->id->attributes['schemeID'] = $schemeID;
        }        
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){        
        if (in_array($k, ['id', 'ID']) && StrUtil::notEmpty($v)) {
            $this->id->textContent = $v;
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->id->attributes['schemeID'] = $v;
            return true;
        }
        if (in_array($k, ['currencyCode', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->currencyCode = $v;
            return true;
        }
        if (in_array($k, ['paymentNote', 'odeme_notu']) && StrUtil::notEmpty($v)) {
            $this->paymentNote = $v;
            return true;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if ($this->isEmpty()) {
            return null;
        }
        $element = $this->createElement($document,'cac:PayeeFinancialAccount');        
        $element->appendChild($this->id->toDOMElement($document));
        if (StrUtil::notEmpty($this->currencyCode)) {
            $this->appendElement($document, $element, 'cbc:CurrencyCode', $this->currencyCode);
        }
        
        // PaymentNote alanını ekle
        if (StrUtil::notEmpty($this->paymentNote)) {
            $this->appendElement($document, $element, 'cbc:PaymentNote', $this->paymentNote);
        }
        return $element;
    }
    public function isEmpty(): bool
    {
        return StrUtil::isEmpty($this->id) && StrUtil::isEmpty($this->currencyCode) && StrUtil::isEmpty($this->paymentNote);
    }
    public function getValue(){
        return $this->id->getValue();
    }
}