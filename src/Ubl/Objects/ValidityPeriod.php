<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;
use PDO;

class ValidityPeriod extends UblDataType
{
    public ?string $StartDate = null;
    public ?string $StartTime = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['StartDate', 'startdate', 'startDate']) && StrUtil::notEmpty($v)) {
            $this->StartDate = $v;
            return true;
        }
        if (in_array($k, ['StartTime', 'starttime', 'startTime']) && StrUtil::notEmpty($v)) {
            $this->StartTime = $v;
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return is_null($this->StartDate) || is_null($this->StartTime);
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $document->createElement('cac:ValidityPeriod');
        $this->appendElement($document, $element, 'cbc:StartDate', $this->StartDate);
        $this->appendElement($document, $element, 'cbc:StartTime', $this->StartTime);
        return $element;
    }
}