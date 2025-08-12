<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class Amount extends UblDataTypeForMoney{
    public function initMe(): void{
        $this->attributes["currencyID"] = "TRY"; 
        $this->defaultTagName = "cbc:Amount";
    }   
} 