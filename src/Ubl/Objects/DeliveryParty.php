<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class DeliveryParty extends Party
{
    /**
     * Converts the object to a DOMElement.
     * Overrides the parent to create a <cac:DeliveryParty> element.
     *
     * @param DOMDocument $document The parent DOMDocument.
     * @return DOMElement|null The generated DOMElement representing this object.
     */
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:DeliveryParty');

        if (StrUtil::notEmpty($this->websiteURI)) {
            $this->appendElement($document, $element, 'cbc:WebsiteURI', $this->websiteURI);
        }

        $partyNameElement = $this->appendElement($document, $element, 'cac:PartyName', null);
        if (StrUtil::notEmpty($this->partyName)) {
            $this->appendElement($document, $partyNameElement, 'cbc:Name', $this->partyName);
        }

        $this->appendChild($element, $this->partyIdentification->toDOMElement($document));
        $this->appendChild($element, $this->postalAddress->toDOMElement($document));
        $this->appendChild($element, $this->partyTaxScheme->toDOMElement($document));
        $this->appendChild($element, $this->contact->toDOMElement($document));

        return $element;
    }
}