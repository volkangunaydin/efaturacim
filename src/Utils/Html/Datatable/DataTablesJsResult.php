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
        $this->drawCounter = StrUtil::coalesce(FormParams::getRequestParam("draw",1),$this->drawCounter);      
        $s = FormParams::getRequestParam("search",array(),CastUtil::$DATA_ARRAY);  
        if($s && is_array($s) && key_exists("value",$s) && strlen("".@$s["value"])>0){
            $this->searchText = trim("".@$s["value"]);
        }
    }
    public static function newResult($colSize=null,$totalSize=null,$filteredData=null){
        return new DataTablesJsResult($colSize,$totalSize,$filteredData);
    }
    public function toJsonOutput(){
        if(count($this->data)>$this->recordsTotal){
            $this->recordsTotal = count($this->data);
        }
        if(count($this->data)>$this->recordsFiltered){
            $this->recordsFiltered = count($this->data);
        }
        $arrJson = array("data"=>$this->data,"recordsTotal"=>$this->recordsTotal,"recordsFiltered"=>$this->recordsFiltered);
        JsonUtil::toJsonOutput((object)$arrJson,array());
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
}
?>  