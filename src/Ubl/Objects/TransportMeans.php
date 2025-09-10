<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class TransportMeans extends UblDataType
{
    public ?RoadTransport $roadTransport = null;

    public function __construct($options = null)
    {
        parent::__construct($options);        
    }
    public function initMe(){
        $this->roadTransport = new RoadTransport();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if ($this->roadTransport->setPropertyFromOptions($k, $v, $options)) {
            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        return is_null($this->roadTransport) || $this->roadTransport->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        $element = $document->createElement('cac:TransportMeans');
        $this->appendChild($element, $this->roadTransport->toDOMElement($document));
        return $element;
    }
}