<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class PartyTaxScheme extends UblDataType
{
    public ?TaxScheme $taxScheme = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->taxScheme = new TaxScheme();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        // Pass options down to the child TaxScheme object.
        return $this->taxScheme->setPropertyFromOptions($k, $v, $options);
    }

    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){  return null; }
        $element = $document->createElement('cac:PartyTaxScheme');
        $element->appendChild($this->taxScheme->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){
        return is_null($this->taxScheme) || $this->taxScheme->isEmpty();
    }
}