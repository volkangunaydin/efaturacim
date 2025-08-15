<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;

use Efaturacim\Util\Utils\Html\Form\FormParams;
use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlDocument;
use Efaturacim\Util\Utils\Html\HtmlTag;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Utils\Url\UrlObject;

class BootstrapForm extends HtmlComponent{
    /** @var HtmlTag */
   public $tagForm = null; 
  public function initMe()
  {
    $this->tagForm = HtmlTag::form()->initID();
    $this->tagForm->setAttribute("method","post");
    $this->tagForm->setAttribute("action",UrlObject::newUrl()->toString());
    $this->tagForm->setAttribute("enctype","multipart/form-data");
  } 
  public static function newForm($options=null){
    return new static($options);    
  }
  /** @return HtmlTag */
  public function textInput($doc,$name,$defVal=null,$caption=null,$options=null){
    if($defVal==FormParams::$AUTO){
        $defVal = FormParams::getRequestParam($name);
    }
     $input = HtmlTag::input()->setAttribute("name",$name)->setAttribute("value",$defVal)->addClass("form-control");
     return $input;
  }
  public function addTextInput($doc,$name,$defVal=null,$caption=null,$options=null){
       $div   = HtmlTag::div()->addClass("mb-3");
       $input = $this->textInput($doc,$name,$defVal,$caption,$options);
       $label = null;
       if(StrUtil::notEmpty($caption)){         
         $label = HtmlTag::label()->setInnerHtml($caption)->setAttribute("for",$input->getId())->addClass("form-label"); 
       }
       if(!is_null($label)){
            $div->addContent($label,$name."_label");
       }
       $div->addContent($input,$name."_input");
       $this->tagForm->addContent($div,$name);
       return $this;
  }
  public function addSubmit($doc,$caption,$options=null){
      $button = HtmlTag::button()->setInnerHtml($caption)->addClass("btn btn-primary")->setAttribute("type","submit");   
      $this->tagForm->addContent($button,"submit_button");
      return $this;
  }
  public function toHtml($doc)
  {
        if($doc instanceof HtmlDocument && strlen("".$doc->csrf)>0){
            $this->tagForm->addContent('<input type="hidden" name="_token" value="'.$doc->csrf.'" />',"_token");
        }
        return parent::toHtml($doc);
  }
  public function toHtmlAsString(){
        $s = '';        
        $s .= $this->tagForm->render();
        return $s;
  }

}
?>