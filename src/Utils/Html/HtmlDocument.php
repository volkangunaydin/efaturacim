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
    protected $cssFiles = [];
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
    protected function addToArray(&$arr,$url,$key=null,$typeStr=null,$params=null){
        if(is_null($key) || empty($key)){
            $key = md5($url);
        }
        $arr[$key] = [
            'url' => $url,
            'type' => $typeStr,
            'key' => $key,
            'params' => $params
        ];
        return $this;
    }
    public function addCss($cssFileUrl,$key=null,$typeStr=null,$params=null){        
        return $this->addToArray($this->cssFiles,$cssFileUrl,$key,$typeStr,$params);
    }
    public function addJsFile($jsFileUrl,$key=null,$typeStr=null,$params=null){        
        return $this->addToArray($this->jsFilesOnEnd,$jsFileUrl,$key,$typeStr,$params);
    }
    public function addJsFileOnEnd($jsFileUrl,$key=null,$typeStr=null,$params=null){        
        return $this->addToArray($this->jsFilesOnEnd,$jsFileUrl,$key,$typeStr,$params);
    }
    public function addJsFileOnHead($jsFileUrl,$key=null,$typeStr=null,$params=null){
        return $this->addToArray($this->jsFilesOnHead,$jsFileUrl,$key,$typeStr,$params);    
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
        }
        $s .= $this->nl.'</html>';
        return $s;
    }
    public function addJsLineToDomReady($jsLineOrLines){
        // TODO: Implement addJsLineToDomReady
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
}
?>
