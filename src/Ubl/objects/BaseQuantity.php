<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\NumberUtil;
use Efaturacim\Util\StrUtil;

class BaseQuantity extends UblDataTypeForQuantity{    
    public function initMe(){
       $this->setDefaultTagNameIfNotSet('cbc:BaseQuantity');
       parent::initMe();
    }
}