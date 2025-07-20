<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class Contact extends UblDataType
{
    public ?string $telephone = null;
    public ?string $telefax = null;
    public ?string $electronicMail = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['telephone', 'telefon', 'tel']) && StrUtil::notEmpty($v)) {
            $this->telephone = $v;
            return true;
        }
        if (in_array($k, ['telefax', 'fax']) && StrUtil::notEmpty($v)) {
            $this->telefax = $v;
            return true;
        }
        if (in_array($k, ['electronicMail', 'email', 'eposta']) && StrUtil::notEmpty($v)) {
            $this->electronicMail = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return StrUtil::isEmpty($this->telephone) && StrUtil::isEmpty($this->telefax) && StrUtil::isEmpty($this->electronicMail);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) { return null; }
        $element = $document->createElement('cac:Contact');
        $this->appendElement($document, $element, 'cbc:Telephone', $this->telephone);
        $this->appendElement($document, $element, 'cbc:Telefax', $this->telefax);
        $this->appendElement($document, $element, 'cbc:ElectronicMail', $this->electronicMail);
        return $element;
    }
}