<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * SignedInfo class for XML digital signatures
 * 
 * Represents the SignedInfo element in XML digital signatures containing
 * canonicalization method, signature method, and references.
 */
class SignedInfo extends UblDataType
{
    public ?CanonicalizationMethod $canonicalizationMethod = null;
    public ?SignatureMethod $signatureMethod = null;
    public ?UblDataTypeList $reference = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->canonicalizationMethod = new CanonicalizationMethod();
        $this->signatureMethod = new SignatureMethod();
        $this->reference = new UblDataTypeList(Reference::class);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['canonicalization_method', 'canonicalizationMethod']) && is_array($v)) {
            return $this->canonicalizationMethod->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['signature_method', 'signatureMethod']) && is_array($v)) {
            return $this->signatureMethod->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['references', 'reference']) && is_array($v)) {
            if (key_exists(0, $v)) {
                // Multiple references
                foreach ($v as $reference) {
                    $ref = new Reference($reference);
                    $this->reference->add($ref);
                }
            } else {
                // Single reference
                $ref = new Reference($v);
                $this->reference->add($ref);
            }
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->reference) || $this->reference->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:SignedInfo');
        
        // Add CanonicalizationMethod
        if (!$this->canonicalizationMethod->isEmpty()) {
            $element->appendChild($this->canonicalizationMethod->toDOMElement($document));
        }
        
        // Add SignatureMethod
        if (!$this->signatureMethod->isEmpty()) {
            $element->appendChild($this->signatureMethod->toDOMElement($document));
        }
        
        // Add References
        $this->appendElementList($document, $this->reference, $element);
        
        return $element;
    }
}

/**
 * CanonicalizationMethod class for XML digital signatures
 */
class CanonicalizationMethod extends UblDataType
{
    public ?string $algorithm = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (is_null($this->algorithm)) {
            $this->algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['algorithm', 'Algorithm']) && StrUtil::notEmpty($v)) {
            $this->algorithm = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->algorithm);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:CanonicalizationMethod');
        $element->setAttribute('Algorithm', $this->algorithm);
        
        return $element;
    }
}

/**
 * SignatureMethod class for XML digital signatures
 */
class SignatureMethod extends UblDataType
{
    public ?string $algorithm = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (is_null($this->algorithm)) {
            $this->algorithm = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['algorithm', 'Algorithm']) && StrUtil::notEmpty($v)) {
            $this->algorithm = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->algorithm);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:SignatureMethod');
        $element->setAttribute('Algorithm', $this->algorithm);
        
        return $element;
    }
}


?>