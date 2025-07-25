<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class Attachment extends UblDataType
{
    public ?EmbeddedDocumentBinaryObject $embeddedDocumentBinaryObject = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->embeddedDocumentBinaryObject = new EmbeddedDocumentBinaryObject();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        // Pass options down to the child EmbeddedDocumentBinaryObject object.
        return $this->embeddedDocumentBinaryObject->setPropertyFromOptions($k, $v, $options);
    }

    public function isEmpty(): bool
    {
        return is_null($this->embeddedDocumentBinaryObject) || $this->embeddedDocumentBinaryObject->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Attachment');

        $childElement = $this->embeddedDocumentBinaryObject->toDOMElement($document);
        if ($childElement) {
            $this->appendChild($element, $childElement);
        }

        return $element;
    }
}