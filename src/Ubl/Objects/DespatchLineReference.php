<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class DespatchLineReference extends UblDataType
{


    public ?string $lineId = null;

    /**
     * Summary of taxableAmount
     * @var DocumentReference
     */
    public ?DocumentReference $documentReference = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
        $this->documentReference = new DocumentReference();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['lineId', 'satir_id', 'LineID']) && StrUtil::notEmpty($v)) {
            $this->lineId = $v;
            return true;
        }
        if (in_array($k, ['documentReference', 'dokuman_referansi']) && StrUtil::notEmpty($v)) {
            $this->documentReference = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->lineId) || is_null($this->documentReference) || $this->documentReference->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:DespatchLineReference');
        $this->appendElement($document, $element, 'cbc:LineID', $this->lineId);
        $this->appendChild($element, $this->documentReference->toDOMElement($document));
        return $element;
    }
}
?>