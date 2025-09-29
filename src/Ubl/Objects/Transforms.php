<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * Transforms class for XML digital signatures
 * 
 * Container for multiple Transform elements in XML digital signatures.
 */
class Transforms extends UblDataType
{
    public ?UblDataTypeList $transform = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->transform = new UblDataTypeList(Transform::class);
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['transform', 'transforms', 'Transform', 'Transforms']) && is_array($v)) {
            if (key_exists(0, $v)) {
                // Multiple transforms
                foreach ($v as $transform) {
                    $t = new Transform($transform);
                    $this->transform->add($t);
                }
            } else {
                // Single transform
                $t = new Transform($v);
                $this->transform->add($t);
            }
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->transform) || $this->transform->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('ds:Transforms');
        $this->appendElementList($document, $this->transform, $element);
        
        return $element;
    }
}

?>
