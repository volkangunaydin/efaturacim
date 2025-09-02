<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class UblDataTypeForPartyContainer extends UblDataType{
    public ?Party $party = null;

    public function initMe(){
        $this->party = new Party();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        return $this->party->setPropertyFromOptions($k, $v, $options);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement($this->defaultTagName);
        $partyElement = $this->party->toDOMElement($document);
        if ($partyElement) {
            $this->appendChild($element, $partyElement);
        }

        return $element;
    }

    public function isEmpty(): bool
    {
        return is_null($this->party) || $this->party->isEmpty();
    }
    public function getName(){
        return $this->party->getPartyName();
    }
    public function getVknOrTckn(){
        return $this->party->getVknOrTckn();
    }
    public function getStreetName(){
        return $this->party->getPostalAddress()->getStreetName();
    }
    public function getBuildingName(){
        return $this->party->getPostalAddress()->getBuildingName();
    }
    public function getBuildingNumber(){
        return $this->party->getPostalAddress()->getBuildingNumber();
    }
    public function getCitySubdivisionName(){
        return $this->party->getPostalAddress()->getCitySubdivisionName();
    }
    public function getCityName(){
        return $this->party->getPostalAddress()->getCityName();
    }
    public function getPostalZone(){
        return $this->party->getPostalAddress()->getPostalZone();
    }
    public function getRegion(){
        return $this->party->getPostalAddress()->getRegion();
    }
    public function getDistrict(){
        return $this->party->getPostalAddress()->getDistrict();
    }
    public function getCountryIdentificationCode(){
        return $this->party->getPostalAddress()->getCountry()->getIdentificationCode();
    }
    public function getCountryName(){
        return $this->party->getPostalAddress()->getCountry()->getName();
    }
    public function getTaxSchemeName(){
        return $this->party->getTaxSchemeName();
    }
}