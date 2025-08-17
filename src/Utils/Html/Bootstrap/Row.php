<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;

use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Html\HtmlTag;

/**
 * Bootstrap Row Component
 * 
 * Creates Bootstrap grid rows with various column configurations.
 * Supports responsive breakpoints, gutters, alignment, and custom styling.
 */
class Row extends HtmlComponent{
    /** @var HtmlTag */
    protected $tag = null;
    
    public function initMe(){
        $this->tag = HtmlTag::div()->addClass("row","default");
        
        // Apply custom classes if specified
        if (isset($this->options['class']) && !empty($this->options['class'])) {
            $this->tag->addClass($this->options['class']);
        }
        
        // Apply custom styles if specified
        if (isset($this->options['style']) && !empty($this->options['style'])) {
            $this->tag->setAttribute('style', $this->options['style']);
        }
        
        // Apply custom ID if specified
        if (isset($this->options['id']) && !empty($this->options['id'])) {
            $this->tag->setAttribute('id', $this->options['id']);
        }
    }
    
    /**
     * Get default options for the row
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'class' => '',
            'style' => '',
            'id' => '',
            'gutter' => true,
            'align' => '',
            'justify' => '',
            'noGutters' => false,
            'container' => false
        ];
    }
    
    public function toHtmlAsString($doc = null){
        $html = $this->tag->render();
        
        // Apply container wrapper if specified
        if (isset($this->options['container']) && $this->options['container']) {
            $containerClass = 'container';
            
            // If container is set to 'fluid', use container-fluid
            if ($this->options['container'] === 'fluid') {
                $containerClass = 'container-fluid';
            }
            
            $html = '<div class="' . $containerClass . '">' . $html . '</div>';
        }
        
        return $html;
    }
    
    /**
     * Create a new row instance
     * 
     * @param array|null $options Custom options
     * @param array|null $defVals Default values
     * @return static
     */
    public static function newRow($options=null,$defVals=null){
        return (new static($options,$defVals));
    }
    
    /**
     * Add a column with specified size
     * 
     * @param int $size Column size (1-12)
     * @param string|null $content Column content
     * @param array $options Column options
     * @return $this
     */
    public function col($size, $content = null, $options = []) {
        $colClass = "col-{$size}";
        
        // Add responsive breakpoints
        if (isset($options['sm'])) $colClass .= " col-sm-{$options['sm']}";
        if (isset($options['md'])) $colClass .= " col-md-{$options['md']}";
        if (isset($options['lg'])) $colClass .= " col-lg-{$options['lg']}";
        if (isset($options['xl'])) $colClass .= " col-xl-{$options['xl']}";
        if (isset($options['xxl'])) $colClass .= " col-xxl-{$options['xxl']}";
        
        $colTag = HtmlTag::div()->addClass($colClass);
        
        // Add custom classes
        if (isset($options['class'])) {
            $colTag->addClass($options['class']);
        }
        
        // Add custom styles
        if (isset($options['style'])) {
            $colTag->setAttribute('style', $options['style']);
        }
        
        // Add custom ID
        if (isset($options['id'])) {
            $colTag->setAttribute('id', $options['id']);
        }
        
        if ($content !== null) {
            $colTag->setInnerHtml($content);
        }
        
        $this->tag->addContent($colTag);
        return $this;
    }
    
    /**
     * Add a column with size 6 (half width)
     * 
     * @param string|null $content Column content
     * @return $this
     */
    public function col6($content=null,$options=null){
        return $this->col(6, $content);
    }
    
    /**
     * Add a column with size 4 (one-third width)
     * 
     * @param string|null $content Column content
     * @return $this
     */
    public function col4($content=null){
        return $this->col(4, $content);
    }
    
    /**
     * Add a column with size 3 (quarter width)
     * 
     * @param string|null $content Column content
     * @return $this
     */
    public function col3($content=null){
        return $this->col(3, $content);
    }
    
    /**
     * Add a column with size 8 (two-thirds width)
     * 
     * @param string|null $content Column content
     * @return $this
     */
    public function col8($content=null){
        return $this->col(8, $content);
    }
    
    /**
     * Add a column with size 12 (full width)
     * 
     * @param string|null $content Column content
     * @return $this
     */
    public function col12($content=null){
        return $this->col(12, $content);
    }
    
    /**
     * Set row vertical alignment
     * 
     * @param string $align Alignment type (start, center, end, baseline, stretch) or intuitive values (top, bottom)
     * @return $this
     */
    public function verticalAlign($align) {
        // Map intuitive values to Bootstrap classes, or use direct Bootstrap values
        $bootstrapClass = match($align) {
            // Intuitive values
            'top' => 'start',
            'bottom' => 'end',
            // Direct Bootstrap values
            'start' => 'start',
            'center' => 'center',
            'end' => 'end',
            'baseline' => 'baseline',
            'stretch' => 'stretch',
            default => 'start' // Default to start if invalid value
        };        
        $this->tag->addClass("align-items-{$bootstrapClass}");
        return $this;
    }
    
    /**
     * Set row horizontal alignment (justify-content)
     * 
     * @param string $align Alignment type (start, center, end, between, around, evenly) or intuitive values (left, right)
     * @return $this
     */
    public function horizontalAlign($align) {
        // Map intuitive values to Bootstrap classes, or use direct Bootstrap values
        $bootstrapClass = match($align) {
            // Intuitive values
            'left' => 'start',
            'right' => 'end',
            // Direct Bootstrap values
            'start' => 'start',
            'center' => 'center',
            'end' => 'end',
            'between' => 'between',
            'around' => 'around',
            'evenly' => 'evenly',
            default => 'start' // Default to start if invalid value
        };
        
        $this->tag->addClass("justify-content-{$bootstrapClass}");
        return $this;
    }
    
    /**
     * Alias for verticalAlign for backward compatibility
     * 
     * @param string $align Alignment type (top, center, bottom, baseline, stretch) or horizontal values (start, center, end, between, around, evenly)
     * @return $this
     */
    public function align($align) {
        if(in_array("".$align,["top","bottom"])){
            return $this->verticalAlign($align);
        }else{
            return $this->horizontalAlign($align);
        }        
    }
    
    /**
     * Set row justification
     * 
     * @param string $justify Justification type (start, center, end, between, around, evenly)
     * @return $this
     */
    public function justify($justify) {
        $this->tag->addClass("justify-content-{$justify}");
        return $this;
    }
    
    /**
     * Remove gutters from the row
     * 
     * @return $this
     */
    public function noGutters() {
        $this->tag->addClass("g-0");
        return $this;
    }
    
    /**
     * Set custom gutters
     * 
     * @param int $size Gutter size (0-5)
     * @return $this
     */
    public function gutters($size) {
        $this->tag->addClass("g-{$size}");
        return $this;
    }
    
    /**
     * Add custom class to the row
     * 
     * @param string $class CSS class
     * @return $this
     */
    public function addClass($class) {
        $this->tag->addClass($class);
        return $this;
    }
    
    /**
     * Set custom style to the row
     * 
     * @param string $style CSS style
     * @return $this
     */
    public function setStyle($style) {
        $this->tag->setAttribute('style', $style);
        return $this;
    }
    
    /**
     * Set custom ID to the row
     * 
     * @param string $id Element ID
     * @return $this
     */
    public function setId($id) {
        $this->tag->setAttribute('id', $id);
        return $this;
    }
    
    // MARGIN UTILITIES - MARGIN YARDIMCILARI
    
    /**
     * Add margin to all sides
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function margin($size) {
        $this->tag->addClass("m-{$size}");
        return $this;
    }
    
    /**
     * Add margin top
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginTop($size) {
        $this->tag->addClass("mt-{$size}");
        return $this;
    }
    
    /**
     * Add margin bottom
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginBottom($size) {
        $this->tag->addClass("mb-{$size}");
        return $this;
    }
    
    /**
     * Add margin left
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginLeft($size) {
        $this->tag->addClass("ms-{$size}");
        return $this;
    }
    
    /**
     * Add margin right
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginRight($size) {
        $this->tag->addClass("me-{$size}");
        return $this;
    }
    
    /**
     * Add margin horizontal (left and right)
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginX($size) {
        $this->tag->addClass("mx-{$size}");
        return $this;
    }
    
    /**
     * Add margin vertical (top and bottom)
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function marginY($size) {
        $this->tag->addClass("my-{$size}");
        return $this;
    }
    
    // PADDING UTILITIES - PADDING YARDIMCILARI
    
    /**
     * Add padding to all sides
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function padding($size) {
        $this->tag->addClass("p-{$size}");
        return $this;
    }
    
    /**
     * Add padding top
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingTop($size) {
        $this->tag->addClass("pt-{$size}");
        return $this;
    }
    
    /**
     * Add padding bottom
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingBottom($size) {
        $this->tag->addClass("pb-{$size}");
        return $this;
    }
    
    /**
     * Add padding left
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingLeft($size) {
        $this->tag->addClass("ps-{$size}");
        return $this;
    }
    
    /**
     * Add padding right
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingRight($size) {
        $this->tag->addClass("pe-{$size}");
        return $this;
    }
    
    /**
     * Add padding horizontal (left and right)
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingX($size) {
        $this->tag->addClass("px-{$size}");
        return $this;
    }
    
    /**
     * Add padding vertical (top and bottom)
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function paddingY($size) {
        $this->tag->addClass("py-{$size}");
        return $this;
    }
    
    // SHORTCUT ALIASES - KISAYOL TAKMA ADLAR
    
    /**
     * Alias for marginBottom
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function mb($size) {
        return $this->marginBottom($size);
    }
    
    /**
     * Alias for marginTop
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function mt($size) {
        return $this->marginTop($size);
    }
    
    /**
     * Alias for marginLeft
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function ms($size) {
        return $this->marginLeft($size);
    }
    
    /**
     * Alias for marginRight
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function me($size) {
        return $this->marginRight($size);
    }
    
    /**
     * Alias for marginX
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function mx($size) {
        return $this->marginX($size);
    }
    
    /**
     * Alias for marginY
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function my($size) {
        return $this->marginY($size);
    }
    
    /**
     * Alias for paddingBottom
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function pb($size) {
        return $this->paddingBottom($size);
    }
    
    /**
     * Alias for paddingTop
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function pt($size) {
        return $this->paddingTop($size);
    }
    
    /**
     * Alias for paddingLeft
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function ps($size) {
        return $this->paddingLeft($size);
    }
    
    /**
     * Alias for paddingRight
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function pe($size) {
        return $this->paddingRight($size);
    }
    
    /**
     * Alias for paddingX
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function px($size) {
        return $this->paddingX($size);
    }
    
    /**
     * Alias for paddingY
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function py($size) {
        return $this->paddingY($size);
    }
    
    /**
     * Alias for padding
     * 
     * @param int $size Padding size (0-5)
     * @return $this
     */
    public function p($size) {
        return $this->padding($size);
    }
    
    /**
     * Alias for margin
     * 
     * @param int $size Margin size (0-5)
     * @return $this
     */
    public function m($size) {
        return $this->margin($size);
    }
    
    // SHORTCUTS - KISAYOLLAR
    
    /**
     * Create a simple row with two equal columns
     * 
     * @param string $leftContent Left column content
     * @param string $rightContent Right column content
     * @param array|null $options Row options
     * @return string HTML string
     */
    public static function twoColumns($leftContent, $rightContent, $options = null) {
        $row = new static($options);
        $row->col6($leftContent)->col6($rightContent);
        return $row->toHtmlAsString();
    }
    
    /**
     * Create a simple row with three equal columns
     * 
     * @param string $col1Content First column content
     * @param string $col2Content Second column content
     * @param string $col3Content Third column content
     * @param array|null $options Row options
     * @return string HTML string
     */
    public static function threeColumns($col1Content, $col2Content, $col3Content, $options = null) {
        $row = new static($options);
        $row->col4($col1Content)->col4($col2Content)->col4($col3Content);
        return $row->toHtmlAsString();
    }
    
    /**
     * Create a simple row with four equal columns
     * 
     * @param string $col1Content First column content
     * @param string $col2Content Second column content
     * @param string $col3Content Third column content
     * @param string $col4Content Fourth column content
     * @param array|null $options Row options
     * @return string HTML string
     */
    public static function fourColumns($col1Content, $col2Content, $col3Content, $col4Content, $options = null) {
        $row = new static($options);
        $row->col3($col1Content)->col3($col2Content)->col3($col3Content)->col3($col4Content);
        return $row->toHtmlAsString();
    }
    
    /**
     * Create a responsive row with specified column configuration
     * 
     * @param array $columns Array of column configurations
     * @param array|null $options Row options
     * @return string HTML string
     */
    public static function responsive($columns, $options = null) {
        $row = new static($options);
        
        foreach ($columns as $column) {
            $size = $column['size'] ?? 12;
            $content = $column['content'] ?? '';
            $colOptions = $column['options'] ?? [];
            $row->col($size, $content, $colOptions);
        }
        
        return $row->toHtmlAsString();
    }
}
?>