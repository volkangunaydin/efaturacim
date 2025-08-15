<?php
namespace Efaturacim\Util\Utils\Html\Datatable;
use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlTag;
use Efaturacim\Util\Utils\Html\Js\JsOptions;
use Efaturacim\Util\Utils\Url\UrlUtil;

class DataTablesJs extends HtmlComponent{
    /**
     * @var HtmlTag
     */
    protected $tableTag = null;
    protected $caps     = [];
    protected $columnDefs = [];
    protected $staticData = [];
    /** @var JsOptions */
    public    $jsOption   = null;
    protected $postData   = [];
    public function initMe(){
        $this->tableTag = HtmlTag::table()->initID();
        $this->tableTag->addClass("display");
        $this->assetPathKey = "datatable";
        $this->jsOption = new JsOptions();

    }
    public function getDefaultOptions(){
        return [            
            'css1' => 'https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css',            
            'js1' => 'https://cdn.datatables.net/2.3.2/js/dataTables.js',                        
        ];
    }
    protected function getColumnsAsArray(){
        $columns = [];
        if(count($this->caps)>0){
            $i=0;
            foreach($this->caps as $cap){
                if(!key_exists($i,$this->columnDefs)){
                    $this->setColumnDef($i,@$cap["text"]);
                }
                $i++;
            }
        }        
        foreach($this->columnDefs as $column){
            $index = 0 + $column["index"];
            $columns[] = array("data"=>"col".$index,"title"=>@$column["title"]);
        }
        return $columns;
    }
    protected function getColumnDefsAsArray(){
        $columnDefs = [];
        foreach($this->columnDefs as $column){
            $prop = array("targets"=>array($column["index"]));
            if(is_numeric($column["width"])){
                $prop["width"] = $column["width"]."px";
            }
            if(is_bool($column["orderable"])){
                $prop["orderable"] = $column["orderable"];
            }
            $columnDefs[] = $prop;
        }
        return $columnDefs;
    }
    public function getJsLinesForInit(){
        return ['init_csrf'=>'let csrfToken = $(\'meta[name="csrf-token"]\').attr(\'content\'); if (csrfToken) { $.ajaxSetup({headers: {\'X-CSRF-TOKEN\': csrfToken } }); }' ];
    }
    public function getJsLines(){
        if(count($this->postData)>0){
            $ajax = $this->jsOption->getOptionAsRef("ajax");    
            if(is_array($ajax) ){
                $ajax["data"] = "function(d){";
                foreach($this->postData as $key=>$value){
                    if(in_array($key,array("_token","_dtaction"))){
                        continue;
                    }
                    $ajax["data"] .= " d.".$key." = '".json_encode($value,JSON_UNESCAPED_UNICODE)."'; ";
                }
                $ajax["data"] .= "}";
            }
            $this->jsOption->setOption("ajax",$ajax);
        }
        $this->jsOption->setOption("columns",$this->getColumnsAsArray());            
        if(count($this->columnDefs)>0){
            $this->jsOption->setOption("columnDefs",$this->getColumnDefsAsArray());            
        }
        $arr = array();        
        $arr[] = 'let '.$this->tableTag->getId().' = new DataTable("#'.$this->tableTag->getId().'",'.$this->jsOption->toJson().');';        
        return $arr;
    }   
    public function getJsFiles(){
        if($this->hasAssetPath()){
            return ['jquery'=>null,'datatable'=>$this->assetPath."datatables.min.js"];   
        }else{
            return ['jquery'=>@$this->options['jquery'],'datatable'=>@$this->options['js']];
        }        
    }
    public function getCssFiles(){
        if($this->hasAssetPath()){
            return ['bootstrap'=>null,'datatable'=>$this->assetPath."datatables.min.css"];
        }else{
            return ['bootstrap'=>null,'datatable'=>@$this->options['css']];
        }
        
    }
    public function toHtml($doc)
    {
        return parent::toHtml($doc);
    }
    public function addCaptions($caps=null){
        $captions = func_get_args();
        if(is_null($caps)){
            return $this;
        }
        if(!is_null($caps) && is_array($caps) && count($captions)==1){
            foreach($caps as $cap){
                $this->addCaption($cap);
            }    
        }else if (count($captions)>1){
            foreach($captions as $cap){
                $this->addCaption($cap);
            }    
        }
        return $this;
    }
    public function toHtmlAsString(){
        $body = '';
        $nl = "\r\n";        
        if(count($this->caps) > 0){            
            $body .= $nl.'<thead><tr>';
            foreach($this->caps as $cap){
                if(is_array($cap)){                                        
                    $body .= '<th>'.@$cap['text'].'</th>';
                } else {
                    $body .= '<th>'.$cap.'</th>';
                }
            }
            $body .= $nl.'</tr></thead>';    
        }    
        
        if(count($this->staticData) > 0){            
            $body .= $nl.'<tbody>';
            foreach($this->staticData as $row){
                $body .= $nl.'<tr>';
                foreach($row as $cell){
                    $body .= $nl.'<td>'.$cell.'</td>';
                }
                $body .= $nl.'</tr>';
            }
            $body .= $nl.'</tbody>';
        }
        $this->tableTag->setInnerHtml($body);
        $s = $this->tableTag->render();
        return $s;
    }
    public function addStaticData($dataAsArray,$mapFunction=null){
        if(!is_null($dataAsArray) && is_array($dataAsArray) && count($dataAsArray)>0){
            if(!is_null($mapFunction) && is_callable($mapFunction)){
                $index = 0;
                foreach($dataAsArray as $row){
                    $index++;
                    $this->staticData[] = call_user_func_array($mapFunction,array($index,$row));
                }
            }else{
                $this->staticData = $dataAsArray;
            }
        }        
        return $this;
    }
    
    public function addCaption($caption){
        if(!is_null($caption) && !empty($caption)){
            if(is_scalar($caption)){
               $this->caps[] = array("text"=>$caption); 
            }else if(is_array($caption)){
                $this->caps[] = $caption;
            }
        }
        return $this;
    }

    /**
     * Create a new DataTable with captions
     * 
     * @param mixed ...$captions Variable number of caption arguments
     * @return static
     */
    public static function newTable($caps=null){
        $table = new static();
        $args = func_get_args();
        if(count($args)>0){
            $table->addCaptions($args);
        }
        return $table;
    }
    public static function newServerSideTable($url=null,$capsAsArray=null,$options=null){
        $table = new static($options);        
        if($capsAsArray && is_array($capsAsArray) && count($capsAsArray)){
            $table->setCaptions($capsAsArray);            
        }        
        if(is_null($url)){
            $url = UrlUtil::getUrl(null,array("__dtaction"=>"data"))->toUrlString();
        }
        $table->jsOption->setOption("serverSide",true);
        $table->jsOption->setOption("ajax",array("url"=>$url,"type"=>"POST"));
        return $table;
    }
    
    /**
     * Get all captions
     * 
     * @return array
     */
    public function getCaptions(): array
    {
        return $this->caps;
    }
    
    /**
     * Clear all captions
     * 
     * @return self
     */
    public function clearCaptions(): self
    {
        $this->caps = [];
        return $this;
    }
    
    /**
     * Set captions from array
     * 
     * @param array $captions
     * @return self
     */
    public function setCaptions(array $captions): self
    {
        $this->clearCaptions();
        foreach ($captions as $caption) {
            $this->addCaption($caption);
        }
        return $this;
    }
    public function setLanguage($langOrUrl){
        if(strlen("".$langOrUrl)>0 && strlen("".$langOrUrl)<=3){
            $this->jsOption->setOption("language",(object)array("url"=>HtmlComponent::getPathDefined("datatable")."".$langOrUrl.".json"));
        }else if(strlen("".$langOrUrl)>0 && strlen("".$langOrUrl)>3){
            $this->jsOption->setOption("language",(object)array("url"=>$langOrUrl));
        }
        
        return $this;
    }
    public function setFullWidth(){
        $this->tableTag->styleObject->setProperty("width","100%")->setProperty("table-layout","fixed");
        return $this;
    }
    public function setColumnDef($index,$caption=null,$width=null,$orderable=null){
        if(is_null($caption) && key_exists($index,$this->caps)){
            $caption = @$this->caps[$index]["text"];
        }
        $this->columnDefs[$index] = array("index"=>$index,"title"=>$caption,"width"=>$width,"orderable"=>$orderable);
        return $this;
    }
    public function addAllPostData(){
        foreach($_POST as $key=>$value){
            $this->addPostData($key,$value);
        }
        return $this;
    }
    public function addPostData($key,$value){
        $this->postData[$key] = $value;
        return $this;
    }
}
?>  