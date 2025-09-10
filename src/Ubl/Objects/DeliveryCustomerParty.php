<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class DeliveryCustomerParty extends UblDataTypeForPartyContainer
{
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:DeliveryCustomerParty");
        parent::initMe();
    }
}