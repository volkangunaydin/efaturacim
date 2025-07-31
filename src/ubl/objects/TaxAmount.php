<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\StrUtil;

class TaxAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        $this->attributes["currencyID"] = "TRY"; 
        $this->defaultTagName = "cbc:TaxAmount";
    }   
} 