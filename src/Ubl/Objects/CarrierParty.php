<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class CarrierParty extends UblDataType
{
    public ?UblDataTypeListForPartyIdentification $partyIdentification = null;
    public ?PartyName $partyName = null;
    public ?Address $postalAddress = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
        $this->partyIdentification = new UblDataTypeListForPartyIdentification(PartyIdentification::class);
        $this->partyName = new PartyName();
        $this->postalAddress = new Address();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['partyIdentification', 'party_identification']) && StrUtil::notEmpty($v)) {
            $this->partyIdentification = $v;
            return true;
        } else if (in_array($k, array("mersis", "mersisno")) && StrUtil::notEmpty($v)) {
            $this->partyIdentification->setMersisNo($v);
        } else if (in_array($k, array("ticaret_sicil_no", "ticaret_sicil", "sicil", "ticaretsicilno", "ticari_sicil")) && StrUtil::notEmpty($v)) {
            $this->partyIdentification->setTicaretSicilNo($v);
        } else if (in_array($k, array("vkn", "tckn", "tc", "vergino", "vergi_no")) && StrUtil::notEmpty($v)) {
            $this->partyIdentification->setVkn($v);
            return true;
        } elseif (in_array($k, ['partyName', 'party_name']) && StrUtil::notEmpty($v)) {
            $this->partyName = $v;
            return true;
        } else if (in_array($k, array("sokak", "bina", "ilce", "il", "ulke")) && StrUtil::notEmpty($v)) {
            return $this->postalAddress->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        $partyIdentificationIsEmpty = is_null($this->partyIdentification) || $this->partyIdentification->isEmpty();
        $partyNameIsEmpty = is_null($this->partyName) || $this->partyName->isEmpty();
        $addressIsEmpty = is_null($this->postalAddress) || $this->postalAddress->isEmpty();

        // CarrierParty ancak tüm alt öğeler BOŞ ise boş kabul edilmeli
        return $partyIdentificationIsEmpty && $partyNameIsEmpty && $addressIsEmpty;
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:CarrierParty');
        $this->appendChild($element, $this->partyIdentification->toDOMElement($document));
        if ($this->partyName && !$this->partyName->isEmpty()) {
            $partyNameElement = $this->partyName->toDOMElement($document);
            if ($partyNameElement) {
                $this->appendChild($element, $partyNameElement);
            }
        }
        $this->appendChild($element,$this->postalAddress->toDOMElement($document));

        return $element;
    }
}
?>