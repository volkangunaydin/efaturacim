<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class AccountingCustomerParty extends UblDataTypeForPartyContainer
{
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:AccountingCustomerParty");
        parent::initMe();
    }
}