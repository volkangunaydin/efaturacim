<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;
use PDO;

class ValidityPeriod extends UblDataType
{
    public ?string $startDate = null;
    public ?string $startTime = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['startDate', 'start_date', 'StartDate']) && StrUtil::notEmpty($v)) {
            $this->startDate = $v;
            return true;
        }else if(in_array($k, ['startTime', 'start_time', 'StartTime']) && StrUtil::notEmpty($v)) {
            $this->startTime = $v;
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return StrUtil::isEmpty($this->startDate) && StrUtil::isEmpty($this->startTime);        
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $document->createElement('cac:ValidityPeriod');
        $this->appendElement($document, $element, 'cbc:StartDate', $this->startDate);
        $this->appendElement($document, $element, 'cbc:StartTime', $this->startTime);
        return $element;
    }
}