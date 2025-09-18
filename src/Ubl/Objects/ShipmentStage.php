<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class ShipmentStage extends UblDataType
{
    public ?string $transportModeCode = null;
    public ?TransportMeans $transportMeans = null;
    /**
     * @var UblDataTypeList
     */
    public ?UblDataTypeList $driverPerson;
    
    public function initMe(){
        $this->transportMeans = new TransportMeans();
        $this->driverPerson = new UblDataTypeList(DriverPerson::class);
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                       
        if (in_array($k, ['TransportMeans', 'transportMeans', 'TRANSPORTMEANS'])) {
            $this->transportMeans = $v;
            return true;
        }
        if (in_array($k, ['DriverPerson', 'driverPerson', 'DRIVERPERSON'])) {
            $this->driverPerson = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->transportModeCode) && is_null($this->transportMeans) && is_null($this->driverPerson);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $document->createElement('cac:ShipmentStage');
        $this->appendElement($document, $element, 'cbc:TransportModeCode', $this->transportModeCode);
        $this->appendChild($element, $this->transportMeans->toDOMElement($document));
        $this->appendElementList($document, $this->driverPerson, $element);
        return $element;
    }
}