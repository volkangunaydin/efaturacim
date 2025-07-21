<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class DeliveryLocation extends Address
{
    /**
     * Checks if the delivery location has any meaningful data.
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return StrUtil::isEmpty($this->streetName) &&
               StrUtil::isEmpty($this->buildingNumber) &&
               StrUtil::isEmpty($this->citySubdivisionName) &&
               StrUtil::isEmpty($this->cityName) &&
               StrUtil::isEmpty($this->postalZone);
    }

    /**
     * Converts the object to a DOMElement.
     * Creates a <cac:DeliveryLocation> element containing a <cac:Address> element.
     *
     * @param DOMDocument $document The parent DOMDocument.
     * @return DOMElement|null The generated DOMElement representing this object.
     */
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $deliveryLocationElement = $document->createElement('cac:DeliveryLocation');

        // The parent Address class creates a <cac:PostalAddress>. For DeliveryLocation,
        // UBL requires <cac:Address>. So we build it manually here, using the
        // properties inherited from the parent Address class.
        $addressElement = $document->createElement('cac:Address');
        $this->appendElement($document, $addressElement, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $addressElement, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $addressElement, 'cbc:CitySubdivisionName', $this->citySubdivisionName);
        $this->appendElement($document, $addressElement, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $addressElement, 'cbc:PostalZone', $this->postalZone);

        if ($this->country) {
            $this->appendChild($addressElement, $this->country->toDOMElement($document));
        }

        $deliveryLocationElement->appendChild($addressElement);

        return $deliveryLocationElement;
    }
}