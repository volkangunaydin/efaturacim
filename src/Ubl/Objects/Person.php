<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Person extends UblDataType
{
    public ?string $firstName = null;
    public ?string $familyName = null;
    public ?string $title = null;
    public ?string $middleName = null;
    public ?string $nameSuffix = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function setNameSurname($name=null,$surname=null){
        $this->firstName = $name;
        $this->familyName = $surname;
        $this->middleName = null;
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['firstName', 'ad', 'first_name']) && StrUtil::notEmpty($v)) {
            $this->firstName = $v;
            return true;
        }
        if (in_array($k, ['familyName', 'soyad', 'family_name']) && StrUtil::notEmpty($v)) {
            $this->familyName = $v;
            return true;
        }
        if (in_array($k, ['title', 'unvan_personel']) && StrUtil::notEmpty($v)) {
            $this->title = $v;
            return true;
        }
        if (in_array($k, ['middleName', 'ikinci_ad', 'middle_name']) && StrUtil::notEmpty($v)) {
            $this->middleName = $v;
            return true;
        }
        if (in_array($k, ['nameSuffix', 'ek_ad', 'name_suffix']) && StrUtil::notEmpty($v)) {
            $this->nameSuffix = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        // A person is considered empty if both first and family names are not set.
        return StrUtil::isEmpty($this->firstName) && StrUtil::isEmpty($this->familyName);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Person');

        $this->appendElement($document, $element, 'cbc:FirstName', $this->firstName);
        $this->appendElement($document, $element, 'cbc:FamilyName', $this->familyName);
        $this->appendElement($document, $element, 'cbc:Title', $this->title);
        $this->appendElement($document, $element, 'cbc:MiddleName', $this->middleName);
        $this->appendElement($document, $element, 'cbc:NameSuffix', $this->nameSuffix);

        return $element;
    }
}