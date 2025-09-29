<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * Transform class for XML digital signatures
 */
class Transform extends UblDataType
{
    public ?string $algorithm = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['algorithm', 'Algorithm']) && StrUtil::notEmpty($v)) {
            $this->algorithm = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return !StrUtil::notEmpty($this->algorithm);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:Transform');
        $element->setAttribute('Algorithm', $this->algorithm);
        
        return $element;
    }
}

?>
