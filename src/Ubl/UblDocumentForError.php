<?php

namespace Efaturacim\Util\Ubl;

class UblDocumentForError extends UblDocument{
    public function __construct($xmlString=null,$options=null)
    {
        parent::__construct($xmlString,$options);
    }
    public function initMe()
    {
        $this->rootElementName = 'none';
        return $this;
    }
    public function toXml(): string
    {
        return "";
    }
    public function loadFromXml($xmlString,$debug=false)
    {        
        return $this;
    }
}
?>