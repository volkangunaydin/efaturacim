<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class DeliveredQuantity extends UblDataTypeForQuantity{    
    public function initMe(){
       $this->setDefaultTagNameIfNotSet('cbc:DeliveredQuantity');
       parent::initMe();
    }
}