<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\DateUtil;
use Efaturacim\Util\StrUtil;

class AdditionalDocumentReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;
    public ?string $documentType = null;
    public ?string $attachmentContent = null;
    public ?string $attachmentMimeCode = 'application/xml';
    public ?string $attachmentFilename = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'belge_no']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['issueDate', 'tarih', 'date']) && StrUtil::notEmpty($v)) {
            $this->issueDate = DateUtil::getAsDbDate($v);
            return true;
        }
        if (in_array($k, ['documentType', 'belge_tipi']) && StrUtil::notEmpty($v)) {
            $this->documentType = $v;
            return true;
        }
        if (in_array($k, ['attachmentContent', 'icerik', 'content']) && StrUtil::notEmpty($v)) {
            $this->attachmentContent = $v;
            return true;
        }
        if (in_array($k, ['attachmentMimeCode', 'mime_type']) && StrUtil::notEmpty($v)) {
            $this->attachmentMimeCode = $v;
            return true;
        }
        if (in_array($k, ['attachmentFilename', 'dosya_adi', 'filename']) && StrUtil::notEmpty($v)) {
            $this->attachmentFilename = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        // An AdditionalDocumentReference must have an ID to be valid.
        return StrUtil::isEmpty($this->id);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:AdditionalDocumentReference');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:IssueDate', $this->issueDate);
        $this->appendElement($document, $element, 'cbc:DocumentType', $this->documentType);

        if (StrUtil::notEmpty($this->attachmentContent)) {
            $attachmentElement = $document->createElement('cac:Attachment');
            $element->appendChild($attachmentElement);

            $attributes = ['mimeCode' => $this->attachmentMimeCode, 'encodingCode' => 'Base64'];
            if (StrUtil::notEmpty($this->attachmentFilename)) {
                $attributes['filename'] = $this->attachmentFilename;
            }

            $this->appendElement($document, $attachmentElement, 'cbc:EmbeddedDocumentBinaryObject', $this->attachmentContent, $attributes);
        }

        return $element;
    }
}