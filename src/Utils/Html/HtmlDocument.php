<?php

namespace Efaturacim\Util\Utils\Html;

/**
 * HTML Document
 */
class HtmlDocument
{
    protected $lang        = 'en';
    protected $title       = '';
    protected $charset     = 'utf-8';
    protected $viewport    = 'width=device-width, initial-scale=1.0';
    protected $bodyTag     = null;
    protected $bodyContent = '';
    protected $nl          = "\r\n";
    protected static $instance = null;
    protected $jsFilesOnEnd  = [];
    protected $jsFilesOnHead = [];
    protected $jsLinesOnDomReady = [];
    protected $cssFiles = [];
    protected $options  = [];    
    public static function getDoc($optionsArray=null){
        if(is_null(self::$instance)){
            self::$instance = new static($optionsArray);
        }
        return self::$instance;
    }
    public function __construct($optionsArray=null){
        $this->bodyTag = new HtmlTag('body');
        if(!is_null($optionsArray) && is_array($optionsArray)){
            $this->applyOptions($optionsArray);
        }
        $this->initMe();
    }
    public function setOptions($arr){
        if(is_array($arr)){
            foreach($arr as $key => $value){
                $this->options[$key] = $value;
            }
        }        
        return $this;
    }
    public function setOption($name,$value){
        $this->options[$name] = $value;
        return $this;
    }
    public function getOption($name,$defVal=null){
        if(isset($this->options[$name])){
            return $this->options[$name];
        }
        return $defVal;
    }
    public function initMe(){
        // Custom initialization code for sub classes
    }
    public function setBodyContent($content){
        $this->bodyContent = $content;
    }
    public function addBodyContent($content){
        $this->bodyContent .= $content;
    }
    public function applyOptions($optionsArray){
        if(!is_null($optionsArray) && is_array($optionsArray)){
            if(isset($optionsArray['lang'])){
                $this->lang = $optionsArray['lang'];
            }    
            if(isset($optionsArray['title'])){
                $this->title = $optionsArray['title'];
            }
            if(isset($optionsArray['charset'])){
                $this->charset = $optionsArray['charset'];
            }
        }
    }
    public function show(){
        header('Content-Type: text/html');        
        echo $this->toHtml();
        die("");
    }
    public function render(){
        return $this->toHtml();
    }
    public function getHeadAsString(){
        $s  = '<!doctype html>';   
        $s .= $this->nl.'<html lang="'.$this->lang.'">';
        $s .= $this->nl.'<head>';
        $s .= $this->nl.'<meta charset="'.$this->charset.'">';
        if(!is_null($this->viewport) && !empty($this->viewport)){
            $s .= $this->nl.'<meta name="viewport" content="'.$this->viewport.'">';
        }        
        foreach($this->cssFiles as $cssFile){              
            $s .= $this->nl.'<link rel="stylesheet" href="'.@$cssFile['url'].'" />';
        }
        foreach($this->jsFilesOnHead as $jsFile){
            $s .= $this->nl.'<script src="'.$jsFile['url'].'" type="'.$jsFile['type'].'" '.$jsFile['params'].'></script>';
        }
        $s .= $this->nl.'<title>'.$this->title.'</title>';
        $s .= $this->nl.'</head>'.$this->nl;
        return $s;
    }
    public function getJsSectionAsString(){
        $s = '';
        foreach($this->jsFilesOnEnd as $jsFile){            
            $s .= $this->nl.'<script src="'.$jsFile['url'].'"></script>';
        }
        return $s;
    }
    protected function addToArray(&$arr,$url,$key=null,$typeStr=null,$params=null,$override=true,$prefix=null){
        if(is_null($key) || empty($key)){
            $key = md5($url);
        }
        if($override===false){                                    
            if(isset($arr[$key]) && strlen("".@$arr[$key]["url"])>0){
                return $this;
            }else if (!is_null($prefix) && key_exists($prefix.$key,$this->options) && strlen("".$this->options[$prefix.$key])>0){                                                
                $url = $this->options[$prefix.$key];                
                //dd(array("override"=>$override,"prefix"=>$prefix,"options"=>$this->options,"key"=>$key,"url"=>$url));
            }            
        }
        if(strlen("".$url)>0){            
            $arr[$key] = [
                'url' => $url,
                'type' => $typeStr,
                'key' => $key,
                'params' => $params
            ];    
        }
        return $this;
    }
    public function addCss($cssFileUrl,$key=null,$typeStr=null,$params=null,$override=true,$prefix=null){        
        return $this->addToArray($this->cssFiles,$cssFileUrl,$key,$typeStr,$params,$override,$prefix);
    }
    public function addJsFile($jsFileUrl,$key=null,$typeStr=null,$params=null,$override=true,$prefix=null){        
        return $this->addToArray($this->jsFilesOnEnd,$jsFileUrl,$key,$typeStr,$params,$override,$prefix);
    }
    public function addJsFileOnEnd($jsFileUrl,$key=null,$typeStr=null,$params=null,$override=true,$prefix=null){        
        return $this->addToArray($this->jsFilesOnEnd,$jsFileUrl,$key,$typeStr,$params,$override,$prefix);
    }
    public function addJsFileOnHead($jsFileUrl,$key=null,$typeStr=null,$params=null,$override=true,$prefix=null){
        return $this->addToArray($this->jsFilesOnHead,$jsFileUrl,$key,$typeStr,$params,$override,$prefix);    
    }
    public function getHeadWithBodyTag(){        
        $s  = $this->getHeadAsString();
        $s .= $this->nl.$this->bodyTag->printStartingTag();          
        return $s;
    }
    public function getBodyEndingTagWithJsSection(){
        $s = '';
        $jsSection = $this->getJsSectionAsString();
        if(!is_null($jsSection) && !empty($jsSection)){
            $s .= $this->nl.'<!-- JS SECTION -->';
            $s .= $this->nl.$jsSection;
            if(count($this->jsLinesOnDomReady)>0){                
                $s .= $this->nl.'<script>';
                $jsLineStr = '';
                foreach($this->jsLinesOnDomReady as $jsLine){
                    $jsLineStr .= $this->nl.$jsLine['url'];
                }
                if(key_exists('jquery',$this->jsFilesOnEnd)){
                    $s .= '$(document).ready(function(){'.$jsLineStr.$this->nl.'});';
                }
                $s .= $this->nl.'</script>';
            }
        }
        $s .= $this->nl.'</html>';
        return $s;
    }
    public function addJsLineToDomReady($jsLineOrLines,$key=null){
        // TODO: Implement addJsLineToDomReady
        if($jsLineOrLines && is_array($jsLineOrLines)){
            $i = 0;
            if(is_null($key)){ $key = "id".md5(serialize($jsLineOrLines)); }
            foreach($jsLineOrLines as $jsLine){
                $i++;                
                $key2 = $key."_".$i;
                $this->addToArray($this->jsLinesOnDomReady,$jsLine,$key2);       
            }
        }else if ($jsLineOrLines && is_string($jsLineOrLines) && strlen($jsLineOrLines)>0){
            $this->addToArray($this->jsLinesOnDomReady,$jsLineOrLines,$key);
        }
        return $this;
    }
    public function toHtml(){
        $s  = $this->getHeadAsString();
        $s .= $this->nl.$this->bodyTag->printStartingTag();
        $s .= $this->bodyContent;
        $s .= $this->nl.$this->bodyTag->printEndingTag();
        $s .= $this->getBodyEndingTagWithJsSection();
        return $s;
    }    
    public function addJsFilesOnEnd($jsArray,$override=false){
        if(!is_null($jsArray) && is_array($jsArray)){
            foreach($jsArray as $key=>$jsFile){
                $this->addJsFileOnEnd($jsFile,$key,null,null,$override,'js::');
            }
        }
        return $this;
    }
    public function addCssFiles($cssArray,$override=false){
        if(!is_null($cssArray) && is_array($cssArray)){
            foreach($cssArray as $key=>$cssFile){                
                $this->addCss($cssFile,$key,null,null,$override,'css::');                
            }
        }
        return $this;
    }
    public function ensureJQuery(){
        return $this->addJsFilesOnEnd(array('jquery'=>'https://code.jquery.com/jquery-3.7.1.min.js'),false);
    }
}
?>
