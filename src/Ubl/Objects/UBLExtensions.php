<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;



class UBLExtensions extends UblDataType
{
    
    /**
     * Summary of UBLExtension
     * @var UblDataTypeList
     */
    public  $UBLExtension = null;

    
    public function initMe(){
        $this->UBLExtension = new UblDataTypeList(UBLExtension::class);
    }    

    public function setPropertyFromOptions($k, $v, $options): bool
    {                
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

        $element = $document->createElement('ext:UBLExtensions');        
        $this->appendElementList($document, $this->UBLExtension,$element);
        return $element;
    }
} 