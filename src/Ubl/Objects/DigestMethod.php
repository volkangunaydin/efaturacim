<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * DigestMethod class for XML digital signatures
 */
class DigestMethod extends UblDataType
{
    public ?string $algorithm = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (is_null($this->algorithm)) {
            $this->algorithm = 'http://www.w3.org/2001/04/xmlenc#sha256';
        }
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

        $element = $document->createElement('ds:DigestMethod');
        $element->setAttribute('Algorithm', $this->algorithm);
        
        return $element;
    }
}

?>
