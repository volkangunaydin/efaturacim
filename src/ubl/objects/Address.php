<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Address extends UblDataType
{
    public ?string $streetName = null;
    public ?string $buildingNumber = null;
    public ?string $cityName = null;
    public ?string $postalZone = null;
    public ?String $citySubdivisionName = null;
    public ?Country $country = null;

    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("sokak")) && StrUtil::notEmpty($v)){
            $this->streetName = $v;
        }else if(in_array($k,array("bina")) && StrUtil::notEmpty($v)){
            $this->buildingNumber = $v;        
        }else if(in_array($k,array("ilce")) && StrUtil::notEmpty($v)){
            $this->citySubdivisionName = $v;
        }else if(in_array($k,array("il")) && StrUtil::notEmpty($v)){
            $this->cityName = $v;
        }else if(in_array($k,array("posta_kodu")) && StrUtil::notEmpty($v)){
            $this->postalZone = $v;        
        }else{
            //\Vulcan\V::dump(array($k,$v,$options));
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:PostalAddress');
        $this->appendElement($document, $element, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $element, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $element, 'cbc:CitySubdivisionName', $this->citySubdivisionName);                
        $this->appendElement($document, $element, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $element, 'cbc:PostalZone', $this->postalZone);
        if ($this->country) {
            $element->appendChild($this->country->toDOMElement($document));
        }

        return $element;
    }
}