<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;

use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlTag;

class Row extends HtmlComponent{
    /** @var HtmlTag */
    protected $tag = null;
    public function initMe(){
        $this->tag = HtmlTag::div()->addClass("row");
    }
    public function toHtmlAsString(){
        return $this->tag->render();
    }    
    public static function newRow($options=null,$defVals=null){
        return (new static($options,$defVals));
    }
    public function col6($content=null){        
        $this->tag->addContent(HtmlTag::div()->addClass("col-6")->setInnerHtml($content));
        return $this;
    }
}
?>