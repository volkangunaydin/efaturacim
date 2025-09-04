<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class DeliveryAddress extends UblDataType
{
    public ?string $room = null;
    public ?string $streetName = null;
    public ?string $buildingName = null;
    public ?string $buildingNumber = null;
    public ?string $cityName = null;
    public ?string $postalZone = null;
    public ?string $citySubdivisionName = null;
    public ?string $region = null;
    public ?string $district = null;
    public ?Country $country = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe(){
        $this->country = new Country();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if(in_array($k,array("room")) && StrUtil::notEmpty($v)){
            $this->room = $v;
            return true;
        }else if(in_array($k,array("sokak")) && StrUtil::notEmpty($v)){
            $this->streetName = $v;
            return true;
        }else if(in_array($k,array("bina_adi")) && StrUtil::notEmpty($v)){
            $this->buildingName = $v;
            return true;
        }else if(in_array($k,array("bina")) && StrUtil::notEmpty($v)){
            $this->buildingNumber = $v;
            return true;
        }else if(in_array($k,array("ilce")) && StrUtil::notEmpty($v)){
            $this->citySubdivisionName = $v;
            return true;
        }else if(in_array($k,array("il")) && StrUtil::notEmpty($v)){
            $this->cityName = $v;
            return true;
        }else if(in_array($k,array("posta_kodu")) && StrUtil::notEmpty($v)){
            $this->postalZone = $v;
            return true;
        }else if(in_array($k,array("bolge")) && StrUtil::notEmpty($v)){
            $this->region = $v;
            return true;
        }else if(in_array($k,array("semt")) && StrUtil::notEmpty($v)){
            $this->district = $v;
            return true;
        }else{
            if (is_null($this->country)) {
                $this->initMe();
            }
            if ($this->country->setPropertyFromOptions($k, $v, $options)) {
                return true;
            }
        }
        return false;
    }

    public function isEmpty(){        
        return !StrUtil::notEmpty($this->cityName);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        $element = $document->createElement('cac:DeliveryAddress');
        
        $this->appendElement($document, $element, 'cbc:Room', $this->room);
        $this->appendElement($document, $element, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $element, 'cbc:BuildingName', $this->buildingName);
        $this->appendElement($document, $element, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $element, 'cbc:CitySubdivisionName', $this->citySubdivisionName);
        $this->appendElement($document, $element, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $element, 'cbc:PostalZone', $this->postalZone);
        $this->appendElement($document, $element, 'cbc:Region', $this->region);
        $this->appendElement($document, $element, 'cbc:District', $this->district);
        
        if ($this->country && !$this->country->isEmpty()) {
            $countryElement = $this->country->toDOMElement($document);
            if ($countryElement) {
                $this->appendChild($element, $countryElement);
            }
        }
        
        return $element;
    }
}