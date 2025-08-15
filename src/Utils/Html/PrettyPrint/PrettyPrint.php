<?php
namespace Efaturacim\Util\Utils\Html\PrettyPrint;

use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlTag;

class PrettyPrint extends HtmlComponent{
    protected $code = null;
    protected $type = "html";    
    /** @var HtmlTag */
    protected $tag = null;
    public static function html($doc,$html,$options=null,$maxHeight=null){
        return (new static($options))->setCode($html,"html")->setMaxHeight($maxHeight)->toHtml($doc);
    }
    public static function js($doc,$js,$options=null,$maxHeight=null){
        return (new static($options))->setCode($js,"js")->setMaxHeight($maxHeight)->toHtml($doc);
    }    

    public function initMe(){
        $this->tag = HtmlTag::code()->initID()->addClass("language-html","default");
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
        return ['highlightjs1'=>@$this->options['js1'],'highlightjs2'=>@$this->options['js2'] ];
    }
    public function getCssFiles(){
        return ['highlightjs1'=>@$this->options['css1'],'highlightjs2'=>@$this->options['css2']];
    }    
    public function setCode($code,$type=null){
        $this->code = $code;
        if(!is_null($type)){
            $this->type = $type;
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