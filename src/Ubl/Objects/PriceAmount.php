<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class PriceAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        parent::initMe();        
        $this->setDefaultTagNameIfNotSet("cbc:PriceAmount");    
    }    
} 