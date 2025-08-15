<?php

namespace Efaturacim\Util\Utils\Html;

use Efaturacim\Util\Utils\SimpleResult;

/**
 * HTML Tag utility class for creating and managing HTML elements
 */
class HtmlTag
{
    /**
     * @var string The HTML tag name (e.g., 'div', 'span', 'input')
     */
    protected string $tagName;
        
    /**
     * @var array HTML attributes
     */
    protected array $attributes = [];
    
    /**
     * @var HtmlStyle CSS styles object
     */
    public HtmlStyle $styleObject;
    
    /**
     * @var mixed The content of the HTML element
     */
    protected $content = null;
    protected $innerHtml = null;
    
    
    /**
     * @var bool Whether this is a self-closing tag
     */
    protected bool $selfClosing = false;
    
    /** 
     * @var array List of self-closing HTML tags
     */
    protected static array $selfClosingTags = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 
        'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];
    
    /**
     * @var array Valid HTML5 attributes
     */
    protected static array $validAttributes = [
        // Global attributes
        'accesskey', 'class', 'contenteditable', 'contextmenu', 'data-*', 'dir', 
        'draggable', 'dropzone', 'hidden', 'id', 'lang', 'spellcheck', 'style', 
        'tabindex', 'title', 'translate',
        
        // Event attributes
        'onabort', 'onblur', 'onchange', 'onclick', 'oncontextmenu', 'oncopy', 
        'oncut', 'ondblclick', 'onerror', 'onfocus', 'oninput', 'oninvalid', 
        'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 
        'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onpaste', 
        'onreset', 'onresize', 'onscroll', 'onselect', 'onsubmit', 'onunload',
        
        // Form attributes
        'action', 'autocomplete', 'autofocus', 'checked', 'disabled', 'form', 
        'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget', 
        'list', 'max', 'maxlength', 'min', 'multiple', 'name', 'pattern', 
        'placeholder', 'readonly', 'required', 'size', 'src', 'step', 'type', 
        'value',
        
        // Link attributes
        'href', 'hreflang', 'media', 'rel', 'target',
        
        // Image attributes
        'alt', 'crossorigin', 'height', 'ismap', 'longdesc', 'sizes', 'srcset', 'width',
        
        // Table attributes
        'colspan', 'rowspan', 'headers', 'scope',
        
        // Meta attributes
        'charset', 'content', 'http-equiv', 'name', 'property', 'scheme',
        
        // Script attributes
        'async', 'defer', 'integrity', 'nomodule', 'referrerpolicy',
        
        // Style attributes
        'media', 'scoped', 'type',
        
        // Other common attributes
        'cite', 'datetime', 'download', 'label', 'loop', 'muted', 'poster', 
        'preload', 'sandbox', 'seamless', 'start', 'usemap'
    ];
    
    /**
     * Constructor
     * 
     * @param string $tagName The HTML tag name
     * @param array $attributes HTML attributes
     * @param mixed $content The content of the element
     */
    public function __construct(string $tagName = 'div', array $attributes = [], $content = null)
    {
        $this->setTagName($tagName);
        $this->styleObject = new HtmlStyle();
        $this->setAttributes($attributes);
        $this->setContent($content);
    }
    
    /**
     * Set the tag name
     * 
     * @param string $tagName
     * @return self
     */
    public function setTagName(string $tagName): self
    {
        $this->tagName = strtolower(trim($tagName));
        $this->selfClosing = in_array($this->tagName, self::$selfClosingTags);
        return $this;
    }
    
    /**
     * Get the tag name
     * 
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }
    
    /**
     * Set all attributes at once
     * 
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = [];
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }
    
    /**
     * Set a single attribute
     * 
     * @param string|array $key
     * @param mixed $value
     * @return self
     */
    public function setAttribute($key, $value): self
    {
        $key = strtolower(trim($key));
        
        // Handle boolean attributes
        if (is_bool($value)) {
            if ($value) {
                $this->attributes[$key] = $key;
            } else {
                unset($this->attributes[$key]);
            }
            return $this;
        }
        
        // Handle null values
        if ($value === null) {
            unset($this->attributes[$key]);
            return $this;
        }
        if(is_array($value)){
            $this->attributes[$key] = $value;
        }else{
            $this->attributes[$key] = (string)$value;
        }        
        return $this;
    }
    
    /**
     * Get an attribute value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute(string $key, $default = null)
    {
        $key = strtolower(trim($key));
        return $this->attributes[$key] ?? $default;
    }
    
    /**
     * Remove an attribute
     * 
     * @param string $key
     * @return self
     */
    public function removeAttribute(string $key): self
    {
        $key = strtolower(trim($key));
        unset($this->attributes[$key]);
        return $this;
    }
    
    /**
     * Check if an attribute exists
     * 
     * @param string $key
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        $key = strtolower(trim($key));
        return isset($this->attributes[$key]);
    }
    public function setInnerHtml($html){
        $this->innerHtml = $html;
        return $this;
    }
    public function getInnerHtml(){
        $s = 'dede';
        $s .= $this->innerHtml;
        return $s;
    }
    /**
     * Set the content of the element
     * 
     * @param mixed $content
     * @return self
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Get the content of the element
     * 
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Add content to existing content
     * 
     * @param mixed $content
     * @return self
     */
    public function addContent($content,$key=null): self
    {
        if ($this->content === null) {
            $this->content = $content;
        } elseif (is_array($this->content)) {
            $this->content[] = $content;
        } else {
            $this->content = [$this->content, $content];
        }
        return $this;
    }
    
    /**
     * Set CSS class
     * 
     * @param string|array $classes
     * @return self
     */
    public function setClass($classes): self
    {
        if (is_array($classes)) {
            $classes = implode(' ', array_filter($classes));
        }
        $this->setAttribute('class', $classes);
        return $this;
    }
    
    /**
     * Add CSS class
     * 
     * @param string|array $class
     * @return self
     */
    public function addClass($class,$key=null): self
    {
        $existingClasses = $this->getAttribute('class', array());
        if(!is_array($existingClasses)){
            $existingClasses = array();
        }
        if(!is_null($key)){
            $existingClasses[$key] = $class;
        }else if(!in_array($class,$existingClasses)){
            $existingClasses[] = $class;
        }        
        $this->setAttribute('class', $existingClasses);
        return $this;
    }
    
    /**
     * Remove CSS class
     * 
     * @param string $class
     * @return self
     */
    public function removeClass(string $class): self
    {
        $existingClasses = $this->getAttribute('class', array());
        if(!is_array($existingClasses)){
            $existingClasses = array();
        }
        if(in_array($class,$existingClasses)){
            $existingClasses = array_diff($existingClasses, [$class]);
        }
        $this->setAttribute('class', $existingClasses);
        return $this;
    }
    
    /**
     * Set ID
     * 
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->setAttribute('id', $id);
        return $this;
    }
    
    /**
     * Set inline styles
     * 
     * @param array|string|HtmlStyle $styles
     * @return self
     */
    public function setStyle($styles): self
    {
        if ($styles instanceof HtmlStyle) {
            $this->styleObject = $styles;
        } elseif (is_array($styles)) {
            $this->styleObject = new HtmlStyle($styles);
        } else {
            $this->styleObject = new HtmlStyle($styles);
        }
        return $this;
    }
    
    /**
     * Add styles using HtmlStyle object
     * 
     * @param HtmlStyle $style
     * @return self
     */
    public function addStyle(HtmlStyle $style): self
    {
        $this->styleObject->setProperties($style);
        return $this;
    }
    
    /**
     * Set a single CSS property
     * 
     * @param string $property
     * @param mixed $value
     * @return self
     */
    public function setCssProperty(string $property, $value): self
    {
        $this->styleObject->setProperty($property, $value);
        return $this;
    }
    
    /**
     * Get a CSS property value
     * 
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function getCssProperty(string $property, $default = null)
    {
        return $this->styleObject->getProperty($property, $default);
    }
    
    /**
     * Remove a CSS property
     * 
     * @param string $property
     * @return self
     */
    public function removeCssProperty(string $property): self
    {
        $this->styleObject->removeProperty($property);
        return $this;
    }
    
    /**
     * Check if a CSS property exists
     * 
     * @param string $property
     * @return bool
     */
    public function hasCssProperty(string $property): bool
    {
        return $this->styleObject->hasProperty($property);
    }
    
    /**
     * Get all CSS properties as HtmlStyle object
     * 
     * @return HtmlStyle
     */
    public function getHtmlStyle(): HtmlStyle
    {
        return $this->styleObject;
    }
    
    /**
     * Apply common style presets
     */
    
    /**
     * Apply flexbox styles
     * 
     * @param string $direction
     * @param string $justify
     * @param string $align
     * @return self
     */
    public function flex(string $direction = 'row', string $justify = 'flex-start', string $align = 'stretch'): self
    {
        $style = HtmlStyle::flex($direction, $justify, $align);
        return $this->addStyle($style);
    }
    
    /**
     * Apply grid styles
     * 
     * @param string $template
     * @param string $gap
     * @return self
     */
    public function grid(string $template = '1fr', string $gap = '0'): self
    {
        $style = HtmlStyle::grid($template, $gap);
        return $this->addStyle($style);
    }
    
    /**
     * Apply centered styles
     * 
     * @return self
     */
    public function centered(): self
    {
        $style = HtmlStyle::centered();
        return $this->addStyle($style);
    }
    
    /**
     * Apply hidden styles
     * 
     * @return self
     */
    public function hidden(): self
    {
        $style = HtmlStyle::hidden();
        return $this->addStyle($style);
    }
    
    /**
     * Apply visible styles
     * 
     * @return self
     */
    public function visible(): self
    {
        $style = HtmlStyle::visible();
        return $this->addStyle($style);
    }
    
    /**
     * Apply border styles
     * 
     * @param string $width
     * @param string $style
     * @param string $color
     * @return self
     */
    public function border(string $width = '1px', string $style = 'solid', string $color = '#000'): self
    {
        $borderStyle = HtmlStyle::border($width, $style, $color);
        return $this->addStyle($borderStyle);
    }
    
    /**
     * Apply rounded corners
     * 
     * @param string $radius
     * @return self
     */
    public function rounded(string $radius = '4px'): self
    {
        $style = HtmlStyle::rounded($radius);
        return $this->addStyle($style);
    }
    
    /**
     * Apply shadow
     * 
     * @param string $shadow
     * @return self
     */
    public function shadow(string $shadow = '0 2px 4px rgba(0,0,0,0.1)'): self
    {
        $style = HtmlStyle::shadow($shadow);
        return $this->addStyle($style);
    }
    
    /**
     * Apply text styles
     * 
     * @param string $size
     * @param string $weight
     * @param string $color
     * @return self
     */
    public function text(string $size = '16px', string $weight = 'normal', string $color = '#000'): self
    {
        $style = HtmlStyle::text($size, $weight, $color);
        return $this->addStyle($style);
    }
    
    /**
     * Apply margin
     * 
     * @param string $margin
     * @return self
     */
    public function margin(string $margin = '0'): self
    {
        $style = HtmlStyle::margin($margin);
        return $this->addStyle($style);
    }
    
    /**
     * Apply padding
     * 
     * @param string $padding
     * @return self
     */
    public function padding(string $padding = '0'): self
    {
        $style = HtmlStyle::padding($padding);
        return $this->addStyle($style);
    }
    
    /**
     * Apply background color
     * 
     * @param string $color
     * @return self
     */
    public function background(string $color = '#fff'): self
    {
        $style = HtmlStyle::background($color);
        return $this->addStyle($style);
    }
    
    /**
     * Apply position
     * 
     * @param string $position
     * @return self
     */
    public function position(string $position = 'static'): self
    {
        $style = HtmlStyle::position($position);
        return $this->addStyle($style);
    }
    
    /**
     * Apply width and height
     * 
     * @param string $width
     * @param string $height
     * @return self
     */
    public function size(string $width = 'auto', string $height = 'auto'): self
    {
        $style = HtmlStyle::size($width, $height);
        return $this->addStyle($style);
    }
    
    /**
     * Apply transition
     * 
     * @param string $property
     * @param string $duration
     * @param string $timing
     * @return self
     */
    public function transition(string $property = 'all', string $duration = '0.3s', string $timing = 'ease'): self
    {
        $style = HtmlStyle::transition($property, $duration, $timing);
        return $this->addStyle($style);
    }
    
    /**
     * Set data attribute
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setData(string $key, $value): self
    {
        $this->setAttribute('data-' . $key, $value);
        return $this;
    }
    
    /**
     * Set event handler
     * 
     * @param string $event
     * @param string $handler
     * @return self
     */
    public function setEvent(string $event, string $handler): self
    {
        $this->setAttribute('on' . strtolower($event), $handler);
        return $this;
    }
    /**
     * @return string
     */
    public function toHtml(){
        return $this->render();
    }    
    public function printStartingTag(){
        $html = '<' . $this->tagName;
        
        // Add attributes
        foreach ($this->attributes as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if(is_array($value)){
                $escapedValue = implode(' ',$value);
                $html .= ' ' . $key . '="' . $escapedValue . '"';            
            }else{
                $escapedValue = htmlspecialchars("".$value, ENT_QUOTES, 'UTF-8');
                $html .= ' ' . $key . '="' . $escapedValue . '"';    
            }
            // Escape attribute values
        }
        
        // Add style attribute from HtmlStyle object if it has properties
        if (!empty($this->styleObject->getProperties())) {
            $styleString = $this->styleObject->toString();
            if (!empty($styleString)) {
                $html .= ' style="' . htmlspecialchars($styleString, ENT_QUOTES, 'UTF-8') . '"';
            }
        }
        
        if ($this->selfClosing) {
            $html .= ' />';
        } else {
            $html .= '>';
        }
        return $html;
    }
    public function printEndingTag(){
        if (!$this->selfClosing) {
            return '</' . $this->tagName . '>';
        }
        return '';
    }
    public function getId($ensureId=true){
        $id = $this->getAttribute("id",null);
        if((is_null($id) || empty($id)) && $ensureId){            
            $id = $this->initID()->getAttribute("id",null);
        }
        return $id;
    }
    public function initID(){
        $id = $this->getAttribute("id",null);
        if(is_null($id) || empty($id)){
            return $this->setId(uniqid('id_'));  
        }
        return $this;      
    }
    /**
     * Render the HTML element
     * 
     * @return string
     */
    public function render(): string
    {
        $html = $this->printStartingTag();    
        if (!$this->selfClosing) {
            // Add content
            if ($this->content !== null) {
                if (is_array($this->content)) {
                    foreach ($this->content as $item) {
                        $html .= $this->renderContent($item);
                    }
                } else {
                    $html .= $this->renderContent($this->content);
                }
            }
            if(!is_null($this->innerHtml) && !empty($this->innerHtml)){
                $html .= $this->innerHtml;
            }
            $html .= $this->printEndingTag();
        }            
        return $html;
    }
    
    /**
     * Render content item
     * 
     * @param mixed $content
     * @return string
     */
    protected function renderContent($content): string
    {
        if ($content instanceof HtmlTag) {
            return $content->render();
        } elseif (is_string($content)) {
            return (string)$content;
            //return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        } elseif (is_numeric($content)) {
            return (string)$content;
        } elseif (is_bool($content)) {
            return $content ? 'true' : 'false';
        } elseif (is_array($content)) {
            return json_encode($content);
        } else {
            return (string)$content;
        }
    }
    
    /**
     * Convert to string
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
    
    /**
     * Create a new HtmlTag instance
     * 
     * @param string $tagName
     * @param array $attributes
     * @param mixed $content
     * @return self
     */
    public static function create(string $tagName = 'div', array $attributes = [], $content = null): self
    {
        return new self($tagName, $attributes, $content);
    }
    
    /**
     * Create common HTML elements
     */
    public static function div(array $attributes = [], $content = null): self
    {
        return new self('div', $attributes, $content);
    }
    
    public static function span(array $attributes = [], $content = null): self
    {
        return new self('span', $attributes, $content);
    }
    
    public static function p(array $attributes = [], $content = null): self
    {
        return new self('p', $attributes, $content);
    }
    
    public static function a(array $attributes = [], $content = null): self
    {
        return new self('a', $attributes, $content);
    }
    
    public static function img(array $attributes = []): self
    {
        return new self('img', $attributes);
    }
    
    public static function input(array $attributes = []): self
    {
        return new self('input', $attributes);
    }
    
    public static function button(array $attributes = [], $content = null): self
    {
        return new self('button', $attributes, $content);
    }
    
    public static function form(array $attributes = [], $content = null): self
    {
        return new self('form', $attributes, $content);
    }
    
    public static function table(array $attributes = [], $content = null): self
    {
        return new self('table', $attributes, $content);
    }
    
    public static function tr(array $attributes = [], $content = null): self
    {
        return new self('tr', $attributes, $content);
    }
    
    public static function td(array $attributes = [], $content = null): self
    {
        return new self('td', $attributes, $content);
    }
    
    public static function th(array $attributes = [], $content = null): self
    {
        return new self('th', $attributes, $content);
    }
    
    public static function ul(array $attributes = [], $content = null): self
    {
        return new self('ul', $attributes, $content);
    }
    
    public static function li(array $attributes = [], $content = null): self
    {
        return new self('li', $attributes, $content);
    }
    
    public static function h1(array $attributes = [], $content = null): self
    {
        return new self('h1', $attributes, $content);
    }
    
    public static function h2(array $attributes = [], $content = null): self
    {
        return new self('h2', $attributes, $content);
    }
    
    public static function h3(array $attributes = [], $content = null): self
    {
        return new self('h3', $attributes, $content);
    }
    
    public static function h4(array $attributes = [], $content = null): self
    {
        return new self('h4', $attributes, $content);
    }
    
    public static function h5(array $attributes = [], $content = null): self
    {
        return new self('h5', $attributes, $content);
    }
    
    public static function h6(array $attributes = [], $content = null): self
    {
        return new self('h6', $attributes, $content);
    }
    
    /**
     * Additional common HTML elements
     */
    public static function textarea(array $attributes = [], $content = null): self
    {
        return new self('textarea', $attributes, $content);
    }
    
    public static function select(array $attributes = [], $content = null): self
    {
        return new self('select', $attributes, $content);
    }
    
    public static function option(array $attributes = [], $content = null): self
    {
        return new self('option', $attributes, $content);
    }
    
    public static function label(array $attributes = [], $content = null): self
    {
        return new self('label', $attributes, $content);
    }
    
    public static function fieldset(array $attributes = [], $content = null): self
    {
        return new self('fieldset', $attributes, $content);
    }
    
    public static function legend(array $attributes = [], $content = null): self
    {
        return new self('legend', $attributes, $content);
    }
    
    public static function nav(array $attributes = [], $content = null): self
    {
        return new self('nav', $attributes, $content);
    }
    
    public static function header(array $attributes = [], $content = null): self
    {
        return new self('header', $attributes, $content);
    }
    
    public static function footer(array $attributes = [], $content = null): self
    {
        return new self('footer', $attributes, $content);
    }
    
    public static function main(array $attributes = [], $content = null): self
    {
        return new self('main', $attributes, $content);
    }
    
    public static function section(array $attributes = [], $content = null): self
    {
        return new self('section', $attributes, $content);
    }
    
    public static function article(array $attributes = [], $content = null): self
    {
        return new self('article', $attributes, $content);
    }
    
    public static function aside(array $attributes = [], $content = null): self
    {
        return new self('aside', $attributes, $content);
    }
    
    public static function strong(array $attributes = [], $content = null): self
    {
        return new self('strong', $attributes, $content);
    }
    
    public static function em(array $attributes = [], $content = null): self
    {
        return new self('em', $attributes, $content);
    }
    
    public static function code(array $attributes = [], $content = null): self
    {
        return new self('code', $attributes, $content);
    }
    
    public static function pre(array $attributes = [], $content = null): self
    {
        return new self('pre', $attributes, $content);
    }
    
    public static function blockquote(array $attributes = [], $content = null): self
    {
        return new self('blockquote', $attributes, $content);
    }
    
    public static function cite(array $attributes = [], $content = null): self
    {
        return new self('cite', $attributes, $content);
    }
    
    public static function small(array $attributes = [], $content = null): self
    {
        return new self('small', $attributes, $content);
    }
    
    public static function mark(array $attributes = [], $content = null): self
    {
        return new self('mark', $attributes, $content);
    }
    
    public static function time(array $attributes = [], $content = null): self
    {
        return new self('time', $attributes, $content);
    }
    
    public static function figure(array $attributes = [], $content = null): self
    {
        return new self('figure', $attributes, $content);
    }
    
    public static function figcaption(array $attributes = [], $content = null): self
    {
        return new self('figcaption', $attributes, $content);
    }
    
    public static function canvas(array $attributes = []): self
    {
        return new self('canvas', $attributes);
    }
    
    public static function video(array $attributes = [], $content = null): self
    {
        return new self('video', $attributes, $content);
    }
    
    public static function audio(array $attributes = [], $content = null): self
    {
        return new self('audio', $attributes, $content);
    }
    
    public static function source(array $attributes = []): self
    {
        return new self('source', $attributes);
    }
    
    public static function track(array $attributes = []): self
    {
        return new self('track', $attributes);
    }
    
    public static function embed(array $attributes = []): self
    {
        return new self('embed', $attributes);
    }
    
    public static function object(array $attributes = [], $content = null): self
    {
        return new self('object', $attributes, $content);
    }
    
    public static function param(array $attributes = []): self
    {
        return new self('param', $attributes);
    }
    
    public static function iframe(array $attributes = [], $content = null): self
    {
        return new self('iframe', $attributes, $content);
    }
    
    public static function map(array $attributes = [], $content = null): self
    {
        return new self('map', $attributes, $content);
    }
    
    public static function area(array $attributes = []): self
    {
        return new self('area', $attributes);
    }
    
    public static function svg(array $attributes = [], $content = null): self
    {
        return new self('svg', $attributes, $content);
    }
    
    public static function math(array $attributes = [], $content = null): self
    {
        return new self('math', $attributes, $content);
    }
    
    public static function script(array $attributes = [], $content = null): self
    {
        return new self('script', $attributes, $content);
    }
    
    public static function noscript(array $attributes = [], $content = null): self
    {
        return new self('noscript', $attributes, $content);
    }
    
    public static function style(array $attributes = [], $content = null): self
    {
        return new self('style', $attributes, $content);
    }
    
    public static function link(array $attributes = []): self
    {
        return new self('link', $attributes);
    }
    
    public static function meta(array $attributes = []): self
    {
        return new self('meta', $attributes);
    }
    
    public static function title(array $attributes = [], $content = null): self
    {
        return new self('title', $attributes, $content);
    }
    
    public static function base(array $attributes = []): self
    {
        return new self('base', $attributes);
    }
    
    public static function head(array $attributes = [], $content = null): self
    {
        return new self('head', $attributes, $content);
    }
    
    public static function body(array $attributes = [], $content = null): self
    {
        return new self('body', $attributes, $content);
    }
    
    public static function html(array $attributes = [], $content = null): self
    {
        return new self('html', $attributes, $content);
    }
    
    public static function doctype(): string
    {
        return '<!DOCTYPE html>';
    }
}
?>
