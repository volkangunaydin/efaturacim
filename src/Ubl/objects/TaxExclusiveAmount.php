<?php

namespace Efaturacim\Util\Ubl\Objects;

class TaxExclusiveAmount extends UblDataTypeForMoney{
    public function initMe(): void{
        $this->setDefaultTagNameIfNotSet("cbc:TaxExclusiveAmount");
    }
} 