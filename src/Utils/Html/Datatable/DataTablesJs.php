<?php
namespace Efaturacim\Util\Utils\Html\Datatable;
use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlTag;

class DataTablesJs extends HtmlComponent{
    /**
     * @var HtmlTag
     */
    protected $tableTag = null;
    protected $caps     = [];
    protected $staticData = [];
    public function initMe(){
        $this->tableTag = HtmlTag::table()->initID();
        $this->tableTag->addClass("display");
    }
    public function getDefaultOptions(){
        return [            
            'css1' => 'https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css',            
            'js1' => 'https://cdn.datatables.net/2.3.2/js/dataTables.js',            
            'jquery'=>'https://code.jquery.com/jquery-3.7.1.min.js',
        ];
    }
    public function getJsLines(){
        return ['let '.$this->tableTag->getId().' = new DataTable("#'.$this->tableTag->getId().'");'];
    }   
    public function getJsFiles(){
        return ['jquery'=>@$this->options['jquery'],'datatable'=>@$this->options['js']];
    }
    public function getCssFiles(){
        return ['bootstrap'=>null,'datatable'=>@$this->options['css']];
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
}
?>  