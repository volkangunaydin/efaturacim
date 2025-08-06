<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class InvoicedQuantity extends UblDataTypeForQuantity{    
    public function initMe(){
       $this->setDefaultTagNameIfNotSet('cbc:InvoicedQuantity');
       parent::initMe();
    }
}