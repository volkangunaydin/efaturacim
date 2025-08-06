<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class LineCountNumeric extends UblDataType
{
    public ?string $value = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if ($k === 'value' && StrUtil::notEmpty($v)) {
            $this->value = $v;
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
        return $document->createElement('cbc:LineCountNumeric', $this->value);
    }

    public static function newLineCountNumeric($str){
        return new LineCountNumeric(array("value"=>$str));
    }
}