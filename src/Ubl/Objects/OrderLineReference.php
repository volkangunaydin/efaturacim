<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class OrderLineReference extends UblDataType
{
    public ?string $lineId = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['lineId', 'satir_id', 'LineID','lineID']) && StrUtil::notEmpty($v)) {
            $this->lineId = $v;
            return true;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->lineId);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        $element = $document->createElement('cac:OrderLineReference');
        $this->appendElement($document, $element, 'cbc:LineID', $this->lineId);
        return $element;
    }
}
?>