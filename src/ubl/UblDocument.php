<?php

namespace Efaturacim\Util\Ubl;

use DOMDocument;
use DOMElement;

/**
 * Abstract base class for Turkish UBL documents.
 *
 * This class provides common properties and methods for all UBL documents
 * used in the Turkish e-Invoice (e-Fatura) system. It establishes a contract
 * for subclasses to implement their specific XML generation and parsing logic.
 */
abstract class UblDocument
{
    /**
     * The underlying XML document.
     * @var DOMDocument
     */
    protected DOMDocument $document;

    /**
     * The root element of the UBL document.
     * @var DOMElement|null
     */
    protected ?DOMElement $root = null;

    /**
     * UBL Version Identifier. e.g., "2.1"
     * @var string
     */
    protected string $ublVersionId = '2.1';

    /**
     * Customization Identifier. e.g., "TR1.2"
     * @var string
     */
    protected string $customizationId = 'TR1.2';

    /**
     * Profile Identifier. e.g., "TEMELFATURA", "TICARIFATURA"
     * This will be specific to the document type.
     * @var string|null
     */
    protected ?string $profileId = null;

    /**
     * Document unique identifier.
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * Universally Unique Identifier for the document.
     * @var string|null
     */
    protected ?string $uuid = null;

    /**
     * Issue date of the document.
     * @var string|null
     */
    protected ?string $issueDate = null;

    /**
     * Issue time of the document.
     * @var string|null
     */
    protected ?string $issueTime = null;
    /**
     * The root XML element name for an Invoice.
     * @var string
     */
    protected string $rootElementName = 'Invoice';    

    /**
     * Constructor.
     * Initializes the DOMDocument.
     */
    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = true;
        $this->initMe();
    }
    abstract public function initMe();
    /**
     * Sets the Profile ID for the UBL document.
     *
     * @param string $profileId
     * @return static
     */
    public function setProfileId(string $profileId): static
    {
        $this->profileId = $profileId;
        return $this;
    }

    /**
     * Sets the document ID.
     *
     * @param string $id
     * @return static
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the UUID for the document.
     *
     * @param string $uuid
     * @return static
     */
    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Sets the issue date.
     *
     * @param string $date (Y-m-d format)
     * @return static
     */
    public function setIssueDate(string $date): static
    {
        $this->issueDate = $date;
        return $this;
    }

    /**
     * Sets the issue time.
     *
     * @param string $time (H:i:s format)
     * @return static
     */
    public function setIssueTime(string $time): static
    {
        $this->issueTime = $time;
        return $this;
    }

    /**
     * Generates the XML representation of the UBL document.
     *
     * @return string The generated XML string.
     */
    abstract public function toXml(): string;
    abstract public function toJson(): string;
    abstract public function loadFromXml($xmlString);

        /**
     * Helper to create and append a new element if the value is not null.
     */
    protected function appendElement(string $name, ?string $value, ?DOMElement $parent = null): void
    {
        if ($value !== null) {
            ($parent ?? $this->root)->appendChild($this->document->createElement($name, $value));
        }
    }

    /**
     * Helper to get a single node value from an XPath query.
     *
     * @param \DOMXPath $xpath
     * @param string $query
     * @return string|null
     */
    protected function getValueFromXpath(\DOMXPath $xpath, string $query): ?string
    {
        $nodes = $xpath->query($query);
        if ($nodes && $nodes->length > 0) {
            return $nodes->item(0)->nodeValue;
        }
        return null;
    }
    /**
     * Appends common UBL elements to the root node.
     */
    protected function appendCommonElements(): void
    {
        $this->appendElement('cbc:UBLVersionID', $this->ublVersionId);
        $this->appendElement('cbc:CustomizationID', $this->customizationId);
        $this->appendElement('cbc:ProfileID', $this->profileId);
        $this->appendElement('cbc:ID', $this->id);
        $this->appendElement('cbc:UUID', $this->uuid);
        $this->appendElement('cbc:IssueDate', $this->issueDate);
        $this->appendElement('cbc:IssueTime', $this->issueTime);
    }

}