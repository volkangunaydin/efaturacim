<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrBase64;

class Attachment extends UblDataType
{
    public ?EmbeddedDocumentBinaryObject $embeddedDocumentBinaryObject = null;

    public function __construct($options = null)
    {
        parent::__construct($options);        
    }
    public function initMe(){
        $this->embeddedDocumentBinaryObject = new EmbeddedDocumentBinaryObject();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {        
        // Pass options down to the child EmbeddedDocumentBinaryObject object.
        //return $this->embeddedDocumentBinaryObject->setPropertyFromOptions($k, $v, $options);
        return false;
    }

    public function isEmpty(): bool
    {
        return false;
        return is_null($this->embeddedDocumentBinaryObject) || $this->embeddedDocumentBinaryObject->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Attachment');
        $this->appendChild($element, $this->embeddedDocumentBinaryObject->toDOMElement($document));
        
        return $element;
    }
    public function hasEmbeddedDocumentBinaryObject(): bool
    {
        return !is_null($this->embeddedDocumentBinaryObject) && !$this->embeddedDocumentBinaryObject->isEmpty();
    }
    public function isXslt(): bool
    {
        if(!is_null($this->embeddedDocumentBinaryObject) && !$this->embeddedDocumentBinaryObject->isEmpty()){
            return $this->embeddedDocumentBinaryObject->isXslt();
        }
        return false;
    }
    public function getAsXsltString(): string
    {
        return StrBase64::decode($this->embeddedDocumentBinaryObject->textContent);
    }
}