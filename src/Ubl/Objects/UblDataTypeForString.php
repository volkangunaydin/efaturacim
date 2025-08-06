<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use Efaturacim\Util\Utils\String\StrUtil;

class UblDataTypeForString extends UblDataType{    
    public function setPropertyFromOptions($k,$v,$options){        
        return false;
    }
    public function isEmpty(){        
        return StrUtil::isEmpty($this->textContent);
    }
    public function toDOMElement(DOMDocument $document){   
        if ($this->isEmpty()) {
            return null;
        }
        return $this->createElement($document,$this->defaultTagName);
    }
}