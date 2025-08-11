<?php

namespace Efaturacim\Util\Ubl\Objects;

class TaxExclusiveAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        parent::initMe();
        $this->setDefaultTagNameIfNotSet("cbc:TaxExclusiveAmount");
    }
} 