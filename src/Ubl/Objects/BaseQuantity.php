<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;


class BaseQuantity extends UblDataTypeForQuantity{    
    public function initMe(){
       $this->setDefaultTagNameIfNotSet('cbc:BaseQuantity');
       parent::initMe();
    }
}