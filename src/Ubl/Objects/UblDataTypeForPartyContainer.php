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
}