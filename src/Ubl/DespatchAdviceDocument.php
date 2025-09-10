<?php
namespace Efaturacim\Util\Ubl;

use Efaturacim\Util\Ubl\Objects\DeliveryCustomerParty;
use Efaturacim\Util\Ubl\Objects\CarrierParty;
use Efaturacim\Util\Ubl\Objects\DespatchSupplierParty;
use Efaturacim\Util\Ubl\Objects\AdditionalDocumentReference;
use Efaturacim\Util\Ubl\Objects\BuyerCustomerParty;
use Efaturacim\Util\Ubl\Objects\Delivery;
use Efaturacim\Util\Ubl\Objects\DespatchDocumentReference;
use Efaturacim\Util\Ubl\Objects\DespatchLine;
use Efaturacim\Util\Ubl\Objects\LegalMonetaryTotal;
use Efaturacim\Util\Ubl\Objects\Note;
use Efaturacim\Util\Ubl\Objects\Shipment;
use Efaturacim\Util\Ubl\Objects\OrderReference;
use Efaturacim\Util\Ubl\Objects\PaymentMeans;
use Efaturacim\Util\Ubl\Objects\PricingExchangeRate;
use Efaturacim\Util\Ubl\Objects\TaxTotal;
use Efaturacim\Util\Ubl\Objects\UblDataType;
use Efaturacim\Util\Ubl\Objects\UblDataTypeList;
use Efaturacim\Util\Ubl\Objects\UblDataTypeListForDespatchLine;
use Efaturacim\Util\Ubl\Objects\UBLExtensions;
use Efaturacim\Util\Ubl\Objects\WithholdingTaxTotal;
use Efaturacim\Util\Utils\Array\ArrayUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Utils\xml\XmlToArray;

/**
 * Represents a UBL Invoice document for the Turkish e-Invoice system.
 *
 * This class extends the base UblDocument and implements the specific
 * structure and logic required for generating a UBL Invoice XML.
 */
class DespatchAdviceDocument extends UblDocument
{

    /**
     * Invoice type code. e.g., "SATIS", "IADE"
     * @var string|null
     */

    public ?string $invoiceTypeCode = 'SATIS';

    /**
     * @var DeliveryCustomerParty
     */
    public $deliveryCustomerParty = null;

    /**
     * @var BuyerCustomerParty
     */
    public $buyerCustomerParty = null;

        /**
     * @var Shipment
     */
    public $shipment = null;

        /**
     * @var CarrierParty
     */
    public $carrierParty = null;

    /**
     * @var DespatchSupplierParty
     */
    public $despatchSupplierParty = null;

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

    /**
     * @var UblDataType
     */
    public $UBLExtensions = null;
    /**
     * @var Delivery
     */
    public $delivery = null;

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
    public $despatchLine = null;
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
    public function getIssueDate()
    {
        return $this->issueDate;
    }
    public function getIssueTime()
    {
        return $this->issueTime;
    }
    public function initMe()
    {
        $this->rootElementName = 'DespatchAdvice';
        $this->setProfileId();
        $this->setIssueDate(date('Y-m-d'));
        $this->setIssueTime(date('H:i:s'));
        $this->setDocumentCurrencyCode("TRY");
        $this->setCopyIndicator(false);
        $this->deliveryCustomerParty     = new DeliveryCustomerParty();
        $this->despatchSupplierParty     = new DespatchSupplierParty();
        $this->carrierParty              = new CarrierParty();
        $this->buyerCustomerParty          = new BuyerCustomerParty();
        $this->delivery                    = new Delivery();
        $this->orderReference              = new UblDataTypeList(OrderReference::class);
        $this->despatchDocumentReference   = new UblDataTypeList(DespatchDocumentReference::class);
        $this->note                        = new UblDataTypeList(Note::class);
        $this->shipment                    = new Shipment();
        $this->despatchLine                = new UblDataTypeListForDespatchLine(DespatchLine::class);
        $this->taxTotal                    = new TaxTotal();
        $this->withholdingTaxTotal         = new WithholdingTaxTotal();
        $this->pricingExchangeRate         = new PricingExchangeRate();
        $this->paymentMeans                = new PaymentMeans();
        $this->additionalDocumentReference = new UblDataTypeList(AdditionalDocumentReference::class);
        $this->UBLExtensions               = new UBLExtensions();
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
        $this->appendElement('cbc:DespatchAdviceTypeCode', $this->invoiceTypeCode);
        $this->appendElementList($this->additionalDocumentReference);
        $this->appendElementList($this->orderReference);
        $this->appendElementList($this->despatchDocumentReference);
        $this->appendElementList($this->note);
        $this->appendDespatchSupplierParty();
        $this->appendDeliveryCustomerParty();
        $this->appendCarrierParty();
        $this->appendBuyerCustomerParty();
        $this->appendDelivery();
        $this->appendElement('cbc:LineCountNumeric', $this->despatchLine->getCount());
        $this->appendPaymentMeans();
        $this->appendTaxTotal();
        $this->appendWithholdingTaxTotal();
        $this->appendPricingExchangeRate();
        $this->appendShipment();
        $this->appendElementList($this->despatchLine);
        return $this->document->saveXML();
    }

    public function appendLegalMonetaryTotal()
    {
        $this->appendElement('cac:LegalMonetaryTotal', $this->legalMonetaryTotal ? $this->legalMonetaryTotal->toDOMElement($this->document) : null);
    }
    public function appendDespatchSupplierParty()
    {
        $this->appendElement('cac:DespatchSupplierParty', $this->despatchSupplierParty->toDOMElement($this->document));
    }
    public function appendDeliveryCustomerParty()
    {
        $this->appendElement('cac:DeliveryCustomerParty', $this->deliveryCustomerParty->toDOMElement($this->document));
    }
    public function appendCarrierParty()
    {
        $this->appendElement('cac:CarrierParty', $this->carrierParty->toDOMElement($this->document));
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
    public function appendShipment()
    {
        $this->appendElement('cac:Shipment', $this->shipment->toDOMElement($this->document));
    }

    /**
     * getPropertyAlias array den yyukleme yaparken yasanabilecek yanlis yazilmari engellemek veya daha kolay yazim icin olusturuldu
     * ornek olarak array de satici yazildigi zaman sanki accountingSupplierParty yazilmis gibi davranir
     * @return string|null
     */
    public function getPropertyAlias($k, $v)
    {
        if (in_array($k, ["satici"])) {
            return "despatchSupplierParty";
        } else if (in_array($k, ["alici", "musteri"])) {
            return "accountingCustomerParty";
        } else if (in_array($k, ["notlar", "notes"])) {
            return "note";
        }
        return null;
    }
    /**
     * Skalar degerlerin nasil atnacagi belirtilir
     */
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ["fatura_no", "faturano", "belgeno"]) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        } else if (in_array($k, ["guid", "uid", "uuid"]) && StrUtil::notEmpty($v)) {
            $this->uuid = $v;
            return true;
        }else if (in_array($k, ["profileid", "profile_id", "ProfileID", "profileID"]) && StrUtil::notEmpty($v)) {
            $this->profileId = $v;
            return true;
        } else if (in_array($k, ["note", "notes", "Note"]) && ArrayUtil::notEmpty($v)) {
            foreach ($v as $vv) {
                $this->note->add(Note::newNote($vv));
            }
            return true;
        } else if (in_array($k, ["note", "notes", "Note"]) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        } else if (in_array($k, ["despatchLine", "satirlar", "lines", "DespatchLine"]) && ArrayUtil::notEmpty($v)) {
            if (ArrayUtil::isAssoc($v)) {
                $this->despatchLine->add(DespatchLine::newLine($v), null, null, $this->getContextArray());
            } else {
                foreach ($v as $vv) {
                    $this->despatchLine->add(DespatchLine::newLine($vv), null, null, $this->getContextArray());
                }
            }
            return true;
        } else if (in_array($k, ["note", "notes"]) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        }
        //\Vulcan\V::dump(array($k,$v,$options));
        return false;
    }

    public function loadFromXml($xmlString, $debug = false): static
    {
        $arr = XmlToArray::xmlStringToArray($xmlString, false);
        if ($arr && is_array($arr) && key_exists("DespatchAdvice", $arr)) {
            $this->loadFromArray($arr["DespatchAdvice"], 0, $debug);
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
        UblDocument::setNamespacesFor($this->root, "DespatchAdvice");
    }

    public function addToOrderList($code = null, $date = null)
    {
        if (StrUtil::notEmpty($code)) {
            $this->orderReference->add(new OrderReference(["id" => $code, "date" => $date]));
        }
    }
    public function addToDespatchList($code = null, $date = null)
    {
        if (StrUtil::notEmpty($code)) {
            $this->despatchDocumentReference->add(new DespatchDocumentReference(["id" => $code, "date" => $date]));
        }
    }
    public function addNote($noteStr)
    {
        $this->note->add(Note::newNote($noteStr));
    }
    public function addLineFromArray($props)
    {
        $this->despatchLine->add(DespatchLine::newLine($props), null, null, $this->getContextArray());
    }
    public function getContextArray()
    {
        return new Options([
            "nextLineId"           => $this->despatchLine->getCount() + 1,
            "documentCurrencyCode" => $this->documentCurrencyCode,
            "despatchAdvice"       => $this->invoiceTypeCode,
        ]);
    }

    public function getVatsAsArray()
    {
        $arr = [];
        foreach ($this->despatchLine->list as $line) {
            if ($line instanceof DespatchLine) {
                $vat = $line->getVatAsArray();
                if ($vat && is_array($vat) && count($vat) > 0 && key_exists("percent", $vat)) {
                    $percent = @$vat["percent"];
                    if (! key_exists($percent, $arr)) {
                        $arr[$percent] = [];
                    }
                    foreach ($vat as $kk => $vv) {
                        if ($kk == "percent") {
                            $arr[$percent][$kk] = $vv;
                            continue;
                        }
                        if (key_exists($kk, $arr[$percent]) && is_numeric($arr[$percent][$kk])) {
                            $arr[$percent][$kk] += $vv;
                        } else {
                            $arr[$percent][$kk] = $vv;
                        }
                    }
                }
            }
        }
        return $arr;
    }
}
