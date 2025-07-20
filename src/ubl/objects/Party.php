<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class Party extends UblObject
{
    public ?string $websiteURI = null;
    public ?string $partyName = null;
    public ?Address $postalAddress = null;
    public ?PartyIdentification $partyIdentification = null;
    // TODO: Add PartyTaxScheme, Contact etc.

    public function __construct()
    {
        // Initialize composed objects
        $this->postalAddress = new Address();
        $this->partyIdentification = new PartyIdentification();
    }

    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:Party');

        $this->appendElement($document, $element, 'cbc:WebsiteURI', $this->websiteURI);

        $partyNameElement = $this->appendElement($document, $element, 'cac:PartyName', null);
        $this->appendElement($document, $partyNameElement, 'cbc:Name', $this->partyName);

        $element->appendChild($this->postalAddress->toDOMElement($document));
        $element->appendChild($this->partyIdentification->toDOMElement($document));

        return $element;
    }
}