<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class EmbeddedDocumentBinaryObject extends UblDataType
{
    public ?string $value = null;
    public ?string $mimeCode = 'application/xml';
    public ?string $encodingCode = 'Base64';
    public ?string $characterSetCode = '';
    public ?string $filename = null;

    public function __construct($options = null)
    {
        parent::__construct($options);        
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
        return false;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }        
        $element = $this->createElement($document,'cbc:EmbeddedDocumentBinaryObject');        
        return $element;
    }
}