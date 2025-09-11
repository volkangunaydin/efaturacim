<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class CarrierParty extends UblDataType
{
    public ?PartyIdentification $partyIdentification = null;
    public ?PartyName $partyName = null;
    public ?Address $address = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
        $this->partyIdentification = new PartyIdentification();
        $this->partyName = new PartyName();
        $this->address = new Address();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['partyIdentification', 'party_identification']) && StrUtil::notEmpty($v)) {
            $this->partyIdentification = $v;
            return true;
        }
        if (in_array($k, ['partyName', 'party_name']) && StrUtil::notEmpty($v)) {
            $this->partyName = $v;
            return true;
        }
        if (in_array($k, ['postalAddress', 'address']) && StrUtil::notEmpty($v)) {
            $this->address = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        $partyIdentificationIsEmpty = is_null($this->partyIdentification) || $this->partyIdentification->isEmpty();
        $partyNameIsEmpty = is_null($this->partyName) || $this->partyName->isEmpty();
        $addressIsEmpty = is_null($this->address) || $this->address->isEmpty();
        
        // CarrierParty ancak tüm alt öğeler BOŞ ise boş kabul edilmeli
        return $partyIdentificationIsEmpty && $partyNameIsEmpty && $addressIsEmpty;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        
        $element = $document->createElement('cac:CarrierParty');
        
        if ($this->partyIdentification && !$this->partyIdentification->isEmpty()) {
            $partyIdentificationElement = $this->partyIdentification->toDOMElement($document);
            if ($partyIdentificationElement) {
                $this->appendChild($element, $partyIdentificationElement);
            }
        }
        
        if ($this->partyName && !$this->partyName->isEmpty()) {
            $partyNameElement = $this->partyName->toDOMElement($document);
            if ($partyNameElement) {
                $this->appendChild($element, $partyNameElement);
            }
        }
        
        if ($this->address && !$this->address->isEmpty()) {
            $addressElement = $this->address->toDOMElement($document);
            if ($addressElement) {
                $this->appendChild($element, $addressElement);
            }
        }
        
        return $element;
    }
}
?>