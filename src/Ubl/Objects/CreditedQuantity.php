<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class CreditedQuantity extends UblDataTypeForQuantity{    
    public function initMe(){
       $this->setDefaultTagNameIfNotSet('cbc:CreditedQuantity');
       parent::initMe();
    }
}
