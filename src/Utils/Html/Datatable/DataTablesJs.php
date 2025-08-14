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
    public function initMe(){
        $this->tableTag = HtmlTag::table()->initID();
    }
    public function getDefaultOptions(){
        return [
            'asset_dir'=>'',
            'css' => 'https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css',            
            'js' => 'https://cdn.datatables.net/2.3.2/js/dataTables.js',            
        ];
    }
    public function toHtmlAsString(){
        $body = '';
        if(count($this->caps) > 0){            
            $body .= '<thead><tr>';
            foreach($this->caps as $cap){
                if(is_array($cap)){                                        
                    $body .= '<th>'.@$cap['text'].'</th>';
                }
            }
            $body .= '</tr></thead>';    
        }
        $this->tableTag->setInnerHtml($body);
        $s = $this->tableTag->render();
        return $s;
    }
    public function getJsLines(){
    
    }    
    public function addStaticData($dataAsArray){
        if(!is_null($dataAsArray) && is_array($dataAsArray)){
            // TODO: Implement addStaticData
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

    public static function newTable($caps){
        $table = new static();
        $args = func_get_args();
        if(count($args) > 0){
            foreach($args as $arg){
                $table->addCaption($arg);
            }
        }
        return $table;
    }
}
?>  