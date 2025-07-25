<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\CastUtil;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;

class InvoiceLine extends UblDataType
{
    public ?string $id = null;    
    public ?float $invoicedQuantity = null;
    public ?string $invoicedQuantityUnitCode = 'C62'; // Default to "unit"
    public ?float $lineExtensionAmount = null;
    public ?string $lineExtensionAmountCurrencyID = 'TRY';

    /**     
     * @var UblDataTypeList
     */
    public $note = null;    
    /**
     * @var UblDataTypeList
     */
    public $allowanceCharge;
    /**
     * @var UblDataTypeList
     */
    public $withholdingTaxTotal;

    public ?TaxTotal $taxTotal = null;
    public ?Item $item = null;
    public ?Price $price = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->allowanceCharge = new UblDataTypeList(AllowanceCharge::class);
        $this->withholdingTaxTotal = new UblDataTypeList(WithholdingTaxTotal::class);
        $this->taxTotal = new TaxTotal();
        $this->item = new Item();
        $this->price = new Price();
        $this->note  = new UblDataTypeList(Note::class);

        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function addAllowanceCharge(array $options): self
    {
        $this->allowanceCharge->add(new AllowanceCharge($options));
        return $this;
    }

    public function addWithholdingTaxTotal(array $options): self
    {
        $this->withholdingTaxTotal->add(new WithholdingTaxTotal($options));
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['id', 'line_id', 'sira_no']) && StrUtil::notEmpty($v)) {
            $this->id = $v;
            return true;
        }
        if (in_array($k, ['price', 'fiyat', 'birim_fiyat']) && NumberUtil::isPositiveNumber($v)) {
            $this->price = new Price(array('priceAmount'=>$v));
            return true;
        }
        if (in_array($k, ['note', 'not']) && StrUtil::notEmpty($v)) {
            $this->note->add(Note::newNote($v));
            return true;
        }
        if (in_array($k, ['invoicedQuantity', 'quantity', 'miktar']) && is_numeric($v)) {
            $this->invoicedQuantity = (float)$v;
            return true;
        }
        if (in_array($k, ['invoicedQuantityUnitCode', 'unitCode', 'birim_kodu']) && StrUtil::notEmpty($v)) {
            $this->invoicedQuantityUnitCode = $v;
            return true;
        }
        if (in_array($k, ['lineExtensionAmount', 'line_amount', 'satir_tutari']) && is_numeric($v)) {
            $this->lineExtensionAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->lineExtensionAmountCurrencyID = $v;
            return true;
        }

        // Handle allowance charges array
        if (in_array(strtolower($k), ['allowancecharges', 'discounts', 'charges', 'iskontolar']) && is_array($v)) {
            foreach ($v as $acOptions) {
                if (is_array($acOptions)) {
                    $this->addAllowanceCharge($acOptions);
                }
            }
            return true;
        }        
        if (in_array($k, ['kdv','kdv_orani']) && StrUtil::notEmpty($v)) {
            $this->setVatRate($v);
        }
        if (in_array($k, ['kdv_tutar','kdv_tutari']) && StrUtil::notEmpty($v)) {
            $this->setVatValue($v);
        }
        // Pass other options to children
        if ($this->item->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        if ($this->price->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }
        if ($this->taxTotal->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // An invoice line must have an ID, a quantity, an amount, and an item.
        return StrUtil::isEmpty($this->id) || is_null($this->invoicedQuantity)  || $this->item->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:InvoiceLine');

        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        //$this->appendElementList($document,$this->note);

        $this->appendElement($document, $element, 'cbc:InvoicedQuantity', number_format($this->invoicedQuantity, 2, '.', ''), ['unitCode' => $this->invoicedQuantityUnitCode]);

        $this->appendElement($document, $element, 'cbc:LineExtensionAmount', number_format(0 + $this->lineExtensionAmount, 2, '.', ''), ['currencyID' => $this->lineExtensionAmountCurrencyID]);

        foreach ($this->allowanceCharge->list as $ac) {
            $this->appendChild($element, $ac->toDOMElement($document));
        }

        foreach ($this->withholdingTaxTotal->list as $wtt) {
            $this->appendChild($element, $wtt->toDOMElement($document));
        }

        $this->appendChild($element, $this->taxTotal->toDOMElement($document));
        $this->appendChild($element, $this->item->toDOMElement($document));
        $this->appendChild($element, $this->price->toDOMElement($document));

        return $element;
    }
    public static function newLine($props){        
        $line = new InvoiceLine($props);        
        return $line;
    }
    public function onBeforeAdd($context=null){
        if(Options::ensureParam($context) && $context instanceof Options){
            if(is_null($this->id)){
                $nid = $context->getAsInt("nextLineId");
                if($nid>0){
                    $this->id = $nid;
                }else{
                    $this->id = StrUtil::getGUID();
                }
            }
            if(is_null($this->lineExtensionAmount)){
                $this->lineExtensionAmount = $this->invoicedQuantity * NumberUtil::coalesce($this->price->priceAmount,0);
            }
        }        
    }
    public function setVatRate($rate){             
        $this->taxTotal->setVatRate($rate);        
    }
    public function setVatValue($val){        
        $this->taxTotal->setVatValue($val);        
    }
    public function calculateLineExtensionAmount(){
        return NumberUtil::asMoneyVal($this->invoicedQuantity * NumberUtil::coalesce($this->price->priceAmount,0));
    }
    public function getLineExtensionAmount(){
        if(!is_null($this->lineExtensionAmount)){
            return $this->lineExtensionAmount;
        }
        return $this->calculateLineExtensionAmount();
    }
    public function getContextArray(){
        return new Options(array(
            "lineExtensionAmount"=>$this->getLineExtensionAmount()
        ));
    }
    public function rebuildValues(){
        $this->lineExtensionAmount = $this->calculateLineExtensionAmount();
        $kdvKey = $this->taxTotal->getVatDefIndex(false);
        if(!is_null($kdvKey)){
            $kdv  = &$this->taxTotal->taxSubtotal->list[$kdvKey];
            if($kdv instanceof TaxSubtotal){
                $kdv->taxableAmount = $this->lineExtensionAmount;
                $kdv->taxAmount     = NumberUtil::asMoneyVal( ($this->lineExtensionAmount*$kdv->percent/100));
            }
        }
    }
}