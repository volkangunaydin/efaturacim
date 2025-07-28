<?php

namespace Efaturacim\Util\Ubl;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\ArrayUtil;
use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;
use Efaturacim\Util\Ubl\Objects\AccountingCustomerParty;
use Efaturacim\Util\Ubl\Objects\TaxTotal;
use Efaturacim\Util\Ubl\Objects\WithholdingTaxTotal;
use Efaturacim\Util\Ubl\Objects\AccountingSupplierParty;
use Efaturacim\Util\Ubl\Objects\DespatchDocumentReference;
use Efaturacim\Util\Ubl\Objects\PricingExchangeRate;
use Efaturacim\Util\Ubl\Objects\InvoiceLine;
use Efaturacim\Util\Ubl\Objects\PaymentMeans;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\Ubl\Objects\OrderReference;
use Efaturacim\Util\Ubl\Objects\LegalMonetaryTotal;
use Efaturacim\Util\Ubl\Objects\Note;
use Efaturacim\Util\Ubl\Objects\Party;
use Efaturacim\Util\Ubl\Objects\UblDataTypeList;
use Efaturacim\Util\Ubl\Objects\UblDataTypeListForInvoiceLine;
use Efaturacim\Util\Utils\xml\XmlToArray;
use V_UBL_AccountingSupplierParty;

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
     * @var PricingExchangeRate
     */
    public $pricingExchangeRate = null;

    /**
     * @var PaymentMeans
     */
    public $paymentMeans = null;
    
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
    public function initMe()
    {
        $this->rootElementName = 'Invoice';
        $this->setProfileId('TICARIFATURA');
        $this->setIssueDate(date('Y-m-d'));
        $this->setIssueTime(date('H:i:s'));
        $this->setDocumentCurrencyCode("TRY");
        $this->setCopyIndicator(false);
        $this->accountingCustomerParty = new AccountingCustomerParty();
        $this->accountingSupplierParty = new AccountingSupplierParty();
        $this->orderReference = new UblDataTypeList(OrderReference::class);
        $this->despatchDocumentReference = new UblDataTypeList(DespatchDocumentReference::class);
        $this->note = new UblDataTypeList(Note::class);
        $this->invoiceLine = new UblDataTypeListForInvoiceLine(InvoiceLine::class);
        $this->taxTotal = new TaxTotal();
        $this->withholdingTaxTotal = new WithholdingTaxTotal();
        $this->pricingExchangeRate = new PricingExchangeRate();
        $this->paymentMeans = new PaymentMeans();
        $this->legalMonetaryTotal = new LegalMonetaryTotal();
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
        $this->rebuildValues();        
        $this->root = $this->document->createElement($this->rootElementName);
        $this->document->appendChild($this->root);
        $this->setNamespaces();
        $this->appendCommonElements();
        $this->appendElement('cbc:InvoiceTypeCode', $this->invoiceTypeCode);

        // TODO: Implement and call methods to append other required sections:
        //$this->appendSignature();
        $this->appendElementList($this->orderReference);
        $this->appendElementList($this->despatchDocumentReference);
        $this->appendElementList($this->note);
        $this->appendAccountingSupplierParty();
        $this->appendAccountingCustomerParty();
        $this->appendElement('cbc:LineCountNumeric', $this->invoiceLine->getCount());
        $this->appendPaymentMeans();        
        $this->appendTaxTotal();
        $this->appendWithholdingTaxTotal();
        $this->appendPricingExchangeRate();
        $this->appendElementList($this->invoiceLine);
        $this->appendLegalMonetaryTotal();
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
    public function appendTaxTotal()
    {
        $this->appendElement('cac:TaxTotal', $this->taxTotal->toDOMElement($this->document));
    }
    public function appendWithholdingTaxTotal()
    {
        $this->appendElement('cac:WithholdingTaxTotal', $this->withholdingTaxTotal->toDOMElement($this->document));
    }
    public function appendPricingExchangeRate()
    { 
        $this->appendElement('cac:PricingExchangeRate', $this->pricingExchangeRate->toDOMElement($this->document));
    }
    public function appendPaymentMeans()
    {
        $this->appendElement('cac:PaymentMeans', $this->paymentMeans->toDOMElement($this->document));
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
        } else if (in_array($k, array("lines", "satirlar","invoiceLine"))) {
            return "invoiceLine";
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
        } else if (in_array($k, array("guid", "uid","uuid")) && StrUtil::notEmpty($v)) {
            $this->uuid = $v;
            return true;            
        } else if (in_array($k, array("note", "notes","Note")) && ArrayUtil::notEmpty($v)) {
            foreach ($v as $vv) {
                $this->note->add(Note::newNote($vv));
            }
            return true;
        } else if (in_array($k, array("note", "notes","Note")) && StrUtil::notEmpty($v)) {                        
            $this->note->add(Note::newNote($v));
            return true;
        } else if (in_array($k, array("invoiceLine", "satirlar", "lines")) && ArrayUtil::notEmpty($v)) {            
            foreach ($v as $vv) {
                $this->invoiceLine->add(InvoiceLine::newLine($vv), null, null, $this->getContextArray());
            }
            return true;
        } else if (in_array($k, array("note", "notes")) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        }
        //\Vulcan\V::dump(array($k,$v,$options));
        return false;
    }

     
    public function loadFromXml($xmlString,$debug=false): static{
        $arr  = XmlToArray::xmlStringToArray($xmlString,false);        
        if($arr && is_array($arr) && key_exists("Invoice",$arr)){
            $this->loadFromArray($arr["Invoice"],0,$debug);
            //\Vulcan\V::dump(StrSerialize::serializeBase64($arr["Invoice"]["InvoiceLine"][0]));
        }
        //\Vulcan\V::dump($arr["Invoice"]["AccountingCustomerParty"]["Party"]);
        return $this;
    }

    /**
     * Sets the standard UBL namespaces on the root element.
     */
    protected function setNamespaces(): void{
        UblDocument::setNamespacesFor($this->root,"Invoice");
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
        $this->legalMonetaryTotal->lineExtensionAmount = $totalLineExtensionAmount;
        $this->legalMonetaryTotal->taxExclusiveAmount = $totalTaxExclusiveAmount;
        $this->legalMonetaryTotal->taxInclusiveAmount = $totalTaxInclusiveAmount;
        $this->legalMonetaryTotal->allowanceTotalAmount = $totalAllowanceTotalAmount;
        $this->legalMonetaryTotal->chargeTotalAmount = $totalChargeTotalAmount;
        $this->legalMonetaryTotal->payableAmount = $totalPayableAmount;
        $this->legalMonetaryTotal->currencyID = $this->documentCurrencyCode;
    }
}