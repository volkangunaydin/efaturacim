<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class LegalMonetaryTotal extends UblDataType
{
    public ?float $lineExtensionAmount = null;
    public ?float $taxExclusiveAmount = null;
    public ?float $taxInclusiveAmount = null;
    public ?float $allowanceTotalAmount = null;
    public ?float $chargeTotalAmount = null;
    public ?float $payableAmount = null;
    public ?string $currencyID = 'TRY';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['lineExtensionAmount', 'mal_hizmet_toplam_tutari']) && is_numeric($v)) {
            $this->lineExtensionAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['taxExclusiveAmount', 'vergi_haric_tutar']) && is_numeric($v)) {
            $this->taxExclusiveAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['taxInclusiveAmount', 'vergi_dahil_tutar']) && is_numeric($v)) {
            $this->taxInclusiveAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['allowanceTotalAmount', 'toplam_iskonto_tutari']) && is_numeric($v)) {
            $this->allowanceTotalAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['chargeTotalAmount', 'toplam_artis_tutari']) && is_numeric($v)) {
            $this->chargeTotalAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['payableAmount', 'odenecek_tutar']) && is_numeric($v)) {
            $this->payableAmount = (float)$v;
            return true;
        }
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->currencyID = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        // LegalMonetaryTotal is essential and should at least have a payable amount.
        return is_null($this->payableAmount);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:LegalMonetaryTotal');

        $this->appendElement($document, $element, 'cbc:LineExtensionAmount', number_format($this->lineExtensionAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:TaxExclusiveAmount', number_format($this->taxExclusiveAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:TaxInclusiveAmount', number_format($this->taxInclusiveAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:AllowanceTotalAmount', number_format($this->allowanceTotalAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:ChargeTotalAmount', number_format($this->chargeTotalAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);
        $this->appendElement($document, $element, 'cbc:PayableAmount', number_format($this->payableAmount, 2, '.', ''), ['currencyID' => $this->currencyID]);

        return $element;
    }
}