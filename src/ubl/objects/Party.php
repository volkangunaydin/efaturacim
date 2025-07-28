<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;
use Efaturacim\Util\Utils\StrNameSurname;

class Party extends UblDataType
{
    public ?string $websiteURI = null;
    public ?PartyName $partyName = null;
    public ?Address $postalAddress = null;
    public ?UblDataTypeListForPartyIdentification $partyIdentification = null;
    public ?PartyTaxScheme $partyTaxScheme = null;
    public ?Contact $contact = null;
    public ?Person $person = null;

    public function __construct($options=null)
    {
        // Initialize composed objects
        parent::__construct($options);
    }
    public function initMe(){
        $this->postalAddress       = new Address();
        $this->partyIdentification = new UblDataTypeListForPartyIdentification(PartyIdentification::class);
        $this->partyTaxScheme      = new PartyTaxScheme();
        $this->contact             = new Contact();
        $this->partyName           = new PartyName();
        $this->person              = new Person();
    }
    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("party_name","musteri_adi","unvan","cari_adi","partyName")) && StrUtil::notEmpty($v)){            
            $this->partyName->setName($v);            
            return true;
        } else if (in_array($k, array("mersis", "mersisno")) && StrUtil::notEmpty($v)) {                        
            $this->partyIdentification->setMersisNo($v);
        } else if (in_array($k, array("ticaret_sicil_no", "ticaret_sicil","sicil","ticaretsicilno","ticari_sicil")) && StrUtil::notEmpty($v)) {                                    
            $this->partyIdentification->setTicaretSicilNo($v);
        }else if(in_array($k,array("vkn","tckn","tc","vergino","vergi_no")) && StrUtil::notEmpty($v)){
            $this->partyIdentification->setVkn($v);
            return true;
        }else if(in_array($k,array("vergi_dairesi","vergidairesi")) && StrUtil::notEmpty($v)){
            return $this->partyTaxScheme->setPropertyFromOptions($k, $v, $options);
        }else if(in_array($k,array("sokak","bina","ilce","il","ulke")) && StrUtil::notEmpty($v)){
            return $this->postalAddress->setPropertyFromOptions($k, $v, $options);
        }else if(in_array($k,array("telefon","tel", "fax", "email", "eposta")) && StrUtil::notEmpty($v)){
            return $this->contact->setPropertyFromOptions($k, $v, $options);
        }else if(in_array($k,array("web","www","url")) && StrUtil::notEmpty($v)){
            $this->websiteURI = $v;
            return true;
        }else{
            if ($this->person->setPropertyFromOptions($k, $v, $options)) {
                return true;
            }
            //\Vulcan\V::dump(array($k,$v,$options));
        }
        return false;
    }
    public function getPartyName(){
        return $this->partyName->getName();
    }
    public function getVknOrTckn(){
        $key1 = $this->partyIdentification->getKeyForSchemeID("TCKN");        
        if(!is_null($key1) && strlen("".$this->partyIdentification[$key1]->getValue())>0 ){
            return $this->partyIdentification[$key1]->getValue();    
        }
        $key2 = $this->partyIdentification->getKeyForSchemeID("VKN");
        if(!is_null($key2) && strlen("".$this->partyIdentification[$key2]->getValue())>0 ){
            return $this->partyIdentification[$key2]->getValue();    
        }
        return null;
    }
    public function isRealPerson(){
        $key = $this->partyIdentification->getKeyForSchemeID("TCKN");
        if(!is_null($key)){
            return true;
        }
        return false;
    }
    public function toDOMElement(DOMDocument $document)    {
        if($this->isEmpty()){ return null;  }        
        $element = $document->createElement('cac:Party');        
        if(StrUtil::notEmpty($this->websiteURI)){
            $this->appendElement($document, $element, 'cbc:WebsiteURI', $this->websiteURI);
        }                
        if(!$this->partyName->isEmpty()){            
            $element->appendChild($this->partyName->toDOMElement($document));
        }                
        $this->appendChild($element,$this->partyIdentification->toDOMElement($document));
        $this->appendChild($element,$this->postalAddress->toDOMElement($document));
        $this->appendChild($element,$this->partyTaxScheme->toDOMElement($document));
        $this->appendChild($element,$this->contact->toDOMElement($document));
        if($this->isRealPerson() && $this->person->isEmpty()){
            $r = StrNameSurname::getAsResult($this->getPartyName());
            if($r->isOK()){
                $this->person->setNameSurname($r->getAttribute("name"),$r->getAttribute("surname"));
            }            
        }
        $this->appendChild($element,$this->person->toDOMElement($document));
        return $element;
    }
    public function isEmpty(){        
        if(is_null($this->partyIdentification) || $this->partyIdentification->isEmpty() ){
            return true;
        }
        return false;
    }
}