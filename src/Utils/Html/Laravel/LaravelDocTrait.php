<?php
namespace Efaturacim\Util\Utils\Html\Laravel;

use Efaturacim\Util\Utils\Html\HtmlDocument;

trait LaravelDocTrait{
    public static function view($viewName,$data=[]){
        $htmlContent = view($viewName,$data)->render();                
        if(method_exists(self::class,"getDoc")){
            $doc = self::getDoc();
            if($doc instanceof HtmlDocument){
                $doc->setBodyContent($htmlContent);
                $doc->show();
            }
        }
        die("".$htmlContent);
    }
}
?>  