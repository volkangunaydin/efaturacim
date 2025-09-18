<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class DespatchDocumentReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;
    public ?ValidityPeriod $validityPeriod = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe(){
        $this->validityPeriod = new ValidityPeriod();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'siparis_no','siparisno']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }else if (in_array($k, ['issueDate', 'tarih','date']) && StrUtil::notEmpty($v)) {
            $this->issueDate = $v;
            return true;
        }
        if ($this->validityPeriod->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return is_null($this->id);
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $document->createElement('cac:DespatchDocumentReference');
        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:IssueDate', DateUtil::getAsDbDate($this->issueDate));
        $this->appendChild($element, $this->validityPeriod->toDOMElement($document));
        return $element;
    }
}