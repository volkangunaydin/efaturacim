<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Address extends UblDataType
{
    public ?string $streetName = null;
    public ?string $buildingName = null;
    public ?string $buildingNumber = null;
    public ?string $cityName = null;
    public ?string $postalZone = null;
    public ?String $citySubdivisionName = null;
    public ?String $region = null;
    public ?String $district = null;
    public ?Country $country = null;
    public function __construct($options = null){
        parent::__construct($options);     
    }
    public function initMe(){
        $this->country = new Country();
    }
    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("sokak")) && StrUtil::notEmpty($v)){
            $this->streetName = $v;
        }else if(in_array($k,array("bina")) && StrUtil::notEmpty($v)){
            $this->buildingName = $v;        
        }else if(in_array($k,array("bina_no")) && StrUtil::notEmpty($v)){
            $this->buildingNumber = $v;        
        }else if(in_array($k,array("ilce")) && StrUtil::notEmpty($v)){
            $this->citySubdivisionName = $v;
        }else if(in_array($k,array("il")) && StrUtil::notEmpty($v)){
            $this->cityName = $v;
        }else if(in_array($k,array("posta_kodu")) && StrUtil::notEmpty($v)){
            $this->postalZone = $v;  
        }else if(in_array($k,array("bolge")) && StrUtil::notEmpty($v)){
                $this->region = $v;          
        }else if(in_array($k,array("semt")) && StrUtil::notEmpty($v)){
            $this->district = $v;          
        }else{
            //\Vulcan\V::dump(array($k,$v,$options));
        }
        return false;
    }

    public function isEmpty(){
       return !StrUtil::notEmpty($this->cityName);
    }

    public function toDOMElement(DOMDocument $document): DOMElement
    {
        $element = $document->createElement('cac:PostalAddress');
        $this->appendElement($document, $element, 'cbc:StreetName', $this->streetName);
        $this->appendElement($document, $element, 'cbc:BuildingName', $this->buildingName);
        $this->appendElement($document, $element, 'cbc:BuildingNumber', $this->buildingNumber);
        $this->appendElement($document, $element, 'cbc:CitySubdivisionName', $this->citySubdivisionName);                
        $this->appendElement($document, $element, 'cbc:CityName', $this->cityName);
        $this->appendElement($document, $element, 'cbc:PostalZone', $this->postalZone);
        $this->appendElement($document, $element, 'cbc:Region', $this->region);
        $this->appendElement($document, $element, 'cbc:District', $this->district);
        //\Vulcan\V::dump($this->country);
        if($this->country) {                        
            $this->appendChild($element,$this->country->toDOMElement($document));
        }

        return $element;
    }
}