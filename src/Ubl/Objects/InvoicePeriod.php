<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class InvoicePeriod extends UblDataType
{
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['startDate']) && StrUtil::notEmpty($v)) {
            $this->startDate = $v;
            return true;
        }

        if (in_array($k, ['endDate']) && StrUtil::notEmpty($v)) {
            $this->endDate = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->startDate) && is_null($this->endDate);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:InvoicePeriod');

        $this->appendElement($document, $element, 'cbc:StartDate', $this->startDate);
        $this->appendElement($document, $element, 'cbc:EndDate', $this->endDate);

        return $element;
    }
}