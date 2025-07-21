<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;

class TaxTotal extends UblDataType
{
    public ?float $taxAmount = null;
    public ?string $taxAmountCurrencyID = 'TRY';

    /**
     * @var UblDataTypeList
     */
    public $taxSubtotal;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->taxSubtotal = new UblDataTypeList(TaxSubtotal::class);
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function addTaxSubtotal(array $options,$context=null): self
    {
        $this->taxSubtotal->add(new TaxSubtotal($options),null,null,$context);
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['taxAmount', 'toplam_vergi_tutari']) && is_numeric($v)) {
            $this->taxAmount = (float)$v;
            return true;
        }

        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->taxAmountCurrencyID = $v;
            return true;
        }

        if (in_array(strtolower($k), ['taxsubtotal', 'subtotals', 'vergi_detaylari']) && is_array($v)) {
            foreach ($v as $subtotalOptions) {
                if (is_array($subtotalOptions)) {
                    $this->addTaxSubtotal($subtotalOptions);
                }
            }
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return false;
        //return is_null($this->taxAmount) || $this->taxSubtotal->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:TaxTotal');
        if(is_null($this->taxAmount)){
            $this->appendElement($document, $element, 'cbc:TaxAmount', number_format(0 + $this->taxAmount, 2, '.', ''), ['currencyID' => $this->taxAmountCurrencyID]);
        }                

        foreach ($this->taxSubtotal->list as $subtotal) {
            $this->appendChild($element, $subtotal->toDOMElement($document));
        }

        return $element;
    }
    public function getVatDefIndex($create=true){
        foreach($this->taxSubtotal->list as $key=>$subtotal){
            if($subtotal instanceof TaxSubtotal){
                if($subtotal->getTaxSchemeTaxTypeCode()=="0015"){
                    return $key;
                }
            }    
        }
        if($create){
            $this->addTaxSubtotal(array("taxTypeCode"=>"0015","name"=>"KDV","percent"=>0));        
            return $this->getVatDefIndex(false);
        }
        return false;        
    }
    public function setVatRate($rate){
        $key = $this->getVatDefIndex();        
        if(!is_null($key)){        
            $this->taxSubtotal->list[$key]->options->setValue("percent",$rate);
            $this->taxSubtotal->list[$key]->percent = $rate;            
        }
    }
    public function setVatValue($val){
        $key = $this->getVatDefIndex();
        if(!is_null($key)){
            $this->taxSubtotal->list[$key]->taxAmount = $val;
        }
    }
    public function setVatTaxableAmount($val){
        $key = $this->getVatDefIndex();
        if(!is_null($key)){
            $this->taxSubtotal->list[$key]->taxableAmount = $val;
        }
    }
    public function onBeforeAdd($context=null){
        if(Options::ensureParam($context) && $context instanceof Options){

        }        
    }
}