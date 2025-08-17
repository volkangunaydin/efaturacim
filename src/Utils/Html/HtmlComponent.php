<?php
namespace Efaturacim\Util\Utils\Html;

use Efaturacim\Util\Utils\Array\AssocArray;

/**
 * Base class for HTML components
 * 
 * This class provides a foundation for creating reusable HTML components.
 * It handles options management, asset paths, and provides a consistent interface
 * for rendering HTML components with optional JavaScript and CSS dependencies.
 * 
 * ## How to Create a Subclass from HtmlComponent
 * 1. Create a new class in the same namespace as HtmlComponent or other namespace if necessary
 * 2. Extend HtmlComponent class
 * 3. Please do not change the __construct parameters and function implement initMe function
 * 4. Please implement toHtmlAsString function
 * 5. Please implement getJsLines function
 * 6. Please implement getJsLinesForInit function
 * 7. Please implement getJsFiles function
 * 8. Please implement getCssFiles function
 * 9. If not required please do not implement toHtml function since  toHtmlAsString,getJsLines,getJsLinesForInit,getJsFiles and getCssFiles build the toHtmlFunction 
 */
class HtmlComponent{  
    /**
     * @var array Global asset paths for components
     */
    public static $PATHS = [];    
    
    /**
     * @var array Component options (merged from constructor parameters)
     */
    protected $options = [];  
    
    /**
     * @var string|null Asset path for this component
     */
    protected $assetPath = null;
    
    /**
     * @var string|null Key for asset path lookup
     */
    protected $assetPathKey = null;
    
    /**
     * Constructor
     * 
     * @param array|null $options Component options (highest priority)
     * @param array|null $defVals Default values (medium priority)
     * @param array|null $initVals Initial values (lowest priority)
     */
    public function __construct($options=null,$defVals=null){        
        $this->options = AssocArray::newArray($options,$defVals,$this->getDefaultOptions());    
        $this->initMe();
        if(!is_null($this->assetPathKey)){
            $this->assetPath = AssocArray::getVal(HtmlComponent::$PATHS,$this->assetPathKey,null);
        }
        
    }
    
    /**
     * Initialize the component
     * 
     * Override this method in subclasses to perform initialization
     * after options have been set. This is the preferred place for
     * component-specific initialization logic.
     */
    public function initMe(){
        // Custom initialization code for sub classes
    }
    
    /**
     * Get global asset paths
     * 
     * @return array
     */
    public static function getPaths(){
        return self::$PATHS;
    }
    
    /**
     * Check if component has an asset path
     * 
     * @return bool
     */
    public function hasAssetPath(){
        return !is_null($this->assetPath) && strlen("".$this->assetPath)>0;
    }
    
    /**
     * Get default options for the component
     * 
     * Override this method in subclasses to define default options.
     * This method should return an array of option names and their default values.
     * 
     * @return array|null Array of default options or null if no defaults
     */
    public function getDefaultOptions(){
        return null;
    }
    
    /**
     * Render the component as HTML string
     * 
     * This is the core method that subclasses must implement.
     * It should return the HTML representation of the component.
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null){
        return 'IMPLEMENT toHtmlAsString FOR '.get_class($this);
    }
    
    /**
     * Get JavaScript code lines for the component
     * 
     * Override this method to return JavaScript code that should be
     * executed for this component. Return an array of JavaScript statements.
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLines(){
        return null;
    }
    
    /**
     * Get JavaScript code lines for component initialization
     * 
     * Override this method to return JavaScript code that should be
     * executed during DOM ready for component initialization.
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLinesForInit(){
        return null;
    }
    
    /**
     * Get JavaScript files required by the component
     * 
     * Override this method to return an array of JavaScript file paths
     * that should be included for this component.
     * 
     * @return array|null Array of JavaScript file paths or null
     */
    public function getJsFiles(){
        return null;
    }
    
    /**
     * Get CSS files required by the component
     * 
     * Override this method to return an array of CSS file paths
     * that should be included for this component.
     * 
     * @return array|null Array of CSS file paths or null
     */
    public function getCssFiles(){
        return null;
    }
    
    /**
     * Convert component to string
     * 
     * @return string HTML string representation
     */
    public function __toString()
    {
        return $this->toHtmlAsString();
    }
    
    /**
     * Render the component with document integration
     * 
     * This method renders the component and optionally integrates
     * with an HtmlDocument to manage dependencies.
     * 
     * @param mixed $doc HtmlDocument instance or null
     * @return string HTML string representation
     */
    public function toHtml($doc){
        $s = $this->toHtmlAsString($doc);        
        if($doc && $doc instanceof HtmlDocument){
            $doc->addCssFiles($this->getCssFiles(),false);
            $doc->addJsFilesOnEnd($this->getJsFiles(),false);
            $doc->addJsLineToDomReady($this->getJsLinesForInit(),null,true);
            $doc->addJsLineToDomReady($this->getJsLines());
        }
        return $s;
    }
    
    /**
     * Get JavaScript code for debugging
     * 
     * Returns formatted JavaScript code including both initialization
     * and regular JavaScript lines for debugging purposes.
     * 
     * @return string Formatted JavaScript code
     */
    public function getJsLinesForDebug(){
        $s  = '';
        $nl = "\r\n";
        $jsLines = $this->getJsLines();
        $jsLinesForInit = $this->getJsLinesForInit();
        if(!is_null($jsLinesForInit) && is_array($jsLinesForInit) && count($jsLinesForInit)>0){
            $s .= $nl."// Js Lines For Init Ensurance";
            foreach($jsLinesForInit as $jsLine){
                $s .= $nl.$jsLine;    
            }            
            $s .= $nl."// End of Js Lines For Init";

        }
        if(!is_null($jsLines) && is_array($jsLines) && count($jsLines)>0){
            foreach($jsLines as $jsLine){
                $s .= $nl.$jsLine;
            }
        }
        return $s;
    }
    
    /**
     * Add global asset paths
     * 
     * @param array $pathArray Associative array of path keys and values
     */
    public static function addPaths($pathArray){
        if(!is_null($pathArray) && is_array($pathArray)){
            foreach($pathArray as $key=>$path){
                self::$PATHS[$key] = $path;
            }
        }        
    }
    
    /**
     * Check if a path is defined
     * 
     * @param string $key Path key to check
     * @return bool True if path exists and is not empty
     */
    public static function isPathDefined($key){
        $p =  AssocArray::getVal(self::$PATHS,$key,null);
        return !is_null($p) && strlen("".$p)>0;
    }
    
    /**
     * Get a defined path
     * 
     * @param string $key Path key to retrieve
     * @return string|null Path value or null if not found/empty
     */
    public static function getPathDefined($key){
        $p =  AssocArray::getVal(self::$PATHS,$key,null);
        return !is_null($p) && strlen("".$p)>0 ? $p : null;
    }
}
?>