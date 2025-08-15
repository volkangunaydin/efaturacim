<?php
namespace Efaturacim\Util\Utils\Html\PrettyPrint;

use Efaturacim\Util\Utils\Array\AssocArray;
use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlDocument;
use Efaturacim\Util\Utils\Html\HtmlTag;
use Efaturacim\Util\Utils\String\StrUtil;

class PrettyPrint extends HtmlComponent{    
    protected $code = null;
    protected $type = "html";    
    protected $lang = "go";
    protected $style = "purebasic";
    /** @var HtmlTag */
    protected $tag = null;
    public static function html($doc,$html,$options=null,$maxHeight=null,$style=null){
        return (new static($options))->setCode($html,"html")->setStyle($style)->setMaxHeight($maxHeight)->toHtml($doc);
    }
    public static function js($doc,$js,$options=null,$maxHeight=null){
        return (new static($options))->setCode($js,"js")->setMaxHeight($maxHeight)->toHtml($doc);
    }    

    public function initMe(){
        $this->tag = HtmlTag::code()->initID()->addClass("language-html","default");
        $this->assetPathKey = "highlightjs";
    }
    public function getDefaultOptions(){
        return [            
            'css1' => 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/default.min.css',    
            'css2' =>'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/gruvbox-dark.min.css',        
            'js1' => 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js',            
            'js2'=>'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/go.min.js',
        ];            
    }
    public function setMaxHeight($maxHeight=null){
        if(!is_null($maxHeight) && $maxHeight>0){
            $this->tag->setStyle("max-height:".$maxHeight."px;overflow-y:auto;");
        }
        return $this;
    }
    public function toHtml($doc)
    {
        return parent::toHtml($doc);
    }
    public function getJsLines(){
        return ['hljs.highlightElement(document.getElementById("'.$this->tag->getId().'"));'];
    }   
    public function getJsFiles(){        
        if($this->hasAssetPath()){            
            return ['highlightjs1'=>$this->assetPath."highlight.min.js",'highlightjs_'.$this->lang=>$this->assetPath."languages/".$this->lang.".min.js" ];
        }else{
            return ['highlightjs1'=>@$this->options['js1'],'highlightjs2'=>@$this->options['js2'] ];
        }        
    }
    public function getCssFiles(){
        if($this->hasAssetPath()){            
            return ['highlightjs1'=>$this->assetPath."styles/default.min.css",'highlightjs2'=>$this->assetPath."styles/".$this->style.".min.css"];
        }else{
            return ['highlightjs1'=>@$this->options['css1'],'highlightjs2'=>@$this->options['css2']];
        }
        
    }    
    public function setCode($code,$type=null){
        $this->code = $code;        
        if(!is_null($type)){
            $this->setType($type);
        }        
        return $this;
    }
    public function setType($type){
        $this->type = $type;
        if($type=="html"){
            $this->lang = "html";
        }else if($type=="js"){
            $this->lang = "javascript";
        }
        return $this;
    }
    public function setStyle($style=null){
        if(!is_null($style)){
            $this->style = $style;
        }        
        return $this;
    }    
    public function toHtmlAsString(){   
        if(in_array($this->type,array("js"))){
            $this->tag->addClass("language-js","default");
        }     
        $s = '<pre>'.$this->tag->setInnerHtml(htmlentities($this->code,ENT_QUOTES,'UTF-8'))->toHtml().'</pre>';
        return $s;
    }
}   
?>