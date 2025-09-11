<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

use Efaturacim\Util\Utils\String\StrUtil;

class ShipmentDelivery extends UblDataType
{
    public ?DeliveryAddress $deliveryAddress = null;
    public ?CarrierParty $carrierParty = null;
    public ?DeliveryTerms $deliveryTerms = null;

    public ?Despatch $despatch = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function initMe(){
        $this->deliveryAddress = new DeliveryAddress();
        $this->carrierParty = new CarrierParty();
        $this->deliveryTerms = new DeliveryTerms();
        $this->despatch = new Despatch();
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
        }else if(in_array($k,array("carrierParty","CarrierParty","carrier_party")) && StrUtil::notEmpty($v)){
            $this->carrierParty = new CarrierParty($v);
        }else if(in_array($k,array("deliveryTerms","DeliveryTerms","delivery_terms")) && StrUtil::notEmpty($v)){
            $this->deliveryTerms = new DeliveryTerms($v);
        }else if(in_array($k,array("despatch","Despatch","despatch")) && StrUtil::notEmpty($v)){
            $this->despatch = new Despatch($v);
        }
    }
    
    public function isEmpty()
    {
        $deliveryAddressIsEmpty = is_null($this->deliveryAddress) || $this->deliveryAddress->isEmpty();
        $carrierPartyIsEmpty = is_null($this->carrierParty) || $this->carrierParty->isEmpty();
        $deliveryTermsIsEmpty = is_null($this->deliveryTerms) || $this->deliveryTerms->isEmpty();
        $despatchIsEmpty = is_null($this->despatch) || $this->despatch->isEmpty();
        
        // ShipmentDelivery ancak tüm alt öğeler BOŞ ise boş kabul edilmeli
        return $deliveryAddressIsEmpty && $carrierPartyIsEmpty && $deliveryTermsIsEmpty && $despatchIsEmpty;
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

        if ($this->carrierParty && !$this->carrierParty->isEmpty()) {
            $carrierPartyElement = $this->carrierParty->toDOMElement($document);
            if ($carrierPartyElement) {
                $this->appendChild($element, $carrierPartyElement);
            }
        }

        if ($this->deliveryTerms && !$this->deliveryTerms->isEmpty()) {
            $deliveryTermsElement = $this->deliveryTerms->toDOMElement($document);
            if ($deliveryTermsElement) {
                $this->appendChild($element, $deliveryTermsElement);
            }
        }

        if ($this->despatch && !$this->despatch->isEmpty()) {
            $despatchElement = $this->despatch->toDOMElement($document);
            if ($despatchElement) {
                $this->appendChild($element, $despatchElement);
            }
        }

        return $element;
    }
}