<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\DateUtil;
use Efaturacim\Util\StrUtil;

class OrderReference extends UblDataType
{
    public ?string $id = null;
    public ?string $issueDate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
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
        return false;
    }
    public function isEmpty(){  
        return StrUtil::isEmpty($this->id);        
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $document->createElement('cac:OrderReference');
        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:IssueDate', DateUtil::getAsDbDate($this->issueDate));
        return $element;
    }
}