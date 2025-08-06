<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


/**
 * UBL Extensions class for handling custom extensions in UBL documents.
 * 
 * This class allows adding custom data to UBL documents through the
 * ext:UBLExtensions element structure.
 */
class UBLExtension extends UblDataType{
    public function initMe(){
        $this->textContent = " ";
    }
    public function setPropertyFromOptions($k, $v, $options){
        return false;
    }

    public function isEmpty(): bool
    {
        return false;
    }
    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('ext:UBLExtension',$this->textContent);
        return $element;
    }
} 