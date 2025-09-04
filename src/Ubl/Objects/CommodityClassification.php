<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class CommodityClassification extends UblDataType
{
    public ?string $itemClassificationCode = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if (empty($this->itemClassificationCode)) {
            return null;
        }
        $element = $document->createElement('cac:CommodityClassification');
        $idElement = $document->createElement('cbc:ItemClassificationCode', $this->itemClassificationCode);
        $element->appendChild($idElement);
        return $element;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if ($k === 'itemClassificationCode' && !empty($v)) {
            $this->itemClassificationCode = $v;
            return true;
        }
        return false;
    }
} 