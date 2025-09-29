<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * ExtensionContent class for UBL documents
 * 
 * Represents the ExtensionContent element that can contain various
 * extension data including SignedInfo for digital signatures.
 */
class ExtensionContent extends UblDataType
{
    public ?XmlSignature $signature = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->signature = new XmlSignature();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['xml_signature', 'xmlSignature', 'XmlSignature', 'signature', 'Signature']) && is_array($v)) {
            return $this->signature->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['content', 'text', 'value']) && StrUtil::notEmpty($v)) {
            $this->textContent = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->signature) || $this->signature->isEmpty()) && 
               !StrUtil::notEmpty($this->textContent);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ext:ExtensionContent');
        
        // Add Signature if present
        if (!$this->signature->isEmpty()) {
            $element->appendChild($this->signature->toDOMElement($document));
        }
        
        // Add text content if present
        if (StrUtil::notEmpty($this->textContent)) {
            $element->textContent = $this->textContent;
        }
        
        return $element;
    }
}

?>
