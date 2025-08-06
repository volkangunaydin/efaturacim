<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class LineExtensionAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        parent::initMe();        
        $this->setDefaultTagNameIfNotSet("cbc:LineExtensionAmount");
    }
}