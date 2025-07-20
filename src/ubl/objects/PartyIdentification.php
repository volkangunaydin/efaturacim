<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class PartyIdentification extends UblObject
{
    public ?string $id = null;
    public ?string $schemeID = null; // e.g., "VKN" or "TCKN"

    public function __construct(?string $id = null, ?string $schemeID = null)
    {
        $this->id = $id;
        $this->schemeID = $schemeID;
    }

    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:PartyIdentification');
        $this->appendElement($document, $element, 'cbc:ID', $this->id, ['schemeID' => $this->schemeID]);
        return $element;
    }
}