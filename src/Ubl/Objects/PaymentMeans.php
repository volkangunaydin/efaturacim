<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class PaymentMeans extends UblDataType
{
    public ?PaymentMeansCode $paymentMeansCode = null;    
    public ?string $paymentChannelCode = null;
    public ?string $instructionNote = null;
    public ?string $paymentDueDate = null;    
    public ?PayeeFinancialAccount $payeeFinancialAccount = null;
    public function initMe(){
        $this->paymentMeansCode = new PaymentMeansCode();
        $this->payeeFinancialAccount = new PayeeFinancialAccount();
    }

    public function setValue($value,$listID=null){
        $this->paymentMeansCode->textContent = $value;        
        if(StrUtil::notEmpty($listID)){
            $this->paymentMeansCode->attributes['listID'] = $listID;
        }        
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){    
        if(in_array($k,['paymentMeansCode','odeme_yontemi'])){
            $this->paymentMeansCode->textContent = $v;
            return true;
        }
        if(in_array($k,['paymentChannelCode','odeme_kanali'])){
            $this->paymentChannelCode = $v;
            return true;
        }
        if(in_array($k,['instructionNote','odeme_aciklama'])){
            $this->instructionNote = $v;
            return true;
        }
        if(in_array($k,['paymentDueDate','odeme_tarihi'])){
            $this->paymentDueDate = $v;
            return true;
        }
        if(in_array($k,['payeeFinancialAccount','odeme_hesap','payeeFinancialAccount'])){
            if (is_array($v)) {
                $this->payeeFinancialAccount->loadFromOptions($v);
            } else {
                $this->payeeFinancialAccount->loadFromOptions(['id' => $v]);
            }
            return true;
        }
        return false;
    }
    public function isEmpty(){
        return is_null($this->paymentMeansCode) || $this->paymentMeansCode->isEmpty();
    }
    public function toDOMElement(DOMDocument $document){
        if ($this->isEmpty()) {            
            return null;
        }
        
        $element = $this->createElement($document,'cac:PaymentMeans');        
        $element->appendChild($this->paymentMeansCode->toDOMElement($document));
        $this->appendElement($document, $element, 'cbc:PaymentChannelCode', $this->paymentChannelCode);
        $this->appendElement($document, $element, 'cbc:InstructionNote', $this->instructionNote);
        $this->appendElement($document, $element, 'cbc:PaymentDueDate', $this->paymentDueDate);
        
        // PayeeFinancialAccount varsa ekle
        if ($this->payeeFinancialAccount && !$this->payeeFinancialAccount->isEmpty() && $this->payeeFinancialAccount->id && !$this->payeeFinancialAccount->id->isEmpty()) {
            $this->appendChild($element, $this->payeeFinancialAccount->toDOMElement($document));
        }
        return $element;
    }
    
}