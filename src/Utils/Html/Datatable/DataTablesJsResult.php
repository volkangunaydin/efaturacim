<?php
namespace Efaturacim\Util\Utils\Html\Datatable;

use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Html\Form\FormParams;
use Efaturacim\Util\Utils\Json\JsonUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class DataTablesJsResult{
    private $drawCounter = 1;
    public $recordsTotal    = 0;
    public $recordsFiltered = 0;
    private $data = [];
    private $colSize = 0;
    public  $searchText = null;
    public  $startIndex = 0;
    public  $limit      = 10;
    public  $orderIndex = null;
    public  $orderAsc   = true;    
    public  $orderArray = array();
    public function __construct($colSize=null,$totalSize=null,$filteredData=null){
        if($totalSize && is_int($totalSize) && $totalSize>0){
            $this->recordsTotal = $totalSize;
        }        
        if($filteredData && is_array($filteredData) && count($filteredData)>0){
            $this->recordsFiltered = count($filteredData);
            $this->data = $filteredData;
        }
        if($colSize && is_int($colSize) && $colSize>0){
            $this->colSize = $colSize;
        }
        $startStr = FormParams::getRequestParam("start","",CastUtil::$DATA_STRING);  
        if(strlen("".$startStr)>0){
            $this->drawCounter = StrUtil::coalesce(FormParams::getRequestParam("draw",1),$this->drawCounter);      
            $s = FormParams::getRequestParam("search",array(),CastUtil::$DATA_ARRAY);  
            if($s && is_array($s) && key_exists("value",$s) && strlen("".@$s["value"])>0){
                $this->searchText = trim("".@$s["value"]);
            }
            $this->startIndex = StrUtil::coalesce(FormParams::getRequestParam("start",0,"int"),0);
            $this->limit = StrUtil::coalesce(FormParams::getRequestParam("length",10,"int"),10);
            
            $orderArr = FormParams::getRequestParam("order",array(),CastUtil::$DATA_ARRAY);  
            if($orderArr && is_array($orderArr) && key_exists(0,$orderArr) && key_exists("column",$orderArr[0])){
                $this->orderIndex = @$orderArr[0]["column"];
                $this->orderAsc   = @$orderArr[0]["dir"]=="asc";
                $this->orderArray = $orderArr;
            }            
        }
    }
    public static function newResult($colSize=null,$totalSize=null,$filteredData=null){
        return new DataTablesJsResult($colSize,$totalSize,$filteredData);
    }
    public function toJsonOutput($pretty=false){
        if(count($this->data)>$this->recordsTotal){
            $this->recordsTotal = count($this->data);
        }
        if(count($this->data)>$this->recordsFiltered){
            $this->recordsFiltered = count($this->data);
        }
        $arrJson = array("data"=>$this->data,"recordsTotal"=>$this->recordsTotal,"recordsFiltered"=>$this->recordsFiltered);
        JsonUtil::toJsonOutput((object)$arrJson,array("pretty"=>$pretty));
    }
    public function add($id,$arg1=null){
        $arr = array("DT_RowId"=>$id);
        $args = func_get_args();
        if($this->colSize>0){
            for($i=1;$i<=$this->colSize;$i++){
                if(key_exists($i,$args)){
                    $arr["col".($i-1)] = @$args[$i];
                }else{
                    $arr["col".($i-1)] = "";
                }
            }
        }
        $this->data[] = (object)$arr;
    }
    public function handleData($callback){
        if(!is_null($callback) && is_callable($callback)){
            call_user_func_array($callback,array(&$this));
        }
    }
    public function getParam($name,$defVal=null,$type=null){
        return FormParams::getRequestParam($name,$defVal,$type=null);
    }
}
?>  