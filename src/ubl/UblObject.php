<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

/**
 * Abstract base class for all UBL data objects.
 *
 * This class provides a common structure and helper methods for objects
 * that represent a part of a UBL document, such as Party, Address, or InvoiceLine.
 */
abstract class UblObject
{
    /**
     * Converts the object to a DOMElement.
     *
     * @param DOMDocument $document The parent DOMDocument.
     * @return DOMElement The generated DOMElement representing this object.
     */
    abstract public function toDOMElement(DOMDocument $document): DOMElement;

    /**
     * Helper to create and append a new element with an optional value and attributes.
     *
     * @param DOMDocument $document
     * @param DOMElement $parent
     * @param string $name
     * @param string|null $value
     * @param array $attributes
     * @return DOMElement
     */
    protected function appendElement(DOMDocument $document, DOMElement $parent, string $name, ?string $value, array $attributes = []): DOMElement
    {
        $element = $document->createElement($name, $value);

        foreach ($attributes as $attrName => $attrValue) {
            $element->setAttribute($attrName, $attrValue);
        }

        $parent->appendChild($element);
        return $element;
    }
}