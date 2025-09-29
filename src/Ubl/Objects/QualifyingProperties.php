<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * QualifyingProperties class for XAdES digital signatures
 * 
 * Represents the xades:QualifyingProperties element containing
 * SignedProperties and SignedDataObjectProperties.
 */
class QualifyingProperties extends UblDataType
{
    public ?string $target = null;
    public ?SignedProperties $signedProperties = null;
    public ?SignedDataObjectProperties $signedDataObjectProperties = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->signedProperties = new SignedProperties();
        $this->signedDataObjectProperties = new SignedDataObjectProperties();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['target', 'Target']) && StrUtil::notEmpty($v)) {
            $this->target = $v;
            return true;
        }
        if (in_array($k, ['signed_properties', 'signedProperties', 'SignedProperties']) && is_array($v)) {
            return $this->signedProperties->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['signed_data_object_properties', 'signedDataObjectProperties', 'SignedDataObjectProperties']) && is_array($v)) {
            return $this->signedDataObjectProperties->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->signedProperties) || $this->signedProperties->isEmpty()) && 
               (is_null($this->signedDataObjectProperties) || $this->signedDataObjectProperties->isEmpty());
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:QualifyingProperties');
        
        // Add Target attribute if present
        if (StrUtil::notEmpty($this->target)) {
            $element->setAttribute('Target', $this->target);
        }
        
        // Add SignedProperties
        if (!$this->signedProperties->isEmpty()) {
            $element->appendChild($this->signedProperties->toDOMElement($document));
        }
        
        // Add SignedDataObjectProperties
        if (!$this->signedDataObjectProperties->isEmpty()) {
            $element->appendChild($this->signedDataObjectProperties->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * SignedProperties class for XAdES digital signatures
 */
class SignedProperties extends UblDataType
{
    public ?string $id = null;
    public ?SignedSignatureProperties $signedSignatureProperties = null;
    public ?SignedDataObjectProperties $signedDataObjectProperties = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->signedSignatureProperties = new SignedSignatureProperties();
        $this->signedDataObjectProperties = new SignedDataObjectProperties();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'Id']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['signed_signature_properties', 'signedSignatureProperties', 'SignedSignatureProperties']) && is_array($v)) {
            return $this->signedSignatureProperties->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['signed_data_object_properties', 'signedDataObjectProperties', 'SignedDataObjectProperties']) && is_array($v)) {
            return $this->signedDataObjectProperties->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->signedSignatureProperties) || $this->signedSignatureProperties->isEmpty()) && 
               (is_null($this->signedDataObjectProperties) || $this->signedDataObjectProperties->isEmpty());
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:SignedProperties');
        
        // Add Id attribute if present
        if (StrUtil::notEmpty($this->id)) {
            $element->setAttribute('Id', $this->id);
        }
        
        // Add SignedSignatureProperties
        if (!$this->signedSignatureProperties->isEmpty()) {
            $element->appendChild($this->signedSignatureProperties->toDOMElement($document));
        }
        
        // Add SignedDataObjectProperties
        if (!$this->signedDataObjectProperties->isEmpty()) {
            $element->appendChild($this->signedDataObjectProperties->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * SignedSignatureProperties class for XAdES digital signatures
 */
class SignedSignatureProperties extends UblDataType
{
    public ?string $signingTime = null;
    public ?SigningCertificate $signingCertificate = null;
    public ?SignerRole $signerRole = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->signingCertificate = new SigningCertificate();
        $this->signerRole = new SignerRole();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['signing_time', 'signingTime', 'SigningTime']) && StrUtil::notEmpty($v)) {
            $this->signingTime = $v;
            return true;
        }
        if (in_array($k, ['signing_certificate', 'signingCertificate', 'SigningCertificate']) && is_array($v)) {
            return $this->signingCertificate->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['signer_role', 'signerRole', 'SignerRole']) && is_array($v)) {
            return $this->signerRole->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->signingTime) && 
               (is_null($this->signingCertificate) || $this->signingCertificate->isEmpty()) &&
               (is_null($this->signerRole) || $this->signerRole->isEmpty());
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:SignedSignatureProperties');
        
        // Add SigningTime
        if (StrUtil::notEmpty($this->signingTime)) {
            $this->appendElement($document, $element, 'xades:SigningTime', $this->signingTime);
        }
        
        // Add SigningCertificate
        if (!$this->signingCertificate->isEmpty()) {
            $element->appendChild($this->signingCertificate->toDOMElement($document));
        }
        
        // Add SignerRole
        if (!$this->signerRole->isEmpty()) {
            $element->appendChild($this->signerRole->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * SigningCertificate class for XAdES digital signatures
 */
class SigningCertificate extends UblDataType
{
    public ?Cert $cert = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->cert = new Cert();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['cert', 'Cert']) && is_array($v)) {
            return $this->cert->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->cert) || $this->cert->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:SigningCertificate');
        $element->appendChild($this->cert->toDOMElement($document));
        
        return $element;
    }
}

/**
 * Cert class for XAdES digital signatures
 */
class Cert extends UblDataType
{
    public ?CertDigest $certDigest = null;
    public ?IssuerSerial $issuerSerial = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->certDigest = new CertDigest();
        $this->issuerSerial = new IssuerSerial();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['cert_digest', 'certDigest', 'CertDigest']) && is_array($v)) {
            return $this->certDigest->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['issuer_serial', 'issuerSerial', 'IssuerSerial']) && is_array($v)) {
            return $this->issuerSerial->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->certDigest) || $this->certDigest->isEmpty()) && 
               (is_null($this->issuerSerial) || $this->issuerSerial->isEmpty());
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:Cert');
        
        // Add CertDigest
        if (!$this->certDigest->isEmpty()) {
            $element->appendChild($this->certDigest->toDOMElement($document));
        }
        
        // Add IssuerSerial
        if (!$this->issuerSerial->isEmpty()) {
            $element->appendChild($this->issuerSerial->toDOMElement($document));
        }
        
        return $element;
    }
}

/**
 * CertDigest class for XAdES digital signatures
 */
class CertDigest extends UblDataType
{
    public ?DigestMethod $digestMethod = null;
    public ?string $digestValue = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->digestMethod = new DigestMethod();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['digest_method', 'digestMethod', 'DigestMethod']) && is_array($v)) {
            return $this->digestMethod->setPropertyFromOptions($k, $v, $options);
        }
        if (in_array($k, ['digest_value', 'digestValue', 'DigestValue']) && StrUtil::notEmpty($v)) {
            $this->digestValue = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (is_null($this->digestMethod) || $this->digestMethod->isEmpty()) && 
               !StrUtil::notEmpty($this->digestValue);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:CertDigest');
        
        // Add DigestMethod
        if (!$this->digestMethod->isEmpty()) {
            $element->appendChild($this->digestMethod->toDOMElement($document));
        }
        
        // Add DigestValue
        if (StrUtil::notEmpty($this->digestValue)) {
            $this->appendElement($document, $element, 'ds:DigestValue', $this->digestValue);
        }
        
        return $element;
    }
}

/**
 * IssuerSerial class for XAdES digital signatures
 */
class IssuerSerial extends UblDataType
{
    public ?string $x509IssuerName = null;
    public ?string $x509SerialNumber = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['x509_issuer_name', 'x509IssuerName', 'X509IssuerName']) && StrUtil::notEmpty($v)) {
            $this->x509IssuerName = $v;
            return true;
        }
        if (in_array($k, ['x509_serial_number', 'x509SerialNumber', 'X509SerialNumber']) && StrUtil::notEmpty($v)) {
            $this->x509SerialNumber = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->x509IssuerName) && !StrUtil::notEmpty($this->x509SerialNumber);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:IssuerSerial');
        
        // Add X509IssuerName
        if (StrUtil::notEmpty($this->x509IssuerName)) {
            $this->appendElement($document, $element, 'ds:X509IssuerName', $this->x509IssuerName);
        }
        
        // Add X509SerialNumber
        if (StrUtil::notEmpty($this->x509SerialNumber)) {
            $this->appendElement($document, $element, 'ds:X509SerialNumber', $this->x509SerialNumber);
        }
        
        return $element;
    }
}

/**
 * SignerRole class for XAdES digital signatures
 */
class SignerRole extends UblDataType
{
    public ?ClaimedRoles $claimedRoles = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->claimedRoles = new ClaimedRoles();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['claimed_roles', 'claimedRoles', 'ClaimedRoles']) && is_array($v)) {
            return $this->claimedRoles->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->claimedRoles) || $this->claimedRoles->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:SignerRole');
        $element->appendChild($this->claimedRoles->toDOMElement($document));
        
        return $element;
    }
}

/**
 * ClaimedRoles class for XAdES digital signatures
 */
class ClaimedRoles extends UblDataType
{
    public ?UblDataTypeList $claimedRole = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->claimedRole = new UblDataTypeList(ClaimedRole::class);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['claimed_role', 'claimedRole', 'ClaimedRole']) && is_array($v)) {
            if (key_exists(0, $v)) {
                // Multiple roles
                foreach ($v as $role) {
                    $r = new ClaimedRole($role);
                    $this->claimedRole->add($r);
                }
            } else {
                // Single role
                $r = new ClaimedRole($v);
                $this->claimedRole->add($r);
            }
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->claimedRole) || $this->claimedRole->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:ClaimedRoles');
        $this->appendElementList($document, $this->claimedRole, $element);
        
        return $element;
    }
}

/**
 * ClaimedRole class for XAdES digital signatures
 */
class ClaimedRole extends UblDataType
{
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['role', 'Role', 'value']) && StrUtil::notEmpty($v)) {
            $this->textContent = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->textContent);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->createElement($document, 'xades:ClaimedRole');
    }
}

/**
 * SignedDataObjectProperties class for XAdES digital signatures
 */
class SignedDataObjectProperties extends UblDataType
{
    public ?DataObjectFormat $dataObjectFormat = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->dataObjectFormat = new DataObjectFormat();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['data_object_format', 'dataObjectFormat', 'DataObjectFormat']) && is_array($v)) {
            return $this->dataObjectFormat->setPropertyFromOptions($k, $v, $options);
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->dataObjectFormat) || $this->dataObjectFormat->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:SignedDataObjectProperties');
        $element->appendChild($this->dataObjectFormat->toDOMElement($document));
        
        return $element;
    }
}

/**
 * DataObjectFormat class for XAdES digital signatures
 */
class DataObjectFormat extends UblDataType
{
    public ?string $objectReference = null;
    public ?string $mimeType = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['object_reference', 'objectReference', 'ObjectReference']) && StrUtil::notEmpty($v)) {
            $this->objectReference = $v;
            return true;
        }
        if (in_array($k, ['mime_type', 'mimeType', 'MimeType']) && StrUtil::notEmpty($v)) {
            $this->mimeType = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->objectReference) && !StrUtil::notEmpty($this->mimeType);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('xades:DataObjectFormat');
        
        // Add ObjectReference attribute if present
        if (StrUtil::notEmpty($this->objectReference)) {
            $element->setAttribute('ObjectReference', $this->objectReference);
        }
        
        // Add MimeType
        if (StrUtil::notEmpty($this->mimeType)) {
            $this->appendElement($document, $element, 'xades:MimeType', $this->mimeType);
        }
        
        return $element;
    }
}

?>
