<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;

class Party extends UblDataType
{
    public ?string $websiteURI = null;
    public ?PartyName $partyName = null;
    public ?Address $postalAddress = null;
    public ?UblDataTypeList $partyIdentification = null;
    public ?PartyTaxScheme $partyTaxScheme = null;
    public ?Contact $contact = null;

    public function __construct($options=null)
    {
        // Initialize composed objects
        parent::__construct($options);
        $this->postalAddress       = new Address();
        $this->partyIdentification = new UblDataTypeList(PartyIdentification::class);
        $this->partyTaxScheme      = new PartyTaxScheme();
        $this->contact             = new Contact();
        $this->partyName           = new PartyName();
        if(!is_null($this->options)){
            $this->loadFromOptions($this->options);
        }        
    }
    public function setPropertyFromOptions($k,$v,$options){
        if(in_array($k,array("name","unvan","cari_adi","partyName")) && StrUtil::notEmpty($v)){
            $this->partyName->name->textContent = $v;
            return true;
        }else if(in_array($k,array("vkn","tckn","tc","vergino","vergi_no")) && StrUtil::notEmpty($v)){
            if(strlen($v)==11){
                $this->partyIdentification->setValue("".$v,"TCKN");
            }else{
                $this->partyIdentification->setValue("".$v,"VKN");
            }
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
            //\Vulcan\V::dump(array($k,$v,$options));
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
        return $element;
    }
    public function isEmpty(){        
        if(is_null($this->partyIdentification) || $this->partyIdentification->isEmpty() ){
            return true;
        }
        return false;
    }
}