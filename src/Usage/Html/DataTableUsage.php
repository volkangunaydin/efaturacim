<?php
namespace Efaturacim\Util\Usage\Html;

use Efaturacim\Util\Data\GeoData\TR\Iller;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Html\Bootstrap\BootstrapForm;
use Efaturacim\Util\Utils\Html\Bootstrap\Row;
use Efaturacim\Util\Utils\Html\HtmlDocument;
use Efaturacim\Util\Utils\Html\Datatable\DataTablesJs;
use Efaturacim\Util\Utils\Html\Datatable\DataTablesJsResult;
use Efaturacim\Util\Utils\Html\Form\FormParams;
use Efaturacim\Util\Utils\Html\PrettyPrint\PrettyPrint;
use Efaturacim\Util\Utils\Url\UrlObject;

class DataTableUsage{
    public static function getDemoHtml(&$doc,$urlData=null){
        $s = '';
        $s .= self::showSampleSearchForm($doc);
        //$s .= self::getDemoHtmlForStaticDataTable($doc);
        $s .= self::getDemoHtmlForServerSideDataTable($doc,$urlData);
        return $s;
    }
    public static function showSampleSearchForm(&$doc){
        $s = '';
        $form = BootstrapForm::newForm();
        $form->addTextInput($doc,"search_text1",FormParams::$AUTO,"Arama Metni 1",array());
        $form->addTextInput($doc,"search_text2",FormParams::$AUTO,"Arama Metni 2",array());
        $form->addSubmit($doc,"Ara",array());
        $s .= $form->toHtml($doc);        
        //$s .= PrettyPrint::html($doc,$form->toHtml($doc),null,400,"purebasic");
        $s .= '<div style="height:50px;"></div>';
        return $s;
    }
    public static function getDemoHtmlForStaticDataTable(&$doc){
        $s = '';
    
        // STATIC DATA TABLE
        $s .= '<h3>SABİT VERİLER İLE DATATABE OLUŞTURMA</h3>';
        $dataTableStatic = DataTablesJs::newTable("#","İl Adı","Plaka","Enlem","Boylam","HTML");   
        $dataTableStatic->setLanguage("tr");
        $dataTableStatic->addStaticData(Iller::getIller(),function($index,$row){
            $htmlStr = '-';
            return [$index,@$row["adi"],@$row["plaka"],@$row["lat"],@$row["long"],$htmlStr];
        });
        $s .= ($dataTableHtml = $dataTableStatic->toHtml($doc));        
        $strHtml = ''.PrettyPrint::html($doc,$dataTableHtml,null,400,"purebasic");
        $strJs   = ''.PrettyPrint::js($doc,$dataTableStatic->getJsLinesForDebug(),null,400);
        $s .= "".Row::newRow()->col6($strHtml)->col6($strJs);
        // END OF STATIC DATA TABLE
        return $s;
    }
    public static function getDemoHtmlForServerSideDataTable(&$doc,$urlData=null){
        $s = '';
    
        // STATIC DATA TABLE
        $s .= '<h3>SUNUCU TABLOSU</h3>';
        $dataTable = DataTablesJs::newServerSideTable($urlData,array("#","İl Adı","Plaka","Enlem","Boylam","HTML"));           
        $dataTable->setFullWidth();
        $dataTable->setColumnDef(0,"#",40,true);
        $dataTable->setColumnDef(1,"İl'",200,false);
        $dataTable->setColumnDef(2,"Plaka",100,null);        
        $dataTable->setLanguage("tr");        
        $dataTable->addAllPostData();
        $s .= ($dataTableHtml = $dataTable->toHtml($doc));        
        $strHtml = ''.PrettyPrint::html($doc,$dataTableHtml,null,400,"purebasic");
        $strJs   = ''.PrettyPrint::js($doc,$dataTable->getJsLinesForDebug(),null,400);
        $s .= "".Row::newRow()->col6($strHtml)->col6($strJs);
        // END OF STATIC DATA TABLE
        return $s;
    }    
    public static function getDemoDataWithAjax(){
        $dataOrg         = Iller::getIller();        
        $res  = DataTablesJsResult::newResult(6);        
        $res->handleData(function(DataTablesJsResult &$res) use($dataOrg){            
            $res->recordsTotal = count($dataOrg);                            
            $res->add(1,1,"deneme","search : ".$res->searchText,print_r($_POST,true));            
        });                
        $res->toJsonOutput();
    }
}
?>