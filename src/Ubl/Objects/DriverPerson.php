<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class DriverPerson extends UblDataType
{
    public ?string $firstName = null;
    public ?string $familyName = null;
    public ?string $title = null;
    public ?string $nationalityID = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe(){
       
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if(in_array($k,array("firstName")) && StrUtil::notEmpty($v)){
            $this->firstName = $v;
            return true;
        }else if(in_array($k,array("familyName")) && StrUtil::notEmpty($v)){
            $this->familyName = $v;
            return true;
        }else if(in_array($k,array("title")) && StrUtil::notEmpty($v)){
            $this->title = $v;
            return true;
        }else if(in_array($k,array("nationalityID")) && StrUtil::notEmpty($v)){
            $this->nationalityID = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(){        
        return !StrUtil::notEmpty($this->firstName) && 
               !StrUtil::notEmpty($this->familyName) && 
               !StrUtil::notEmpty($this->nationalityID);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:DriverPerson');
        
        $this->appendElement($document, $element, 'cbc:FirstName', $this->firstName);
        $this->appendElement($document, $element, 'cbc:FamilyName', $this->familyName);
        $this->appendElement($document, $element, 'cbc:Title', $this->title);
        $this->appendElement($document, $element, 'cbc:NationalityID', $this->nationalityID);
        
        return $element;
    }
}