<?php

namespace Efaturacim\Util\Ubl;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Array\ArrayUtil;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Ubl\Objects\AccountingCustomerParty;
use Efaturacim\Util\Ubl\Objects\BuyerCustomerParty;
use Efaturacim\Util\Ubl\Objects\TaxTotal;
use Efaturacim\Util\Ubl\Objects\UBLExtensions;
use Efaturacim\Util\Ubl\Objects\WithholdingTaxTotal;
use Efaturacim\Util\Ubl\Objects\AccountingSupplierParty;
use Efaturacim\Util\Ubl\Objects\DespatchDocumentReference;
use Efaturacim\Util\Ubl\Objects\PricingExchangeRate;
use Efaturacim\Util\Ubl\Objects\InvoiceLine;
use Efaturacim\Util\Ubl\Objects\PaymentMeans;
use Efaturacim\Util\Ubl\Objects\AllowanceCharge;
use Efaturacim\Util\Ubl\Objects\Delivery;
use Efaturacim\Util\Ubl\Objects\BillingReference;
use Efaturacim\Util\Ubl\Objects\AdditionalDocumentReference;
use Efaturacim\Util\Ubl\Objects\OrderReference;
use Efaturacim\Util\Ubl\Objects\LegalMonetaryTotal;
use Efaturacim\Util\Ubl\Objects\Note;
use Efaturacim\Util\Ubl\Objects\Party;
use Efaturacim\Util\Ubl\Objects\UblDataType;
use Efaturacim\Util\Ubl\Objects\UblDataTypeList;
use Efaturacim\Util\Ubl\Objects\UblDataTypeListForInvoiceLine;
use Efaturacim\Util\Utils\xml\XmlToArray;


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

    /**
     * @var AccountingCustomerParty
     */
    public $accountingCustomerParty = null;

    /**
     * @var BuyerCustomerParty
     */
    public $buyerCustomerParty = null;

    /**
     * @var AccountingSupplierParty
     */
    public $accountingSupplierParty = null;

    /**
     * @var LegalMonetaryTotal
     */
    public $legalMonetaryTotal = null;
    /**
     * @var TaxTotal
     */
    public $taxTotal = null;


    /**
     * @var WithholdingTaxTotal
     */
    public $withholdingTaxTotal = null;

    
    /**
     * @var AllowanceCharge
     */
    public $allowanceCharge = null;


    /**
     * @var PricingExchangeRate
     */
    public $pricingExchangeRate = null;

    public $paymentMeans = null;

    /**     
     * @var UblDataType
     */
    public $UBLExtensions = null;
    /**
     * @var Delivery
     */
    public $delivery = null;

    public $billingReference = null;

    public $orderReference = null;
    /**     
     * @var UblDataTypeList
     */
    public $despatchDocumentReference = null;
    /**     
     * @var UblDataTypeList
     */
    public $note = null;
    /**
     * @var UblDataTypeList
     */
    public $invoiceLine = null;
    /**
     * @var UblDataTypeList
     */
    public $additionalDocumentReference = null;

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

    }
    public function getIssueDate(){
        return $this->issueDate;
    }
    public function getIssueTime(){
        return $this->issueTime;
    }
    public function initMe()
    {
        $this->rootElementName = 'Invoice';
        $this->setProfileId('TICARIFATURA');
        $this->setIssueDate(date('Y-m-d'));
        $this->setIssueTime(date('H:i:s'));
        $this->setDocumentCurrencyCode("TRY");
        $this->setCopyIndicator(false);
        $this->billingReference = new UblDataTypeList(BillingReference::class);
        $this->accountingSupplierParty = new AccountingSupplierParty();
        $this->accountingCustomerParty = new AccountingCustomerParty();
        $this->buyerCustomerParty = new BuyerCustomerParty();
        $this->delivery = new Delivery();
        $this->orderReference = new UblDataTypeList(OrderReference::class);
        $this->despatchDocumentReference = new UblDataTypeList(DespatchDocumentReference::class);
        $this->note = new UblDataTypeList(Note::class);
        $this->invoiceLine = new UblDataTypeListForInvoiceLine(InvoiceLine::class);
        $this->allowanceCharge = new AllowanceCharge();
        $this->taxTotal = new TaxTotal();
        $this->withholdingTaxTotal = new WithholdingTaxTotal();
        $this->pricingExchangeRate = new PricingExchangeRate();
        $this->paymentMeans = new UblDataTypeList(PaymentMeans::class);
        $this->legalMonetaryTotal = new LegalMonetaryTotal();
        $this->additionalDocumentReference = new UblDataTypeList(AdditionalDocumentReference::class);
        $this->UBLExtensions = new UBLExtensions();
    }
    public function setLineCount()
    {

    }

    /**
     * Generates the XML representation of the UBL Invoice.
     *
     * @return string The generated XML string.
     */
    public function toXml(): string
    {
        $this->getGUID();        
        $this->root = $this->document->createElement($this->rootElementName);
        $this->document->appendChild($this->root);
        $this->setNamespaces();
        $this->appendElement(null, $this->UBLExtensions->toDOMElement($this->document));
        $this->appendCommonElements();
        $this->appendElement('cbc:InvoiceTypeCode', $this->invoiceTypeCode);
        $this->appendElementList($this->billingReference);
        $this->appendElement('cbc:LineCountNumeric', $this->invoiceLine->getCount());
        $this->appendElementList($this->additionalDocumentReference);
        $this->appendElementList($this->orderReference);
        $this->appendElementList($this->despatchDocumentReference);
        $this->appendElementList($this->note);
        $this->appendAccountingSupplierParty();
        $this->appendAccountingCustomerParty();
        $this->appendBuyerCustomerParty();
        $this->appendDelivery();
        $this->appendElementList($this->paymentMeans);
        $this->appendAllowanceCharge();
        $this->appendTaxTotal();
        $this->appendWithholdingTaxTotal();
        $this->appendPricingExchangeRate();
        $this->appendLegalMonetaryTotal();
        $this->appendElementList($this->invoiceLine);
        return $this->document->saveXML();
    }

    public function appendLegalMonetaryTotal()
    {
        $this->appendElement('cac:LegalMonetaryTotal', $this->legalMonetaryTotal ? $this->legalMonetaryTotal->toDOMElement($this->document) : null);
    }
    public function appendAccountingSupplierParty()
    {
        $this->appendElement('cac:AccountingSupplierParty', $this->accountingSupplierParty->toDOMElement($this->document));
    }
    public function appendAccountingCustomerParty()
    {
        $this->appendElement('cac:AccountingCustomerParty', $this->accountingCustomerParty->toDOMElement($this->document));
    }
    public function appendBuyerCustomerParty()
    {
        $this->appendElement('cac:BuyerCustomerParty', $this->buyerCustomerParty->toDOMElement($this->document));
    }
    public function appendTaxTotal()
    {
        $this->appendElement('cac:TaxTotal', $this->taxTotal->toDOMElement($this->document));
    }
    public function appendWithholdingTaxTotal()
    {
        $this->appendElement('cac:WithholdingTaxTotal', $this->withholdingTaxTotal->toDOMElement($this->document));
    }
    public function appendAllowanceCharge()
    {
        $this->appendElement('cac:AllowanceCharge', $this->allowanceCharge->toDOMElement($this->document));
    }
    public function appendPricingExchangeRate()
    {
        $this->appendElement('cac:PricingExchangeRate', $this->pricingExchangeRate->toDOMElement($this->document));
    }
    public function appendPaymentMeans()
    {
        $this->appendElement('cac:PaymentMeans', $this->paymentMeans->toDOMElement($this->document));
    }
    public function appendDelivery()
    {
        $this->appendElement('cac:Delivery', $this->delivery->toDOMElement($this->document));
    }
    public function appendBillingReference()
    {
        $this->appendElement('cac:BillingReference', $this->billingReference->toDOMElement($this->document));
    }

    /**
     * getPropertyAlias array den yyukleme yaparken yasanabilecek yanlis yazilmari engellemek veya daha kolay yazim icin olusturuldu
     * ornek olarak array de satici yazildigi zaman sanki accountingSupplierParty yazilmis gibi davranir
     * @return string|null
     */
    public function getPropertyAlias($k, $v)
    {
        if (in_array($k, array("satici"))) {
            return "accountingSupplierParty";
        } else if (in_array($k, array("alici", "musteri"))) {
            return "accountingCustomerParty";
        } else if (in_array($k, array("notlar", "notes"))) {
            return "note";
        }
        return null;
    }
    /**
     * Skalar degerlerin nasil atnacagi belirtilir
     */
    public function setPropertyFromOptions($k, $v, $options)
    {
        if (in_array($k, array("fatura_no", "faturano", "belgeno")) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        } else if (in_array($k, array("guid", "uid", "uuid")) && StrUtil::notEmpty($v)) {
            $this->uuid = $v;
            return true;
        } else if (in_array($k, array("note", "notes", "Note")) && ArrayUtil::notEmpty($v)) {
            foreach ($v as $vv) {
                $this->note->add(Note::newNote($vv));
            }
            return true;
        } else if (in_array($k, array("note", "notes", "Note")) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        } else if (in_array($k, array("invoiceLine", "satirlar", "lines", "InvoiceLine")) && ArrayUtil::notEmpty($v)) {
            if (ArrayUtil::isAssoc($v)) {
                $this->invoiceLine->add(InvoiceLine::newLine($v), null, null, $this->getContextArray());
            } else {
                foreach ($v as $vv) {
                    $this->invoiceLine->add(InvoiceLine::newLine($vv), null, null, $this->getContextArray());
                }
            }
            return true;
        } else if (in_array($k, array("note", "notes")) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        }
        //\Vulcan\V::dump(array($k,$v,$options));
        return false;
    }


    public function loadFromXml($xmlString, $debug = false): static
    {
        $arr = XmlToArray::xmlStringToArray($xmlString, false);
        if ($arr && is_array($arr) && key_exists("Invoice", $arr)) {
            $this->loadFromArray($arr["Invoice"], 0, $debug);
            //\Vulcan\V::dump(StrSerialize::serializeBase64($arr["Invoice"]["InvoiceLine"][0]));
        }
        //\Vulcan\V::dump($arr["Invoice"]["AccountingCustomerParty"]["Party"]);
        return $this;
    }

    /**
     * Sets the standard UBL namespaces on the root element.
     */
    protected function setNamespaces(): void
    {
        UblDocument::setNamespacesFor($this->root, "Invoice");
    }

    public function addToOrderList($code = null, $date = null)
    {
        if (StrUtil::notEmpty($code)) {
            $this->orderReference->add(new OrderReference(array("id" => $code, "date" => $date)));
        }
    }
    public function addToDespatchList($code = null, $date = null)
    {
        if (StrUtil::notEmpty($code)) {
            $this->despatchDocumentReference->add(new DespatchDocumentReference(array("id" => $code, "date" => $date)));
        }
    }
    public function addNote($noteStr)
    {
        $this->note->add(Note::newNote($noteStr));
    }
    public function addLineFromArray($props)
    {
        $this->invoiceLine->add(InvoiceLine::newLine($props), null, null, $this->getContextArray());
    }
    public function getContextArray()
    {
        return new Options(array(
            "nextLineId" => $this->invoiceLine->getCount() + 1,
            "documentCurrencyCode" => $this->documentCurrencyCode,
            "invoiceTypeCode" => $this->invoiceTypeCode
        ));
    }
    public function rebuildValues()
    {        
        $totalLineExtensionAmount = 0;
        $totalTaxExclusiveAmount = 0;
        $totalTaxInclusiveAmount = 0;
        $totalAllowanceTotalAmount = 0;
        $totalChargeTotalAmount = 0;
        $totalPayableAmount = 0;

        // Rebuild invoice line values and collect totals
        foreach ($this->invoiceLine->list as &$invLine) {
            if ($invLine instanceof InvoiceLine) {
                $invLine->rebuildValues();

                // Get calculated values from line context
                $lineContext = $invLine->getContextArray();
                if ($lineContext instanceof Options) {
                    $totalLineExtensionAmount += NumberUtil::asMoneyVal($lineContext->getAs("lineExtensionAmount", 0));
                    $totalTaxExclusiveAmount += NumberUtil::asMoneyVal($lineContext->getAs("taxExclusiveAmount", 0));
                    $totalTaxInclusiveAmount += NumberUtil::asMoneyVal($lineContext->getAs("taxInclusiveAmount", 0));
                    $totalAllowanceTotalAmount += NumberUtil::asMoneyVal($lineContext->getAs("allowanceTotalAmount", 0));
                    $totalChargeTotalAmount += NumberUtil::asMoneyVal($lineContext->getAs("chargeTotalAmount", 0));
                    $totalPayableAmount += NumberUtil::asMoneyVal($lineContext->getAs("payableAmount", 0));
                }
            }
        }

        // Set LegalMonetaryTotal values
        $this->legalMonetaryTotal->setCurrencyID($this->documentCurrencyCode);
        $this->legalMonetaryTotal->lineExtensionAmount->setValue($totalLineExtensionAmount);
        $this->legalMonetaryTotal->taxExclusiveAmount->setValue($totalTaxExclusiveAmount);
        $this->legalMonetaryTotal->taxInclusiveAmount->setValue($totalTaxInclusiveAmount);
        $this->legalMonetaryTotal->allowanceTotalAmount->setValue($totalAllowanceTotalAmount);
        $this->legalMonetaryTotal->chargeTotalAmount->setValue($totalChargeTotalAmount);
        $this->legalMonetaryTotal->payableAmount->setValue($totalPayableAmount);        
    }
    public function getVatsAsArray(){
        $arr = array();
        foreach($this->invoiceLine->list as $line){
            if($line instanceof InvoiceLine){
                $vat = $line->getVatAsArray();
                if($vat && is_array($vat) && count($vat)>0 && key_exists("percent",$vat)){
                    $percent = @$vat["percent"];
                    if(!key_exists($percent,$arr)){
                        $arr[$percent] = array();
                    }
                    foreach($vat as $kk=>$vv){
                        if($kk=="percent"){ 
                            $arr[$percent][$kk] = $vv;
                            continue;
                        }
                        if(key_exists($kk,$arr[$percent]) && is_numeric($arr[$percent][$kk])){
                            $arr[$percent][$kk] += $vv;
                        }else{
                            $arr[$percent][$kk] = $vv;
                        }                        
                    }
                }
            }
        }
        return $arr;
    }
    public function getPayableAmount(){
        return $this->legalMonetaryTotal->payableAmount->toNumber();
    }
    public function getLineExtensionAmount(){
        return $this->legalMonetaryTotal->lineExtensionAmount->toNumber();
    }
    public function getTaxExclusiveAmount(){
        return $this->legalMonetaryTotal->taxExclusiveAmount->toNumber();
    }
    public function getTaxInclusiveAmount(){
        return $this->legalMonetaryTotal->taxInclusiveAmount->toNumber();
    }
    public function getAllowanceTotalAmount(){
        return $this->legalMonetaryTotal->allowanceTotalAmount->toNumber();
    }
    public function getChargeTotalAmount(){
        return $this->legalMonetaryTotal->chargeTotalAmount->toNumber();
    }    
    public function getLineExtensionAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getLineExtensionAmount();
            }                
        });
    }
    public function getTaxExclusiveAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getTaxExclusiveAmount();
            }                
        });
    }
    public function getTaxInclusiveAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getTaxInclusiveAmount();
            }                
        });
    }
    public function getAllowanceTotalAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getAllowanceTotalAmount();
            }                
        });
    }   
    public function getChargeTotalAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getChargeTotalAmount();
            }                
        });
    }       
    public function getPayableAmountFromLines(){        
        return $this->invoiceLine->sum(function($line){
            if($line instanceof InvoiceLine){
                return $line->getPayableAmount();
            }                
        });
    }      
}