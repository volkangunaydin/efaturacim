<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * Reference class for XML digital signatures
 */
class Reference extends UblDataType
{
    public ?string $uri = null;
    public ?string $id = null;
    public ?string $type = null;
    public ?Transforms $transforms = null;
    public ?DigestMethod $digestMethod = null;
    public ?string $digestValue = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->transforms = new Transforms();
        $this->digestMethod = new DigestMethod();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['uri', 'URI']) && StrUtil::notEmpty($v)) {
            $this->uri = $v;
            return true;
        }
        if (in_array($k, ['id', 'Id']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['type', 'Type']) && StrUtil::notEmpty($v)) {
            $this->type = $v;
            return true;
        }
        if (in_array($k, ['transforms', 'transform']) && is_array($v)) {
            return $this->transforms->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['digest_method', 'digestMethod']) && is_array($v)) {
            return $this->digestMethod->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['digest_value', 'digestValue', 'DigestValue']) && StrUtil::notEmpty($v)) {
            $this->digestValue = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->uri) || !StrUtil::notEmpty($this->digestValue);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:Reference');
        
        // Add URI attribute
        if (StrUtil::notEmpty($this->uri)) {
            $element->setAttribute('URI', $this->uri);
        }
        
        // Add Id attribute if present
        if (StrUtil::notEmpty($this->id)) {
            $element->setAttribute('Id', $this->id);
        }
        
        // Add Type attribute if present
        if (StrUtil::notEmpty($this->type)) {
            $element->setAttribute('Type', $this->type);
        }
        
        // Add Transforms if any
        if (!$this->transforms->isEmpty()) {
            $element->appendChild($this->transforms->toDOMElement($document));
        }
        
        // Add DigestMethod
        if (!$this->digestMethod->isEmpty()) {
            $element->appendChild($this->digestMethod->toDOMElement($document));
        }
        
        // Add DigestValue
        if (StrUtil::notEmpty($this->digestValue)) {
            $this->appendElement($document, $element, 'ds:DigestValue', $this->digestValue);
        }
        
        return $element;
    }
}



?>