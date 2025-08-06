<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Ubl\Objects\Attachment;

class AdditionalDocumentReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;
    public ?string $documentType = null;
    /**
     * Summary of attachment
     * @var UblDataTypeList
     */
    public  $attachment = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe(){
        $this->attachment = new UblDataTypeList(Attachment::class);
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

        return false;
    }
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true){
        //\Vulcan\V::dump($arr);
        return parent::loadFromArray($arr,$depth,$isDebug,$dieOnDebug);
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
        $this->appendElementList($document, $this->attachment,$element);

        return $element;
    }
}