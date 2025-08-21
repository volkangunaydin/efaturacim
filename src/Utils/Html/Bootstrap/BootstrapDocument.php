<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;

use Efaturacim\Util\Utils\Html\Datatable\DataTablesJs;
use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlDocument;
// SAMPLE USAGES
// $doc = new Bootstrap5Document(); 
// $doc->show();
// ------------------
// echo \Efaturacim\Util\Utils\Html\Bootstrap\BootstrapDocument::getDoc()->getHeadWithBodyTag();
// echo echo \Efaturacim\Util\Utils\Html\Documents\Bootstrap5Document::getDoc()->getBodyEndingTagWithJsSection();
// ----
// echo BootstrapDocument::getDoc()->getHeadWithBodyTag();
// echo BootstrapDocument::getDoc()->getBodyEndingTagWithJsSection();



class BootstrapDocument extends HtmlDocument{
    public function initMe(){        
        if(HtmlComponent::isPathDefined("bootstrap")){            
            $assetPath = HtmlComponent::getPathDefined("bootstrap");
            if($assetPath!="none"){
                $this->addCss($assetPath.'css/bootstrap.min.css','bootstrap');
                $this->addJsFileOnEnd($assetPath.'js/bootstrap.bundle.min.js','bootstrap');        
            }            
        }else{
            $this->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css','bootstrap');
            $this->addJsFileOnEnd('https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js','bootstrap');    
        }
        $this->ensureJQuery();
    }
    public static function alert($type="error",$message="",$options=null){
        return new Alert(array('type'=>$type,'message'=>$message),$options);
    }
    
    /**
     * Create a DataTable with optional captions/headers
     * 
     * @param mixed ...$captions Variable number of caption arguments
     * @return DataTablesJs
     */
    public static function dataTable($cap=null){
        return (new DataTablesJs())->addCaptions(func_get_args());        
    }
}
?>