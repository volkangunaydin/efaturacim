<?php

namespace Efaturacim\Util\Ubl;

use DOMElement;

/**
 * Represents a UBL Invoice document for the Turkish e-Invoice system.
 *
 * This class extends the base UblDocument and implements the specific
 * structure and logic required for generating a UBL Invoice XML.
 */
class InvoiceDocument extends UblDocument
{


    /**
     * Invoice type code. e.g., "SATIS", "IADE"
     * @var string|null
     */
    public ?string $invoiceTypeCode = 'SATIS';

    // TODO: Add properties for invoice lines, parties, totals etc.
    // public array $invoiceLines = [];
    // public array $supplierParty = [];
    // public array $customerParty = [];
    // public array $legalMonetaryTotal = [];

    /**
     * Constructor.
     * Sets the default profile ID for a commercial invoice.
     */
    public function __construct()
    {
        parent::__construct();
        // Default profile for a commercial invoice. Can be overridden for "TEMELFATURA".
        $this->profileId = 'TICARIFATURA';
    }

    /**
     * Generates the XML representation of the UBL Invoice.
     *
     * @return string The generated XML string.
     */
    public function toXml(): string
    {
        $this->root = $this->document->createElement($this->rootElementName);
        $this->document->appendChild($this->root);

        $this->setNamespaces();
        $this->appendCommonElements();

        $this->appendElement('cbc:InvoiceTypeCode', $this->invoiceTypeCode);

        // TODO: Implement and call methods to append other required sections:
        // $this->appendSignature();
        // $this->appendAccountingSupplierParty();
        // $this->appendAccountingCustomerParty();
        // $this->appendTaxTotal();
        // $this->appendLegalMonetaryTotal();
        // $this->appendInvoiceLines();

        return $this->document->saveXML();
    }

    /**
     * Generates a JSON representation of the document's data.
     *
     * @return string
     */
    public function toJson(): string
    {
        $data = get_object_vars($this);
        unset($data['document'], $data['root']); // Exclude DOM objects

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Loads document properties from an XML string.
     *
     * @param string $xmlString The UBL XML content.
     * @return static
     * @throws \Exception if the XML is invalid or empty.
     */
    public function loadFromXml($xmlString): static
    {
        if (empty($xmlString)) {
            throw new \Exception('XML string cannot be empty.');
        }

        if (!@$this->document->loadXML($xmlString)) {
            throw new \Exception('Failed to parse XML string.');
        }

        $this->root = $this->document->documentElement;

        $xpath = new \DOMXPath($this->document);
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

        // Populate common properties from the base class
        $this->ublVersionId    = $this->getValueFromXpath($xpath, '/Invoice/cbc:UBLVersionID');
        $this->customizationId = $this->getValueFromXpath($xpath, '/Invoice/cbc:CustomizationID');
        $this->profileId       = $this->getValueFromXpath($xpath, '/Invoice/cbc:ProfileID');
        $this->id              = $this->getValueFromXpath($xpath, '/Invoice/cbc:ID');
        $this->uuid            = $this->getValueFromXpath($xpath, '/Invoice/cbc:UUID');
        $this->issueDate       = $this->getValueFromXpath($xpath, '/Invoice/cbc:IssueDate');
        $this->issueTime       = $this->getValueFromXpath($xpath, '/Invoice/cbc:IssueTime');

        // Populate Invoice-specific properties
        $this->invoiceTypeCode = $this->getValueFromXpath($xpath, '/Invoice/cbc:InvoiceTypeCode');

        // TODO: Implement and call methods to parse other required sections:
        // $this->parseAccountingSupplierParty($xpath);
        // $this->parseAccountingCustomerParty($xpath);
        // $this->parseTaxTotal($xpath);
        // $this->parseLegalMonetaryTotal($xpath);
        // $this->parseInvoiceLines($xpath);

        return $this;
    }

    /**
     * Sets the standard UBL namespaces on the root element.
     */
    protected function setNamespaces(): void
    {
        $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
        $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
    }


}