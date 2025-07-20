<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class TaxScheme extends UblDataType
{
    public ?string $name = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['name', 'vergi_dairesi', 'vergidairesi']) && StrUtil::notEmpty($v)) {
            $this->name = $v;
            return true;
        }
        return false;
    }
    public function isEmpty(){  
        return StrUtil::isEmpty($this->name);        
    }
    public function toDOMElement(DOMDocument $document){
        if($this->isEmpty()){ return null; }
        $element = $document->createElement('cac:TaxScheme');
        $this->appendElement($document, $element, 'cbc:Name', $this->name);
        return $element;
    }
}