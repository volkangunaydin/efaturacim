<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class PaymentMeans extends UblDataType
{
    public ?PaymentMeansCode $paymentMeansCode = null;    
    public function initMe(){
        $this->paymentMeansCode = new PaymentMeansCode();
    }

    public function setValue($value,$listID=null){
        $this->paymentMeansCode->textContent = $value;        
        if(StrUtil::notEmpty($listID)){
            $this->paymentMeansCode->attributes['listID'] = $listID;
        }        
        return $this;
    }
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $this->createElement($document,'cac:PaymentMeans');        
        $element->appendChild($this->paymentMeansCode->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        if(is_null($this->paymentMeansCode) || $this->paymentMeansCode->isEmpty()){
            return true;
        }
        return false;        
    }
}