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
    public function getJsFiles(){
        return null;
    }
    public function getCssFiles(){
        return null;
    }
    public function __toString()
    {
        return $this->toHtmlAsString();
    }
    public function toHtml($doc){
        $s = $this->toHtmlAsString();        
        if($doc && $doc instanceof HtmlDocument){
            $doc->addCssFiles($this->getCssFiles(),false);
            $doc->addJsFilesOnEnd($this->getJsFiles(),false);
            $doc->addJsLineToDomReady($this->getJsLines());
        }
        return $s;
    }
    public function getJsLinesForDebug(){
        $s  = '';
        $nl = "\r\n";
        $jsLines = $this->getJsLines();
        if(!is_null($jsLines) && is_array($jsLines) && count($jsLines)>0){
            foreach($jsLines as $jsLine){
                $s .= $nl.$jsLine;
            }
        }
        return $s;
    }
}
?>