<?php
namespace Efaturacim\Util\Utils\Html\Document;

use Efaturacim\Util\Utils\Options;

class SimpleHtmlDocument{
    /** */
    protected $options = null;
    protected $bodyContents = [];    
    protected $headContents = [];
    protected $endJsFiles   = [];
    protected $nl = "\r\n";
    public function __construct($options=null,$defVals=null){
        $this->options = Options::newParams($options,$defVals);
        $this->initMe();
    }
    public function initMe(){
        // Custom initialization code for sub classes
    }
    public function &getOptions(){
        return $this->options;        
    }
    public function addBodyContent($content,$key="default",$override=false){
        if(!key_exists($key,$this->bodyContents)){
            $this->bodyContents[$key] = "";
        }
        if($override){
            $this->bodyContents[$key] = $content;
        }else{
            $this->bodyContents[$key] = $this->bodyContents["".$key].$content;
        }
        return $this;
    }
    public function setBodyContent($content,$key="default"){
        $this->addBodyContent($content,$key,true);
        return $this;
    }
    public function getBodyContent(){
        return implode("", $this->bodyContents);
    }
    public function toHtml(){
        $s = '';
        $s .= '<!doctype html>';
        $s .= $this->nl.'<html lang="'.$this->options->getAsString("lang","tr").'">';
        $s .= $this->nl.'<head>';
        $s .= $this->nl.'<meta charset="utf-8">';
        $s .= $this->nl.'<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $s .= $this->nl.'<title>'.$this->options->getAsString("title","Untitled").'</title>';
        $s .= implode("", $this->headContents);
        $s .= $this->nl.'</head>';
        $s .= $this->nl.'<body>';
        $s .= $this->getBodyContent();
        $s .= implode("", $this->endJsFiles);
        $s .= $this->nl.'</body>';
        $s .= $this->nl.'</html>';
        return $s;        
    }
    public function show(){
        header('Content-Type: text/html');        
        echo $this->toHtml();
        die("");
    }
    public function addHeadContent($content,$key="default"){
        $this->headContents[$key] .= $content;
        return $this;
    }
    public function addCss($url,$key="default"){
        $this->headContents[$key] = '<link rel="stylesheet" href="'.$url.'" />';
        return $this;
    }
    public function addJs($url,$key="default"){
        $this->headContents[$key] = '<script src="'.$url.'"></script>';
        return $this;
    }
    public function addEndJsFile($url,$key="default",$override=false){
        if(key_exists($key,$this->endJsFiles) && !$override){
            return $this;
        }
        $this->endJsFiles[$key] = '<script src="'.$url.'"></script>';
        return $this;
    }
}
?>