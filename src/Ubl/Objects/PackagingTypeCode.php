<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class PackagingTypeCode extends UblDataType
{
    public function toDOMElement(DOMDocument $document): ?DOMElement {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cbc:PackagingTypeCode', $this->textContent);
        return $element;
    }

    public function setPropertyFromOptions($k, $v, $options) {
        if (in_array($k, ['value', 'textContent', 'packagingTypeCode'])) {
            $this->textContent = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool {
        return empty($this->textContent);
    }
} 