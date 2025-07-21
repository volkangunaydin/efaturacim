<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\StrUtil;

class TaxCategory extends UblDataType
{
    public ?string $name = null;
    public ?float $percent = null;
    public ?TaxScheme $taxScheme = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->taxScheme = new TaxScheme();
        if (!is_null($this->options)) {
            $this->loadFromOptions($this->options);
        }
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['name', 'kategori_adi']) && StrUtil::notEmpty($v)) {
            $this->name = $v;
            return true;
        }
        if (in_array($k, ['percent', 'oran']) && is_numeric($v)) {
            $this->percent = (float)$v;
            return true;
        }

        // Pass other options to taxScheme
        if ($this->taxScheme->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        // A tax category is empty if its tax scheme is empty, as TaxScheme is mandatory in UBL.
        return is_null($this->taxScheme) || $this->taxScheme->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:TaxCategory');

        $this->appendElement($document, $element, 'cbc:Name', $this->name);

        if ($this->percent !== null) {
            $this->appendElement($document, $element, 'cbc:Percent', number_format($this->percent, 2, '.', ''));
        }

        $this->appendChild($element, $this->taxScheme->toDOMElement($document));

        return $element;
    }
}