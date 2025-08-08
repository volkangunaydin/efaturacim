<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

use Efaturacim\Util\Utils\String\StrUtil;

class Delivery extends UblDataType
{
    public ?DeliveryAddress $deliveryAddress = null;
    public ?DeliveryTerms $deliveryTerms = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function initMe(){
        $this->deliveryAddress = new DeliveryAddress();
        $this->deliveryTerms = new DeliveryTerms();
    }
    
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true)
    {
        //\Vulcan\V::dump($arr);
        return parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug);
    }
    
    public function setPropertyFromOptions($k, $v, $options)
    {
        if(in_array($k,array("deliveryAddress","DeliveryAddress","delivery_address")) && StrUtil::notEmpty($v)){
            $this->deliveryAddress = new DeliveryAddress($v);
        }else if(in_array($k,array("deliveryTerms","DeliveryTerms","delivery_terms")) && StrUtil::notEmpty($v)){
            $this->deliveryTerms = new DeliveryTerms($v);
        }
    }
    
    public function isEmpty()
    {
        return is_null($this->deliveryAddress) || is_null($this->deliveryTerms);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:Delivery');
        
        if ($this->deliveryAddress && !$this->deliveryAddress->isEmpty()) {
            $deliveryAddressElement = $this->deliveryAddress->toDOMElement($document);
            if ($deliveryAddressElement) {
                $this->appendChild($element, $deliveryAddressElement);
            }
        }

        if ($this->deliveryTerms && !$this->deliveryTerms->isEmpty()) {
            $deliveryTermsElement = $this->deliveryTerms->toDOMElement($document);
            if ($deliveryTermsElement) {
                $this->appendChild($element, $deliveryTermsElement);
            }
        }

        return $element;
    }
}