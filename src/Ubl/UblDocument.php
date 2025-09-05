<?php

namespace Efaturacim\Util\Ubl;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Ubl\Objects\UblDataTrait;
use Efaturacim\Util\Ubl\Objects\UblDataType;
use Efaturacim\Util\Ubl\Objects\UblDataTypeList;

/**
 * Abstract base class for Turkish UBL documents.
 *
 * This class provides common properties and methods for all UBL documents
 * used in the Turkish e-Invoice (e-Fatura) system. It establishes a contract
 * for subclasses to implement their specific XML generation and parsing logic.
 */
abstract class UblDocument{
    use UblDataTrait;    
    /**
     * Summary of options
     * @var Options
     */
    public $options = null;
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
     * Copy Indicator. e.g., "false"
     * @var bool
     */
    protected ?bool $copyIndicator = false;

    
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
    protected ?string $rootElementName = null;

    /**
     * Document Currency Code. e.g., "TRY", "USD", "EUR"
     * @var string
     */
    protected string $documentCurrencyCode = 'TRY';

    /**
     * Constructor.
     * Initializes the DOMDocument.
     */
    public function __construct($options=null)
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = true;
        $this->options = new Options($options);
        $this->initMe();
    }
    abstract public function initMe();
    /**
     * Sets the Profile ID for the UBL document.
     *
     * @param string|null $profileId The profile ID to set. If null, uses a default value.
     * @return static
     */
    public function setProfileId(?string $profileId = null): static
    {
        if ($profileId === null) {
            // Default profile ID based on document type
            $this->profileId = 'TICARIFATURA';
        } else {
            $this->profileId = $profileId;
        }
        return $this;
    }

    public function getProfileId(){
        return $this->profileId;
    }

    public function getId(){
        return $this->id;
    }

    
    /**
     * Sets the document ID.
     *
     * @param string $id
     * @return static
     */
    public function getUBLVersionID(){
        return $this->ublVersionId;
    }

    public function getCustomizationId(){
        return $this->customizationId;
    }
    public function getCopyIndicator(){
        return $this->copyIndicator;
    }
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
    public function setUuid($uuid=null): static{
        if(is_null($uuid)){
            $uuid = StrUtil::getGUID();
        }
        $this->uuid = $uuid;
        return $this;
    }
    public function getGUID(){
        if(is_null($this->uuid) || $this->uuid==""){
           $this->uuid = StrUtil::getGUID();     
        }
        return $this->uuid;
    }
    /**
     * Sets the issue date.
     *
     * @param string $date (Y-m-d format)
     * @return static
     */
    public function setIssueDate($date): static
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

    public function setCopyIndicator(bool $copyIndicator): static
    {
        $this->copyIndicator = $copyIndicator;
        return $this;
    }

    /**
     * Sets the document currency code.
     *
     * @param string $currencyCode (ISO 4217 format)
     * @return static
     */
    public function setDocumentCurrencyCode(string $currencyCode): static
    {
        $this->documentCurrencyCode = $currencyCode;
        return $this;
    }

    public function getDocumentCurrencyCode(){
        return $this->documentCurrencyCode;
    }

    /**
     * Sets properties from options/array data.
     * This method can be overridden by subclasses to handle specific properties.
     *
     * @param string $k The property key
     * @param mixed $v The property value
     * @param mixed $options Additional options
     * @return bool True if the property was handled, false otherwise
     */
 

    /**
     * Generates the XML representation of the UBL document.
     *
     * @return string The generated XML string.
     */
    abstract public function toXml(): string;    
    abstract public function loadFromXml($xmlString,$debug=false);

        /**
     * Helper to create and append a new element if the value is not null.
     */
    protected function appendElement($name,  $value=null,$parent = null): void
    {
        if ($name !== null && $name instanceof DOMElement) {
            ($parent ?? $this->root)->appendChild($name);
            return;
        }
        if ($value !== null) {
            if($value && $value instanceof DOMElement){
                ($parent ?? $this->root)->appendChild($value);
            }else if (is_scalar($value)){
                ($parent ?? $this->root)->appendChild($this->document->createElement($name, $value));
            }            
        }
    }
    protected function appendElementList($list,$parent=null): void{
        if(!is_null($list) && $list instanceof UblDataTypeList && !$list->isEmpty()){
            foreach($list->list as $item){
                $el = $item->toDOMElement($this->document);
                if(!is_null($el)){
                    ($parent ?? $this->root)->appendChild($el);
                }                
            }
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
     * Generates a JSON representation of the document's data.
     *
     * @return string
     */
    public function toJson(): string
    {                
        return json_encode($this->toArrayOrObject(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }    
    /**
     * Appends common UBL elements to the root node.
     */
    protected function appendCommonElements(): void{
        $this->appendElement('cbc:UBLVersionID', $this->ublVersionId);
        $this->appendElement('cbc:CustomizationID', $this->customizationId);
        $this->appendElement('cbc:ProfileID', $this->profileId);
        $this->appendElement('cbc:ID', $this->id);
        $this->appendElement('cbc:CopyIndicator', $this->copyIndicator ? 'true' : 'false');
        $this->appendElement('cbc:UUID', $this->uuid);
        $this->appendElement('cbc:IssueDate', $this->issueDate);
        $this->appendElement('cbc:IssueTime', $this->issueTime);
    }
    public function appendSignature(){

    }
    public function toArrayOrObject(){        
        $res  = array();
        $data = get_object_vars($this);
        unset($data["options"],$data["document"],$data["root"]); 
         foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArrayOrObject')) {                
                if($value instanceof UblDataType && $value->isEmpty()){
                    $value = null;
                }else{
                    $value = $value->toArrayOrObject();
                }                
            }
            if(!is_null($value)){
                $res[$key] = $value;
            }
        }
        return (object)$res;
    }
    public function getPropertyAlias($k,$v){
        return null;
    }
    public static function setNamespacesFor(&$root,$rootElementName){
        if($rootElementName=="Invoice"){
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        }
        else if($rootElementName=="DespatchAdvice"){
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        }
        else if($rootElementName=="CreditNote"){
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        }
    }

    public static function getNewXmlDocument($rootElementName="Invoice"){
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;
        $root = $document->createElement($rootElementName);
        $document->appendChild($root);
        self::setNamespacesFor($root,$rootElementName);
        return $document;      
    }      
}