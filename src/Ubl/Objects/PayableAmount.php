<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class PayableAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        parent::initMe();        
        $this->setDefaultTagNameIfNotSet("cbc:PayableAmount");    
    }    
} 