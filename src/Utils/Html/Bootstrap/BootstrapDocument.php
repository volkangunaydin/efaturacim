<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
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
        $this->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css','bootstrap-css');
        $this->addJsFileOnEnd('https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js','bootstrap-js');
    }
}
?>