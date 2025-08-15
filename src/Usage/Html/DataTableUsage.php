<?php
namespace Efaturacim\Util\Usage\Html;

use Efaturacim\Util\Data\GeoData\TR\Iller;
use Efaturacim\Util\Utils\Html\Bootstrap\Row;
use Efaturacim\Util\Utils\Html\HtmlDocument;
use Efaturacim\Util\Utils\Html\Datatable\DataTablesJs;
use Efaturacim\Util\Utils\Html\PrettyPrint\PrettyPrint;

class DataTableUsage{
    public static function getDemoHtml(&$doc){
        $s = '';
        $s .= self::getDemoHtmlForStaticDataTable($doc);
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
}
?>