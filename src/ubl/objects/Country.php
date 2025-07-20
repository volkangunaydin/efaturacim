<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class Country extends UblObject
{
    public ?string $identificationCode = null; // e.g., "TR"
    public ?string $name = null;               // e.g., "Türkiye"

    public function __construct(?string $name = null, ?string $identificationCode = null)
    {
        $this->name = $name;
        $this->identificationCode = $identificationCode;
    }

    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:Country');

        if ($this->identificationCode !== null) {
            $this->appendElement($document, $element, 'cbc:IdentificationCode', $this->identificationCode);
        }
        if ($this->name !== null) {
            $this->appendElement($document, $element, 'cbc:Name', $this->name);
        }
        return $element;
    }
}