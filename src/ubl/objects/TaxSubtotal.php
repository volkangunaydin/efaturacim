<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\StrUtil;

class TaxSubtotal extends UblDataType
{
    public ?float $taxableAmount = null;
    public ?float $percent       = 20;
    public ?string $taxableAmountCurrencyID = 'TRY';

    public ?float $taxAmount = null;
    public ?string $taxAmountCurrencyID = 'TRY';

    public ?TaxCategory $taxCategory = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->taxCategory = new TaxCategory();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
        //\Vulcan\V::dump($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['taxableAmount', 'matrah']) && is_numeric($v)) {
            $this->taxableAmount = (float)$v;
            return true;
        }

        if (in_array($k, ['taxAmount', 'vergi_tutari']) && is_numeric($v)) {
            $this->taxAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['percent',  'kdv_oran','kdv_orani']) && is_numeric($v)) {
            $this->percent = $v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->taxableAmountCurrencyID = $v;
            $this->taxAmountCurrencyID = $v;
            return true;
        }
        if (in_array($k, ['name']) && StrUtil::notEmpty($v)) {
            $this->taxCategory->taxScheme->name = $v; 
            return true;
        }
        if (in_array($k, ['TaxTypeCode','taxTypeCode','vergi_kodu']) && StrUtil::notEmpty($v)) {
            $this->taxCategory->taxScheme->taxTypeCode  = $v;
            return true;
        }
        // Pass other options to taxCategory
        if ($this->taxCategory->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {        
        // A tax subtotal is considered empty if the tax amount is not set,
        // or if the mandatory tax category is empty.
        return is_null($this->taxAmount) || is_null($this->taxCategory) || $this->taxCategory->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:TaxSubtotal');

        if(!is_null($this->taxableAmount)){
            $this->appendElement($document, $element, 'cbc:TaxableAmount', number_format($this->taxableAmount, 2, '.', ''), ['currencyID' => $this->taxableAmountCurrencyID]);
        }
        

        $this->appendElement($document, $element, 'cbc:TaxAmount', number_format($this->taxAmount, 2, '.', ''), ['currencyID' => $this->taxAmountCurrencyID]);
        if(!is_null($this->percent)){
            $this->appendElement($document, $element, 'cbc:Percent',NumberUtil::asCleanNumber($this->percent));
        }

        $this->appendChild($element, $this->taxCategory->toDOMElement($document));

        return $element;
    }
    public function getTaxSchemeTaxTypeCode(){
        if($this->taxCategory && $this->taxCategory instanceof TaxCategory && $this->taxCategory->taxScheme && $this->taxCategory->taxScheme instanceof TaxScheme){
            return $this->taxCategory->taxScheme->taxTypeCode;
        }
        return null;
    }
}