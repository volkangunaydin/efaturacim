<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class AccountingSupplierParty extends UblDataType
{
    public ?Party $party = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->party = new Party();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        // Pass options down to the child Party object.
        // This allows setting party details directly on the supplier party.
        return $this->party->setPropertyFromOptions($k, $v, $options);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:AccountingSupplierParty');

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
}