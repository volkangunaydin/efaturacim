<?php
namespace Efaturacim\Util\Usage\Html;

use Efaturacim\Util\Utils\Html\Bootstrap\BootstrapDocument;
use Efaturacim\Util\Utils\Html\Bootstrap\Title;
use Efaturacim\Util\Utils\Html\PrettyPrint\PrettyPrint;

class LarvavelUsage{
    public static function runDemo(){
        $doc = new BootstrapDocument();
        $s = '';
        $s .= Title::title("Laravel Document Definition : App\\Services\\MyLaravelDocument.php",array("tag"=>"h2"));
        $s .= PrettyPrint::php($doc,array(
            '<?php'
            ,'   namespace App\Services;'
            ,'   use Efaturacim\Util\Utils\Html\Bootstrap\BootstrapDocument;'
            ,'   class MyLaravelDocument extends BootstrapDocument{'
            ,'       public function initMe(){'
            ,'           HtmlComponent::addPaths(array());'
            ,'           parent::initMe();'
            ,'           $this->title = "My Laravel App";  '
            ,'           $this->csrf = csrf_token();'
            ,'       }'
            ,'   }'
            ,'?>'
        ));
        $s .= Title::title("Laravel Routers",array("tag"=>"h2"));
        $s .= PrettyPrint::php($doc,array(
            '<?php'
            ,'   Route::any("/example-page1", function (){'
            ,'       // instead of return view("page1");'
            ,'       // you can also use controllers and return view from there'
            ,'       return MyLaravelDocument::getDoc()->handlePage(function(&$doc){ return view("page1")->render(); });'
            ,'   });'
            ,'   Route::any("/example-using-LaravelDocTrait", function (){'
            ,'       return MyLaravelDocument::view("welcome");'
            ,'   });'

            ,'?>'
        ));
        $doc->setBodyContent($s);
        $doc->show();        
    }
}
?>