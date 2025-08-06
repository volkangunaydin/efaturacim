<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class TaxableAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        $this->attributes["currencyID"] = "TRY"; 
        $this->defaultTagName = "cbc:TaxableAmount";
    }   
} 