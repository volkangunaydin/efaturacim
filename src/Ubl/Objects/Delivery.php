<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

use Efaturacim\Util\Utils\String\StrUtil;

class Delivery extends UblDataType
{
    public ?DeliveryAddress $deliveryAddress = null;
    public ?DeliveryTerms $deliveryTerms = null;
    public ?Shipment $shipment = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function initMe(){
        $this->deliveryAddress = new DeliveryAddress();
        $this->deliveryTerms = new DeliveryTerms();
        $this->shipment = new Shipment();
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
        }else if(in_array($k,array("shipment","Shipment","SHIPMENT")) && StrUtil::notEmpty($v)){
            $this->shipment = new Shipment($v);
        }
    }
    
    public function isEmpty()
    {
        $deliveryAddressIsEmpty = is_null($this->deliveryAddress) || $this->deliveryAddress->isEmpty();
        $deliveryTermsIsEmpty = is_null($this->deliveryTerms) || $this->deliveryTerms->isEmpty();
        $shipmentIsEmpty = is_null($this->shipment) || $this->shipment->isEmpty();

        // Delivery ancak tüm alt öğeler BOŞ ise boş kabul edilmeli
        return $deliveryAddressIsEmpty && $deliveryTermsIsEmpty && $shipmentIsEmpty;
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

        if ($this->shipment && !$this->shipment->isEmpty()) {
            $shipmentElement = $this->shipment->toDOMElement($document);
            if ($shipmentElement) {
                $this->appendChild($element, $shipmentElement);
            }
        }

        return $element;
    }
}