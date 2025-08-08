<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class InvoiceDocumentReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;
    public ?string $documentTypeCode = null;

    public function setPropertyFromOptions($k, $v, $options)
    {
        if (in_array($k, ['id', 'line_id', 'sira_no']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['issueDate', 'IssueDate', 'ISSUEDATE']) && StrUtil::notEmpty($v)) {
            $this->issueDate = $v;
            return true;
        }
        if (in_array($k, ['documentTypeCode', 'DocumentTypeCode', 'DOCUMENTYPECODE']) && StrUtil::notEmpty($v)) {
            $this->documentTypeCode = $v;
            return true;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document)
    {
        $element = $this->createElement($document, 'cac:InvoiceDocumentReference');
        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:IssueDate', $this->issueDate);
        $this->appendElement($document, $element, 'cbc:DocumentTypeCode', $this->documentTypeCode);
        return $element;
    }
    public function isEmpty()
    {
        if (is_null($this->id)) {
            return true;
        }
        return false;
    }
}