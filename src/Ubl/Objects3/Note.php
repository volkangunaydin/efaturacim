<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Note extends UblDataType
{
    public ?string $value = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['value', 'note', 'not']) && StrUtil::notEmpty($v)) {
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

        // cbc:Note is a simple element with just a text value.
        return $document->createElement('cbc:Note', $this->value);
    }
    public static function newNote($str){
        return new Note(array("value"=>$str));
    }
}