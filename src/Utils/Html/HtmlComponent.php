<?php
namespace Efaturacim\Util\Utils\Html;

use Efaturacim\Util\Utils\Array\AssocArray;

class HtmlComponent{  
    protected $options = [];
    public function __construct($options=null,$defVals=null){        
        $this->options = AssocArray::newArray($options,$defVals,$this->getDefaultOptions());    
        $this->initMe();
    }
    public function initMe(){
        // Custom initialization code for sub classes
    }
    public function getDefaultOptions(){
        return null;
    }
    public function toHtmlAsString(){
        return 'IMPLEMENT toHtmlAsString FOR '.get_class($this);
    }
    public function getJsLines(){
        return null;
    }
    public function toHtml($doc){
        $s = $this->toHtmlAsString();        
        if($doc && $doc instanceof HtmlDocument){
            $doc->addJsLineToDomReady($this->getJsLines());
        }
        return $s;
    }
}
?>