<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\DateUtil;
use Efaturacim\Util\StrUtil;

class Delivery extends UblDataType
{
    public ?DeliveryAddress $deliveryAddress = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function initMe(){
        $this->deliveryAddress = new DeliveryAddress();
    }
    
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['deliveryAddress', 'delivery_address']) && StrUtil::notEmpty($v)) {
            $this->deliveryAddress = $v;
            return true;
        }

        return false;
    }
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true)
    {
        //\Vulcan\V::dump($arr);
        return parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug);
    }
    public function isEmpty(): bool
    {
        // Delivery element should always be present, even if empty
        return false;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        $element = $document->createElement('cac:Delivery');
        
        if ($this->deliveryAddress && !$this->deliveryAddress->isEmpty()) {
            $deliveryAddressElement = $this->deliveryAddress->toDOMElement($document);
            if ($deliveryAddressElement) {
                $this->appendChild($element, $deliveryAddressElement);
            }
        }

        return $element;
    }
}