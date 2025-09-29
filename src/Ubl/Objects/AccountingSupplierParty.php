<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class AccountingSupplierParty extends UblDataTypeForPartyContainer
{
    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:AccountingSupplierParty");
        parent::initMe();
    }
    
    
}