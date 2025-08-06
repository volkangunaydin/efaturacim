<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class SellersItemIdentification extends UblDataType
{
    public ?string $id = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if (empty($this->id)) {
            return null;
        }
        $element = $document->createElement('cac:SellersItemIdentification');
        $idElement = $document->createElement('cbc:ID', $this->id);
        $element->appendChild($idElement);
        return $element;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if ($k === 'id' && !empty($v)) {
            $this->id = $v;
            return true;
        }
        return false;
    }
} 