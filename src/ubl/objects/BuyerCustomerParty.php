<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class BuyerCustomerParty extends UblDataTypeForPartyContainer
{
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:BuyerCustomerParty");
        parent::initMe();
    }
}