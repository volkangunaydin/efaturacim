<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * Signature class for UBL documents
 * 
 * Represents a digital signature in UBL documents with signatory party information
 * and digital signature attachment reference.
 */
class Signature extends UblDataType
{
    public ?ID $id = null;
    public ?Party $signatoryParty = null;
    public ?DigitalSignatureAttachment $digitalSignatureAttachment = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->id                         = new ID();
        $this->signatoryParty             = new Party();
        $this->digitalSignatureAttachment = new DigitalSignatureAttachment();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'ID', 'signature_id']) && StrUtil::notEmpty($v)) {
            $this->id->setValue($v);
            return true;
        }
        if (in_array($k, ['schemeID', 'scheme_id']) && StrUtil::notEmpty($v)) {
            $this->id->attributes["schemeID"] = $v;
            return true;
        }
        if (in_array($k, ['signatory_party', 'signatoryParty', 'party']) && is_array($v)) {
            return $this->signatoryParty->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['digital_signature_attachment', 'digitalSignatureAttachment', 'attachment']) && is_array($v)) {
            return $this->digitalSignatureAttachment->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['uri', 'URI', 'signature_uri']) && StrUtil::notEmpty($v)) {
            $this->digitalSignatureAttachment->externalReference->uri = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->id) || $this->id->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Signature');
        
        // Add ID element
        if (!$this->id->isEmpty()) {
            $element->appendChild($this->id->toDOMElement($document));
        }
        
        // Add SignatoryParty element
        if (!$this->signatoryParty->isEmpty()) {
            $signatoryPartyElement = $document->createElement('cac:SignatoryParty');
            $this->appendChild($signatoryPartyElement, $this->signatoryParty->toDOMElement($document));
            $element->appendChild($signatoryPartyElement);
        }
        
        // Add DigitalSignatureAttachment element
        if (!$this->digitalSignatureAttachment->isEmpty()) {
            $element->appendChild($this->digitalSignatureAttachment->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * DigitalSignatureAttachment class for UBL documents
 */
class DigitalSignatureAttachment extends UblDataType
{
    public ?ExternalReference $externalReference = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->externalReference = new ExternalReference();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['external_reference', 'externalReference', 'reference']) && is_array($v)) {
            return $this->externalReference->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['uri', 'URI']) && StrUtil::notEmpty($v)) {
            $this->externalReference->uri = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->externalReference) || $this->externalReference->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:DigitalSignatureAttachment');
        $this->appendChild($element, $this->externalReference->toDOMElement($document));
        
        return $element;
    }
}

/**
 * ExternalReference class for UBL documents
 */
class ExternalReference extends UblDataType
{
    public ?string $uri = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['uri', 'URI', 'reference_uri']) && StrUtil::notEmpty($v)) {
            $this->uri = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->uri);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:ExternalReference');
        $this->appendElement($document, $element, 'cbc:URI', $this->uri);
        
        return $element;
    }
}

?>