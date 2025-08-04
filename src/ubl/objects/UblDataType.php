<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMDocumentFragment;
use DOMElement;
use DOMNodeList;
use Efaturacim\Util\Options;
use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;
use Efaturacim\Util\Ubl\UblDocument;
use Efaturacim\Util\Utils\Xml\XmlToArray;

/**
 * Abstract base class for all UBL data objects.
 *
 * This class provides a common structure and helper methods for objects
 * that represent a part of a UBL document, such as Party, Address, or InvoiceLine.
 */
abstract class UblDataType{
    use UblDataTrait;    
    /**
     * Summary of options
     * @var Options
     */
    public $options = null;
    public $textContent = null;
    public $attributes  = array();
    public $defaultTagName = "";
    public function __construct($options=null,$debug=false,$defaultTagName=null){        
        if(!is_null($defaultTagName)){
            $this->defaultTagName = $defaultTagName;
        }
        $this->initMe();
        if($options!=null){
            $this->options = new Options($options);
            $this->loadFromOptions($this->options,false,$debug);
        }        
    }
    public function setDefaultTagNameIfNotSet($tagname){
        if((is_null($this->defaultTagName) || $this->defaultTagName==="") && !is_null($tagname) && strlen("".$tagname)>0){
            $this->defaultTagName = $tagname;
        }
        return $this;
    }
    public function initMe(){
        
    }
    public function setTextContent($textVal){
        $this->textContent = $textVal;
    }
    /**
     * Converts the object to a DOMElement.
     *
     * @param DOMDocument $document The parent DOMDocument.
     * @return DOMElement The generated DOMElement representing this object.
     */
    abstract public function toDOMElement(DOMDocument $document);
    abstract public function setPropertyFromOptions($k,$v,$options);
    /**
     * Helper to create and append a new element with an optional value and attributes.
     *
     * @param DOMDocument $document
     * @param DOMElement $parent
     * @param string $name
     * @param string|null $value
     * @param array $attributes
     * @return DOMElement
     */
    protected function appendElement($document, $parent, string $name, ?string $value, array $attributes = []){
        if(is_null($value) || is_null($parent) ){
            return null;
        }        
        $element = $document->createElement($name, $value);
        foreach ($attributes as $attrName => $attrValue) {
            if(is_null($attrValue)){
                $attrValue = "";
            }
            $element->setAttribute($attrName, $attrValue);
        }
        $parent->appendChild($element);
        return $element;
    }
    protected function appendElementList($document,$list,$parent=null): void{
        if(!is_null($list) && $list instanceof UblDataTypeList && !$list->isEmpty()){
            foreach($list->list as $item){
                if($item!=null){
                    $c = $item->toDOMElement($document);
                    if($c!==null){
                        ($parent ?? $document->root)->appendChild($c);
                    }                    
                }                
            }
        }
    }    
    protected function appendChild(&$el,$child){
        if($el && $el instanceof DOMElement && !is_null($child)){
            if($child instanceof DOMNodeList){
                foreach($child as $ch){
                    $el->appendChild($ch);        
                }        
            }else if ($child instanceof DOMDocumentFragment){
                if ($child->hasChildNodes()) {
                    $el->appendChild($child);    
                }                
            }else{
                $el->appendChild($child);        
            }            
        }
    }
    public function toXml($doc){
        $el = $this->toDOMElement($doc);
        return $doc->saveXML($el);
    }
    public function toArrayOrObject(){        
        $data = get_object_vars($this);        
        unset($data["options"]); // Exclude options object        
        // Recursively convert UblDataType objects to arrays/objects.
        foreach ($data as $key => &$value) {
            if (is_object($value) && $value instanceof UblDataType && $value->isEmpty()) {
                $value = null;
            }else if (is_object($value) && method_exists($value, 'toArrayOrObject')) {
                $value = $value->toArrayOrObject();
            }
        }
        return (object)$data;
    }
    public function toJson(){
        return json_encode($this->toArrayOrObject(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);    
    }
    public function isEmpty(){
        return false;
    }
    public function loadFromOptions($options,$clear=false,$debug=false){
        if(Options::ensureParam(op: $options) && $options instanceof Options){
            $this->loadFromArray($options->params,0,$debug);
        }
    }
    public function toXmlString($document=null){
        if(is_null($document)){
            $document = UblDocument::getNewXmlDocument("Invoice");
        }
        return $document->saveXML($this->toDOMElement($document));
    }   
    public function getAsXmlString($doc=null){
        if(is_null($doc)){
            $doc = UblDocument::getNewXmlDocument("Invoice");
        }
        return  $this->toXmlString($doc);
    }
    public function showAsXml($doc=null,$showOutput=true){
        if(is_null($doc)){
            $doc = UblDocument::getNewXmlDocument("Invoice");
        }
        $xml = $this->toXmlString($doc);
        return PreviewUtil::previewXml($xml,$showOutput);
    }
    public function createElement(DOMDocument $document,$tagName){
        $el = $document->createElement($tagName,"".$this->textContent);                
        if($el===false){
            return null;
        }
        if($this->attributes && count($this->attributes)>0){                        
            foreach($this->attributes as $attrName=>$attrValue){
                if(is_array($attrValue)){
                    foreach($attrValue as $kk=>$vv){
                        if(is_scalar($vv)){
                            $el->setAttribute($kk,$vv);
                        }                        
                    }
                }else if (is_scalar($attrValue)){
                    $el->setAttribute($attrName,$attrValue);
                }                                
            }
        }
        return $el;
    }
    public static function newFromXml($xmlString=null,$debug=false){
        $a = new static();                
        $a->loadFromArray(XmlToArray::xmlStringToArray($xmlString,false,true),0,$debug);
        return $a;
    }
}