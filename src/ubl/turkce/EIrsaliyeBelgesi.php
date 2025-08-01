<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\DespatchAdviceDocument;

class EIrsaliyeBelgesi extends EBelge{
    /**
     * Summary of ubl
     * @var DespatchAdviceDocument
     */
    public $ubl = null;
    
    public function __construct($irsaliyeNo=null){
        parent::__construct("deliveryAdvice");                
    }
    
    public function getIrsaliyeNo(){
        return $this->ubl->getId();
    }
    
    public static function fromXmlFile($xmlFile){
        $a = new EIrsaliyeBelgesi();
        if($xmlFile && strlen("".$xmlFile)>0 && file_exists($xmlFile) && is_readable($xmlFile)){
            $a->ubl->loadFromXml(file_get_contents($xmlFile));            
        }        
        return $a;
    }
}
?> 