<?php
namespace Efaturacim\Util\Utils\Html\Document;
class BootstrapDocument extends HtmlDocument{
    public function __construct($options=null){
        parent::__construct($options);
    }
    public function initMe(){
        $this->addCss("https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css","bootstrap");
        $this->addEndJsFile("https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js","bootstrap");
    }
}