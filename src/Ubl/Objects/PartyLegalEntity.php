<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class PartyLegalEntity extends UblDataType
{
    public ?string $registrationName = null;
    public ?string $companyID = null;
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function setPropertyFromOptions($k, $v, $options)
    {
        return false;
    }

    public function isEmpty()
    {
        return is_null($this->registrationName) && is_null($this->companyID);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:PartyLegalEntity');

        $this->appendElement($document, $element, 'cbc:RegistrationName', $this->registrationName);
        $this->appendElement($document, $element, 'cbc:CompanyID', $this->companyID);


        return $element;
    }
}