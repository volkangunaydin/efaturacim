<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\DateUtil;
use Efaturacim\Util\StrUtil;
use Efaturacim\Util\Ubl\Objects\Attachment;

class AdditionalDocumentReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;
    public ?string $documentType = null;
    public ?Attachment $attachment = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->attachment = new Attachment();
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

        // Pass other options to attachment
        if ($this->attachment->setPropertyFromOptions($k, $v, $options)) {
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

        $this->appendChild($element, $this->attachment->toDOMElement($document));

        return $element;
    }
}