<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class Address extends UblObject
{
    public ?string $streetName = null;
    public ?string $buildingNumber = null;
    public ?string $cityName = null;
    public ?string $postalZone = null;
    public ?Country $country = null;

    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:PostalAddress');

        $this->appendElement($document, $element, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $element, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $element, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $element, 'cbc:PostalZone', $this->postalZone);

        if ($this->country) {
            $element->appendChild($this->country->toDOMElement($document));
        }

        return $element;
    }
}