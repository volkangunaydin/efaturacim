<?php
namespace Efaturacim\Util\Orkestra\Soap\Util;

use Efaturacim\Util\Orkestra\Soap\Result\OrkestraSoapResult;
use Efaturacim\Util\Orkestra\Soap\Services\OrkestraSoapServiceBase;
use Efaturacim\Util\Orkestra\XML\OrkestraSoapXmlUtil;
use Efaturacim\Util\Utils\Json\JsonUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;

class OrkestraGetPage{
    protected $service = null;
    protected $objectNameOrClass = null;
    protected $pageIndex = 1;
    protected $pageSize = 100;
    protected $options = null;
    protected $period  = null;
    protected $wpClass = null;
    protected $inputType = "xml";
    protected $outputType = "json2";
    protected $classType = -1;
    protected $fields    = [];
    protected $filters   = [];
    protected $result = null;
    public function __construct($service,$objectNameOrClass,$pageIndex=1,$pageSize=100,$options=null){
        $this->service = $service;
        $this->objectNameOrClass = $objectNameOrClass;
        $this->pageIndex = max(0,$pageIndex-1);
        $this->pageSize  = max(1,$pageSize);
        $this->options   = new Options($options);
        $this->result    = null;
    }
    public function addFields($fields){
        $args = func_get_args();
        if(is_array($args) && count($args)>0){
            foreach($args as $field){
                if(is_array($field) && count($field)>0){
                    foreach($field as $f){
                        $this->fields[] = $f;
                    }
                }else if(is_scalar($field) && StrUtil::notEmpty($field)){
                    $this->fields[] = $field;
                }                
            }
        }
        return $this;
    }    
    public function filterByStringEquals($field,$value){        
        $this->filters[] = [
            "Type" => "STRING",
            "StringValue" => "".$value,
            "Property" => $field,
            "Operator" => "="
        ];
        return $this;
    }    
    public function filterByRef($field,$value){
        if(is_array($value)){
            $this->filters[] = [
                "Type" => "STRNIG",
                "StringValue" => implode(",",$value),
                "Property" => $field,
                "Operator" => "IN"
            ];
         
        }else{
            $this->filters[] = [
                "Type" => "LONG",
                "NumValue" => $value,
                "Property" => $field,
                "Operator" => "="
            ];    
        }
        return $this;
    }
    protected function getPeriod(){
        if($this->period && is_int($this->period) && $this->period>0){
            return $this->period;
        }
        if($this->service && $this->service instanceof OrkestraSoapServiceBase){
            $this->period = $this->service->getPeriod();
        }
        return $this->period;
    }
    protected function getXml(){
        $nl = "\r\n";
        $s = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ws="http://ws.server.orkestra.com.tr">';
        $s .= $nl.'<soap:Header/>';
        $s .= $nl.'<soap:Body>';
        $s .= $nl.'<ws:getPage>';
        $s .= $nl.'<params>';
        $s .= $nl.'<Period>'.$this->getPeriod().'</Period>';
        $s .= $nl.'<InputType>'.$this->inputType.'</InputType>';
        $s .= $nl.'<OutputType>'.$this->outputType.'</OutputType>';
        $s .= $nl.'<EntityName>'.$this->wpClass.'</EntityName>';
        $s .= $nl.'<ClassType>'.$this->classType.'</ClassType>';
        $s .= $nl.'<PageIndex>'.$this->pageIndex.'</PageIndex>';
        $s .= $nl.'<PageSize>'.$this->pageSize.'</PageSize>';
        if($this->fields && is_array($this->fields) && count($this->fields)>0){
            foreach($this->fields as $field){
                $s .= $nl.'<Fields>'.$field.'</Fields>';
            }
        }else{
            $s .= $nl.'<Fields>reference</Fields>';
        }
        if($this->filters && is_array($this->filters) && count($this->filters)>0){
            foreach($this->filters as $filter){
                if(is_array($filter) && count($filter)>0){
                    $s .= $nl.'<Filters>';
                    $s .= $nl.'   <Type>'.OrkestraSoapXmlUtil::esc($filter["Type"]).'</Type>';
                    if(key_exists("StringValue",$filter)){
                        $s .= $nl.'   <StringValue>'.OrkestraSoapXmlUtil::esc($filter["StringValue"]).'</StringValue>';    
                    }else{
                        $s .= $nl.'   <NumValue>'.OrkestraSoapXmlUtil::esc($filter["NumValue"]).'</NumValue>';
                    }                                        
                    $s .= $nl.'   <Property>'.OrkestraSoapXmlUtil::esc($filter["Property"]).'</Property>';
                    $s .= $nl.'   <Operator>'.OrkestraSoapXmlUtil::esc($filter["Operator"]).'</Operator>';
                    $s .= $nl.'</Filters>';    
                }
            }
        }
        $s .= $nl.'</params>';
        $s .= $nl.'</ws:getPage>';
        $s .= $nl.'</soap:Body>';
        $s .= $nl.'</soap:Envelope>';
        //\Vulcan\V::dump($s);
        return $s;
    }
    public function getResult(){
        $this->result = new OrkestraSoapResult();
        $wp = OrkestraWorkProducts::getWpInfo($this->objectNameOrClass);
        if($wp && is_array($wp) && count($wp)>0 && strlen("".@$wp["name"])>0){
            if($this->service && $this->service instanceof OrkestraSoapServiceBase){
                $this->wpClass = @$wp["wp"];
                $xml = $this->getXml();
                $resCurl = $this->service->curlExec(null,"getPage",$xml,null,false,null,true,null,null);                    
                if($resCurl->isOk()){                    
                    $this->result->setIsOk(true);
                    $returnString = $resCurl->getStringInBetweenTags("return",true);
                    if($this->outputType == "json2"){
                        $list = JsonUtil::getAsArray($returnString);
                        if($list && is_array($list) && count($list)>0){
                            $this->result->list = $list;
                        }
                        $this->result->value = count($list);
                        $this->result->setAttribute("size",count($list));
                    }else{
                        $this->result->addError("Orkestra servisinin çıktı tipi tanınamadı. Lütfen çıktı tipini kontrol ediniz.[".$this->outputType."]");
                    }                    
                }else{
                    $this->result->addError("Orkestra servisine bağlanılamadı. Lütfen servisini kontrol ediniz.");
                }
            }else{
                $this->result->addError("Orkestra servisi tanınamadı. Lütfen servisini kontrol ediniz.");
            }
        }else{
            $this->result->addError("Orkestra iş nesnesi tanınamadı. Lütfen iş nesne adını kontrol ediniz.[".$this->objectNameOrClass."]");
        }
        return $this->result;
    }
    public function first(){  
        if(is_null($this->result)){            
            $this->getResult();
        }                      
        if($this->result->isOk() && is_array($this->result->list) && count($this->result->list)>0){
            return $this->result->list[0];
        }
        return null;
    }
}
?>