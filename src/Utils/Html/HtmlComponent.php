<?php
namespace Efaturacim\Util\Utils\Html;

use Efaturacim\Util\Utils\Array\AssocArray;

class HtmlComponent{  
    protected static $PATHS = [];    
    protected $options = [];  
    protected $assetPath = null;
    protected $assetPathKey = null;
    public function __construct($options=null,$defVals=null){        
        $this->options = AssocArray::newArray($options,$defVals,$this->getDefaultOptions());    
        $this->initMe();
        if(!is_null($this->assetPathKey)){
            $this->assetPath = AssocArray::getVal(HtmlComponent::$PATHS,$this->assetPathKey,null);
        }
    }
    public function initMe(){
        // Custom initialization code for sub classes
    }
    public static function getPaths(){
        return self::$PATHS;
    }
    public function hasAssetPath(){
        return !is_null($this->assetPath) && strlen("".$this->assetPath)>0;
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
    public static function addPaths($pathArray){
        if(!is_null($pathArray) && is_array($pathArray)){
            foreach($pathArray as $key=>$path){
                self::$PATHS[$key] = $path;
            }
        }        
    }
    public static function isPathDefined($key){
        $p =  AssocArray::getVal(self::$PATHS,$key,null);
        return !is_null($p) && strlen("".$p)>0;
    }
    public static function getPathDefined($key){
        $p =  AssocArray::getVal(self::$PATHS,$key,null);
        return !is_null($p) && strlen("".$p)>0 ? $p : null;
    }
}
?>