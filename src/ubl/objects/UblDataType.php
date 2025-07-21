<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Options;

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
    public function __construct($options=null){
        if($options!=null){
            $this->options = new Options($options);
        }
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
                ($parent ?? $document->root)->appendChild($item->toDOMElement($document));
            }
        }
    }    
    protected function appendChild(&$el,$child){
        if($el && $el instanceof DOMElement && !is_null($child)){
            $el->appendChild($child);
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
    public function loadFromOptions($options,$clear=false){
        if(Options::ensureParam(op: $options) && $options instanceof Options){
            foreach($options->params as $k=>$v){
                if($this->setPropertyFromOptions($k,$v,$options)){

                }else if(property_exists($this,$k) && is_scalar($v)){
                    $this->$k = $v;
                }else if(property_exists($this,$k) && is_array($v) && count($v)>0 && $this->$k instanceof UblDataType){
                    $this->$k->loadFromOptions($v);
                }else{
                    //\Vulcan\V::dump(array($k,$v));
                }                
            }
        }
    }
    
}