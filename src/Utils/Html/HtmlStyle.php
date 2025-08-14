<?php

namespace Efaturacim\Util\Utils\Html;

/**
 * HTML Style utility class for managing CSS styles
 */
class HtmlStyle
{
    /**
     * @var array CSS properties and their values
     */
    protected array $properties = [];
    
    /**
     * @var array Valid CSS properties
     */
    protected static array $validProperties = [
        // Layout properties
        'display', 'position', 'top', 'right', 'bottom', 'left', 'z-index',
        'float', 'clear', 'overflow', 'overflow-x', 'overflow-y',
        
        // Box model properties
        'width', 'height', 'min-width', 'min-height', 'max-width', 'max-height',
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border', 'border-width', 'border-style', 'border-color',
        'border-top', 'border-right', 'border-bottom', 'border-left',
        'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width',
        'border-top-style', 'border-right-style', 'border-bottom-style', 'border-left-style',
        'border-top-color', 'border-right-color', 'border-bottom-color', 'border-left-color',
        'border-radius', 'border-top-left-radius', 'border-top-right-radius', 
        'border-bottom-right-radius', 'border-bottom-left-radius',
        'box-sizing', 'box-shadow',
        
        // Typography properties
        'font-family', 'font-size', 'font-weight', 'font-style', 'font-variant',
        'line-height', 'text-align', 'text-decoration', 'text-transform',
        'text-indent', 'text-shadow', 'letter-spacing', 'word-spacing',
        'white-space', 'word-wrap', 'word-break', 'text-overflow',
        
        // Color and background properties
        'color', 'background', 'background-color', 'background-image',
        'background-repeat', 'background-attachment', 'background-position',
        'background-size', 'background-clip', 'background-origin',
        
        // Visual properties
        'opacity', 'visibility', 'cursor', 'outline', 'outline-width',
        'outline-style', 'outline-color', 'outline-offset',
        
        // Transform properties
        'transform', 'transform-origin', 'transition', 'transition-property',
        'transition-duration', 'transition-timing-function', 'transition-delay',
        'animation', 'animation-name', 'animation-duration', 'animation-timing-function',
        'animation-delay', 'animation-iteration-count', 'animation-direction',
        'animation-fill-mode', 'animation-play-state',
        
        // Flexbox properties
        'flex', 'flex-direction', 'flex-wrap', 'flex-flow', 'flex-grow',
        'flex-shrink', 'flex-basis', 'justify-content', 'align-items',
        'align-self', 'align-content',
        
        // Grid properties
        'grid', 'grid-template', 'grid-template-areas', 'grid-template-rows',
        'grid-template-columns', 'grid-area', 'grid-row', 'grid-column',
        'grid-row-start', 'grid-row-end', 'grid-column-start', 'grid-column-end',
        'grid-gap', 'grid-row-gap', 'grid-column-gap', 'justify-items',
        'justify-self', 'align-content', 'place-items', 'place-content',
        
        // Print properties
        'page-break-before', 'page-break-after', 'page-break-inside',
        'orphans', 'widows',
        
        // Other properties
        'content', 'quotes', 'counter-reset', 'counter-increment',
        'resize', 'user-select', 'pointer-events', 'clip', 'clip-path'
    ];
    
    /**
     * Constructor
     * 
     * @param array|string $styles Initial styles
     */
    public function __construct($styles = [])
    {
        if (is_string($styles)) {
            $this->parseStyleString($styles);
        } elseif (is_array($styles)) {
            $this->setProperties($styles);
        }
    }
    
    /**
     * Set multiple properties at once
     * 
     * @param array|HtmlStyle $properties
     * @return self
     */
    public function setProperties($properties): self
    {
        if(!is_null($properties) && $properties instanceof HtmlStyle){
            $this->setProperties($properties->getProperties());
        }else if (!is_null($properties) &&is_array($properties)){
            foreach ($properties as $property => $value) {
                $this->setProperty($property, $value);
            }    
        }
        return $this;
    }
    
    /**
     * Set a single CSS property
     * 
     * @param string $property
     * @param mixed $value
     * @return self
     */
    public function setProperty(string $property, $value): self
    {
        $property = $this->normalizeProperty($property);
        
        if ($value === null || $value === '') {
            unset($this->properties[$property]);
        } else {
            $this->properties[$property] = $this->normalizeValue($value);
        }
        
        return $this;
    }
    
    /**
     * Get a CSS property value
     * 
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function getProperty(string $property, $default = null)
    {
        $property = $this->normalizeProperty($property);
        return $this->properties[$property] ?? $default;
    }
    
    /**
     * Remove a CSS property
     * 
     * @param string $property
     * @return self
     */
    public function removeProperty(string $property): self
    {
        $property = $this->normalizeProperty($property);
        unset($this->properties[$property]);
        return $this;
    }
    
    /**
     * Check if a property exists
     * 
     * @param string $property
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        $property = $this->normalizeProperty($property);
        return isset($this->properties[$property]);
    }
    
    /**
     * Get all properties
     * 
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
    
    /**
     * Clear all properties
     * 
     * @return self
     */
    public function clear(): self
    {
        $this->properties = [];
        return $this;
    }
    
    /**
     * Parse a CSS style string
     * 
     * @param string $styleString
     * @return self
     */
    public function parseStyleString(string $styleString): self
    {
        $this->clear();
        
        if (empty($styleString)) {
            return $this;
        }
        
        // Split by semicolon and process each property
        $properties = explode(';', $styleString);
        
        foreach ($properties as $property) {
            $property = trim($property);
            if (empty($property)) {
                continue;
            }
            
            $parts = explode(':', $property, 2);
            if (count($parts) === 2) {
                $prop = trim($parts[0]);
                $value = trim($parts[1]);
                $this->setProperty($prop, $value);
            }
        }
        
        return $this;
    }
    
    /**
     * Convert to CSS string
     * 
     * @return string
     */
    public function toString(): string
    {
        if (empty($this->properties)) {
            return '';
        }
        
        $styles = [];
        foreach ($this->properties as $property => $value) {
            $styles[] = $property . ':' . $value;
        }
        
        return implode(';', $styles);
    }
    
    /**
     * Convert to string
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
    
    /**
     * Normalize property name
     * 
     * @param string $property
     * @return string
     */
    protected function normalizeProperty(string $property): string
    {
        return strtolower(trim($property));
    }
    
    /**
     * Normalize property value
     * 
     * @param mixed $value
     * @return string
     */
    protected function normalizeValue($value): string
    {
        if (is_numeric($value)) {
            // Add 'px' to numeric values for common properties
            $numericProperties = ['width', 'height', 'margin', 'padding', 'border-width', 'font-size'];
            if (in_array($this->getLastProperty(), $numericProperties)) {
                return $value . 'px';
            }
            return (string)$value;
        }
        
        return (string)$value;
    }
    
    /**
     * Get the last property that was set (for context)
     * 
     * @return string|null
     */
    protected function getLastProperty(): ?string
    {
        $keys = array_keys($this->properties);
        return end($keys) ?: null;
    }
    
    /**
     * Create a new HtmlStyle instance
     * 
     * @param array|string $styles
     * @return self
     */
    public static function create($styles = []): self
    {
        return new self($styles);
    }
    
    /**
     * Common style presets
     */
    
    /**
     * Create a flexbox container style
     * 
     * @param string $direction
     * @param string $justify
     * @param string $align
     * @return self
     */
    public static function flex(string $direction = 'row', string $justify = 'flex-start', string $align = 'stretch'): self
    {
        return new self([
            'display' => 'flex',
            'flex-direction' => $direction,
            'justify-content' => $justify,
            'align-items' => $align
        ]);
    }
    
    /**
     * Create a grid container style
     * 
     * @param string $template
     * @param string $gap
     * @return self
     */
    public static function grid(string $template = '1fr', string $gap = '0'): self
    {
        return new self([
            'display' => 'grid',
            'grid-template-columns' => $template,
            'gap' => $gap
        ]);
    }
    
    /**
     * Create a centered style
     * 
     * @return self
     */
    public static function centered(): self
    {
        return new self([
            'display' => 'flex',
            'justify-content' => 'center',
            'align-items' => 'center'
        ]);
    }
    
    /**
     * Create a hidden style
     * 
     * @return self
     */
    public static function hidden(): self
    {
        return new self([
            'display' => 'none'
        ]);
    }
    
    /**
     * Create a visible style
     * 
     * @return self
     */
    public static function visible(): self
    {
        return new self([
            'display' => 'block'
        ]);
    }
    
    /**
     * Create a border style
     * 
     * @param string $width
     * @param string $style
     * @param string $color
     * @return self
     */
    public static function border(string $width = '1px', string $style = 'solid', string $color = '#000'): self
    {
        return new self([
            'border' => $width . ' ' . $style . ' ' . $color
        ]);
    }
    
    /**
     * Create a rounded corners style
     * 
     * @param string $radius
     * @return self
     */
    public static function rounded(string $radius = '4px'): self
    {
        return new self([
            'border-radius' => $radius
        ]);
    }
    
    /**
     * Create a shadow style
     * 
     * @param string $shadow
     * @return self
     */
    public static function shadow(string $shadow = '0 2px 4px rgba(0,0,0,0.1)'): self
    {
        return new self([
            'box-shadow' => $shadow
        ]);
    }
    
    /**
     * Create a text style
     * 
     * @param string $size
     * @param string $weight
     * @param string $color
     * @return self
     */
    public static function text(string $size = '16px', string $weight = 'normal', string $color = '#000'): self
    {
        return new self([
            'font-size' => $size,
            'font-weight' => $weight,
            'color' => $color
        ]);
    }
    
    /**
     * Create a margin style
     * 
     * @param string $margin
     * @return self
     */
    public static function margin(string $margin = '0'): self
    {
        return new self([
            'margin' => $margin
        ]);
    }
    
    /**
     * Create a padding style
     * 
     * @param string $padding
     * @return self
     */
    public static function padding(string $padding = '0'): self
    {
        return new self([
            'padding' => $padding
        ]);
    }
    
    /**
     * Create a background style
     * 
     * @param string $color
     * @return self
     */
    public static function background(string $color = '#fff'): self
    {
        return new self([
            'background-color' => $color
        ]);
    }
    
    /**
     * Create a position style
     * 
     * @param string $position
     * @return self
     */
    public static function position(string $position = 'static'): self
    {
        return new self([
            'position' => $position
        ]);
    }
    
    /**
     * Create a width and height style
     * 
     * @param string $width
     * @param string $height
     * @return self
     */
    public static function size(string $width = 'auto', string $height = 'auto'): self
    {
        return new self([
            'width' => $width,
            'height' => $height
        ]);
    }
    
    /**
     * Create a transition style
     * 
     * @param string $property
     * @param string $duration
     * @param string $timing
     * @return self
     */
    public static function transition(string $property = 'all', string $duration = '0.3s', string $timing = 'ease'): self
    {
        return new self([
            'transition' => $property . ' ' . $duration . ' ' . $timing
        ]);
    }
}
?>
