<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

/**
 * UBL Extensions class for handling custom extensions in UBL documents.
 * 
 * This class allows adding custom data to UBL documents through the
 * ext:UBLExtensions element structure.
 */
class UBLExtensions extends UblDataType
{
    /**
     * Array of extension elements
     * @var array
     */
    public array $extensions = [];

    /**
     * Constructor
     * 
     * @param mixed $options Options to initialize the extensions
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Set property from options
     * 
     * @param string $k Property key
     * @param mixed $v Property value
     * @param mixed $options Additional options
     * @return bool True if property was set successfully
     */
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['extensions', 'extension', 'ext']) && is_array($v)) {
            $this->extensions = $v;
            return true;
        }
        
        if (StrUtil::notEmpty($v)) {
            // Allow adding individual extension elements
            $this->extensions[] = [
                'key' => $k,
                'value' => $v
            ];
            return true;
        }
        
        return false;
    }

    /**
     * Add an extension element
     * 
     * @param string $key Extension key
     * @param mixed $value Extension value
     * @return self
     */
    public function addExtension(string $key, $value): self
    {
        $this->extensions[] = [
            'key' => $key,
            'value' => $value
        ];
        return $this;
    }

    /**
     * Check if extensions are empty
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->extensions);
    }

    /**
     * Convert to DOM element
     * 
     * @param DOMDocument $document
     * @return DOMElement|null
     */
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ext:UBLExtensions');

        foreach ($this->extensions as $extension) {
            $extensionElement = $document->createElement('ext:UBLExtension');
            
            // Create the extension content element
            $contentElement = $document->createElement('ext:ExtensionContent');
            
            if (is_array($extension['value'])) {
                // If value is an array, create nested structure
                $this->createNestedElements($document, $contentElement, $extension['value']);
            } else {
                // Simple value
                $contentElement->textContent = (string)$extension['value'];
            }
            
            $extensionElement->appendChild($contentElement);
            $element->appendChild($extensionElement);
        }

        return $element;
    }

    /**
     * Create nested elements from array data
     * 
     * @param DOMDocument $document
     * @param DOMElement $parent
     * @param array $data
     */
    private function createNestedElements(DOMDocument $document, DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $childElement = $document->createElement($key);
                $this->createNestedElements($document, $childElement, $value);
                $parent->appendChild($childElement);
            } else {
                $childElement = $document->createElement($key, (string)$value);
                $parent->appendChild($childElement);
            }
        }
    }

    /**
     * Create a new UBLExtensions instance with data
     * 
     * @param array $extensions Array of extensions
     * @return self
     */
    public static function newExtensions(array $extensions = []): self
    {
        return new self(['extensions' => $extensions]);
    }
} 