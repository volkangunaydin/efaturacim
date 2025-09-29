<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * XmlSignature class for XML digital signatures
 * 
 * Represents a complete XML digital signature with SignedInfo, SignatureValue,
 * KeyInfo, and Object elements.
 */
class XmlSignature extends UblDataType
{
    public ?string $id = null;
    public ?SignedInfo $signedInfo = null;
    public ?SignatureValue $signatureValue = null;
    public ?KeyInfo $keyInfo = null;
    public ?SignatureObject $object = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->signedInfo = new SignedInfo();
        $this->signatureValue = new SignatureValue();
        $this->keyInfo = new KeyInfo();
        $this->object = new SignatureObject();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'Id', 'signature_id']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['signed_info', 'signedInfo', 'SignedInfo']) && is_array($v)) {
            return $this->signedInfo->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['signature_value', 'signatureValue', 'SignatureValue']) && is_array($v)) {
            return $this->signatureValue->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['key_info', 'keyInfo', 'KeyInfo']) && is_array($v)) {
            return $this->keyInfo->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['object', 'Object']) && is_array($v)) {
            return $this->object->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->signedInfo) || $this->signedInfo->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:Signature');
        
        // Add Id attribute if present
        if (StrUtil::notEmpty($this->id)) {
            $element->setAttribute('Id', $this->id);
        }
        
        // Add SignedInfo
        if (!$this->signedInfo->isEmpty()) {
            $element->appendChild($this->signedInfo->toDOMElement($document));
        }
        
        // Add SignatureValue
        if (!$this->signatureValue->isEmpty()) {
            $element->appendChild($this->signatureValue->toDOMElement($document));
        }
        
        // Add KeyInfo
        if (!$this->keyInfo->isEmpty()) {
            $element->appendChild($this->keyInfo->toDOMElement($document));
        }
        
        // Add Object
        if (!$this->object->isEmpty()) {
            $element->appendChild($this->object->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * SignatureValue class for XML digital signatures
 */
class SignatureValue extends UblDataType
{
    public ?string $id = null;
    public ?string $value = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'Id']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['value', 'Value', 'signature_value']) && StrUtil::notEmpty($v)) {
            $this->value = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->value);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:SignatureValue', $this->value);
        
        // Add Id attribute if present
        if (StrUtil::notEmpty($this->id)) {
            $element->setAttribute('Id', $this->id);
        }
        
        return $element;
    }
}

/**
 * KeyInfo class for XML digital signatures
 */
class KeyInfo extends UblDataType
{
    public ?X509Data $x509Data = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->x509Data = new X509Data();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['x509_data', 'x509Data', 'X509Data']) && is_array($v)) {
            return $this->x509Data->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->x509Data) || $this->x509Data->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:KeyInfo');
        $element->appendChild($this->x509Data->toDOMElement($document));
        
        return $element;
    }
}

/**
 * X509Data class for XML digital signatures
 */
class X509Data extends UblDataType
{
    public ?string $x509SubjectName = null;
    public ?string $x509Certificate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['x509_subject_name', 'x509SubjectName', 'X509SubjectName']) && StrUtil::notEmpty($v)) {
            $this->x509SubjectName = $v;
            return true;
        }
        if (in_array($k, ['x509_certificate', 'x509Certificate', 'X509Certificate']) && StrUtil::notEmpty($v)) {
            $this->x509Certificate = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->x509SubjectName) && !StrUtil::notEmpty($this->x509Certificate);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:X509Data');
        
        // Add X509SubjectName if present
        if (StrUtil::notEmpty($this->x509SubjectName)) {
            $this->appendElement($document, $element, 'ds:X509SubjectName', $this->x509SubjectName);
        }
        
        // Add X509Certificate if present
        if (StrUtil::notEmpty($this->x509Certificate)) {
            $this->appendElement($document, $element, 'ds:X509Certificate', $this->x509Certificate);
        }
        
        return $element;
    }
}

/**
 * SignatureObject class for XML digital signatures
 */
class SignatureObject extends UblDataType
{
    public ?QualifyingProperties $qualifyingProperties = null;
    public ?string $content = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->qualifyingProperties = new QualifyingProperties();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['qualifying_properties', 'qualifyingProperties', 'QualifyingProperties']) && is_array($v)) {
            return $this->qualifyingProperties->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['content', 'Content', 'object_content']) && StrUtil::notEmpty($v)) {
            $this->content = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->qualifyingProperties) || $this->qualifyingProperties->isEmpty()) && 
               !StrUtil::notEmpty($this->content);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:Object');
        
        // Add QualifyingProperties if present
        if (!$this->qualifyingProperties->isEmpty()) {
            $element->appendChild($this->qualifyingProperties->toDOMElement($document));
        }
        
        // Add raw content if present
        if (StrUtil::notEmpty($this->content)) {
            $fragment = $document->createDocumentFragment();
            $fragment->appendXML($this->content);
            $element->appendChild($fragment);
        }
        
        return $element;
    }
}

?>
