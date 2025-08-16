<?php
namespace Efaturacim\Util\Usage\Html;

use Efaturacim\Util\Data\GeoData\TR\Iller;
use Efaturacim\Util\Utils\Array\ArrayFilter;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Html\Bootstrap\Badge;
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
        $dataTable->setColumnDef(1,"İl'",200,true);
        $dataTable->setColumnDef(2,"Plaka",100,true);        
        $dataTable->setColumnDef(3,"Enlem",100,false);
        $dataTable->setColumnDef(4,"Boylam",100,false);
        $dataTable->setColumnDef(5,"HTML",null,false);
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
            $searchText1 = $res->getParam("search_text1",null);
            $searchText2 = $res->getParam("search_text2",null);                     
            $res->recordsTotal = count($dataOrg);                            
            $htmlStr = '';            
            $useFieldsForSearch = array("adi","plaka");            
            $dataFiltered = ArrayFilter::filterSmart($dataOrg,array($res->searchText,$searchText1,$searchText2),$res->startIndex,$res->limit,$res->orderIndex,$res->orderAsc,$useFieldsForSearch);            
            $i =0;            
            foreach($dataFiltered as $row){
                $i++;
                $id = $row["id"];
                if($i==1){
                    $htmlStr  .= Badge::primary("Tablo Arama metni : ".$res->searchText)->setBlock(true);
                    $htmlStr  .= Badge::success("Arama Metni 1 : ".$searchText1)->setBlock(true);
                    $htmlStr  .= Badge::danger("Arama Metni 2 : ".$searchText2)->setBlock(true);    
                    $htmlStr  .= Badge::warning("Sıralama : ".$res->orderIndex." - ".($res->orderAsc?"Artan":"Azalan"))->setBlock(true);    
                }                
                $res->add($id,$i,@$row["adi"],@$row["plaka"],@$row["lat"],@$row["long"],$htmlStr);            
            }                                    
        });                
        $res->toJsonOutput(true);
    }
}
?>