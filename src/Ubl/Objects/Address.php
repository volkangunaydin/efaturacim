<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Address extends UblDataType
{
    public ?string $id = null;
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
    public function initMe()
    {
        $this->country = new Country();
    }
    public function setPropertyFromOptions($k, $v, $options)
    {
        if (in_array($k, array("sokak","streetName")) && StrUtil::notEmpty($v)) {
            $this->streetName = $v;
        } else if (in_array($k, array("id")) && StrUtil::notEmpty($v)) {
            $this->id = $v;
        } else if (in_array($k, array("bina","buildingName")) && StrUtil::notEmpty($v)) {
            $this->buildingName = $v;
        } else if (in_array($k, array("bina_no","buildingNumber")) && StrUtil::notEmpty($v)) {
            $this->buildingNumber = $v;
        } else if (in_array($k, array("ilce","citySubdivisionName")) && StrUtil::notEmpty($v)) {
            $this->citySubdivisionName = $v;
        } else if (in_array($k, array("il","cityName")) && StrUtil::notEmpty($v)) {
            $this->cityName = $v;
        } else if (in_array($k, array("posta_kodu","postalZone")) && StrUtil::notEmpty($v)) {
            $this->postalZone = $v;
        } else if (in_array($k, array("bolge","region", "mahalle")) && StrUtil::notEmpty($v)) {
            $this->region = $v;
        } else if (in_array($k, array("semt","district")) && StrUtil::notEmpty($v)) {
            $this->district = $v;
        } else {
            //\Vulcan\V::dump(array($k,$v,$options));
        }
        return false;
    }

    public function isEmpty()
    {
        return !StrUtil::notEmpty($this->id) &&
               !StrUtil::notEmpty($this->streetName) &&
               !StrUtil::notEmpty($this->buildingName) &&
               !StrUtil::notEmpty($this->buildingNumber) &&
               !StrUtil::notEmpty($this->cityName) &&
               !StrUtil::notEmpty($this->postalZone) &&
               !StrUtil::notEmpty($this->citySubdivisionName) &&
               !StrUtil::notEmpty($this->region) &&
               !StrUtil::notEmpty($this->district) &&
               (!$this->country || $this->country->isEmpty());
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        
        $element = $document->createElement('cac:PostalAddress');
        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $element, 'cbc:BuildingName', $this->buildingName);
        $this->appendElement($document, $element, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $element, 'cbc:CitySubdivisionName', $this->citySubdivisionName);
        $this->appendElement($document, $element, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $element, 'cbc:PostalZone', $this->postalZone);
        $this->appendElement($document, $element, 'cbc:Region', $this->region);
        $this->appendElement($document, $element, 'cbc:District', $this->district);
        //\Vulcan\V::dump($this->country);
        if ($this->country && !$this->country->isEmpty()) {
            $countryElement = $this->country->toDOMElement($document);
            if ($countryElement) {
                $this->appendChild($element, $countryElement);
            }
        }
        

        return $element;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getStreetName()
    {
        return $this->streetName;
    }
    public function getBuildingName()
    {
        return $this->buildingName;
    }
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }
    public function getCitySubdivisionName()
    {
        return $this->citySubdivisionName;
    }
    public function getCityName()
    {
        return $this->cityName;
    }
    public function getPostalZone()
    {
        return $this->postalZone;
    }
    public function getRegion()
    {
        return $this->region;
    }
    public function getDistrict()
    {
        return $this->district;
    }
    public function getCountry()
    {
        return $this->country;
    }
    public function getCountryIdentificationCode()
    {
        return $this->country->getIdentificationCode();
    }
    public function getCountryName()
    {
        return $this->country->getName();
    }
}