<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class EmbeddedDocumentBinaryObject extends UblDataType
{
    public ?string $value = null;
    public ?string $mimeCode = 'application/xml';
    public ?string $encodingCode = 'Base64';
    public ?string $filename = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['value', 'content', 'icerik', 'attachmentContent']) && StrUtil::notEmpty($v)) {
            $this->value = $v;
            return true;
        }
        if (in_array($k, ['mimeCode', 'mime_type', 'attachmentMimeCode']) && StrUtil::notEmpty($v)) {
            $this->mimeCode = $v;
            return true;
        }
        if (in_array($k, ['encodingCode', 'encoding']) && StrUtil::notEmpty($v)) {
            $this->encodingCode = $v;
            return true;
        }
        if (in_array($k, ['filename', 'dosya_adi', 'attachmentFilename']) && StrUtil::notEmpty($v)) {
            $this->filename = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return StrUtil::isEmpty($this->value);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cbc:EmbeddedDocumentBinaryObject', $this->value);

        $attributes = ['mimeCode' => $this->mimeCode, 'encodingCode' => $this->encodingCode];
        if (StrUtil::notEmpty($this->filename)) {
            $attributes['filename'] = $this->filename;
        }

        foreach ($attributes as $attrName => $attrValue) {
            if (!is_null($attrValue)) {
                $element->setAttribute($attrName, $attrValue);
            }
        }

        return $element;
    }
}