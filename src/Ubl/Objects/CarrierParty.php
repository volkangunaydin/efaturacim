<?php

namespace Efaturacim\Util\Ubl\Objects;

class CarrierParty extends UblDataTypeForPartyContainer
{
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:CarrierParty");
        parent::initMe();
    }
}