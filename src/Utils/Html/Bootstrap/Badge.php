<?php

namespace Efaturacim\Util\Utils\Html\Bootstrap;

use Efaturacim\Util\Utils\Html\HtmlTag;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Badge component
 * 
 * Provides various badge styles and colors for Bootstrap
 */
class Badge extends HtmlComponent
{
    /**
     * Available badge variants
     */
    protected static array $variants = [
        'primary', 'secondary', 'success', 'danger', 'warning', 
        'info', 'light', 'dark', 'white', 'transparent'
    ];
    
    /**
     * Available positions
     */
    protected static array $positions = [
        'top-start', 'top-end', 'bottom-start', 'bottom-end'
    ];
    
    /**
     * Get default options for the badge
     * 
     * @return array
     */
    public function getDefaultOptions(){
        return [
            'content' => '',
            'variant' => 'primary',
            'pill' => false,
            'positioned' => false,
            'position' => 'top-start',
            'visible' => true,
            'block' => false
        ];
    }
    
    /**
     * Initialize the badge with options
     */
    public function initMe(){
        // No need to assign to properties - use $this->options directly
    }
    
    /**
     * Set badge content
     * 
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->options['content'] = $content;
        return $this;
    }
    
    /**
     * Get badge content
     * 
     * @return string
     */
    public function getContent(): string
    {
        return $this->options['content'] ?? '';
    }
    
    /**
     * Set badge variant
     * 
     * @param string $variant
     * @return self
     */
    public function setVariant(string $variant): self
    {
        if (in_array($variant, self::$variants)) {
            $this->options['variant'] = $variant;
        }
        return $this;
    }
    
    /**
     * Get badge variant
     * 
     * @return string
     */
    public function getVariant(): string
    {
        return $this->options['variant'] ?? 'primary';
    }
    
    /**
     * Set pill shape
     * 
     * @param bool $pill
     * @return self
     */
    public function setPill(bool $pill): self
    {
        $this->options['pill'] = $pill;
        return $this;
    }
    
    /**
     * Get pill shape
     * 
     * @return bool
     */
    public function isPill(): bool
    {
        return $this->options['pill'] ?? false;
    }
    
    /**
     * Set positioned badge
     * 
     * @param bool $positioned
     * @param string $position
     * @return self
     */
    public function setPositioned(bool $positioned, string $position = 'top-start'): self
    {
        $this->options['positioned'] = $positioned;
        if (in_array($position, self::$positions)) {
            $this->options['position'] = $position;
        }
        return $this;
    }
    
    /**
     * Get positioned status
     * 
     * @return bool
     */
    public function isPositioned(): bool
    {
        return $this->options['positioned'] ?? false;
    }
    
    /**
     * Get position
     * 
     * @return string
     */
    public function getPosition(): string
    {
        return $this->options['position'] ?? 'top-start';
    }
    
    /**
     * Set visibility
     * 
     * @param bool $visible
     * @return self
     */
    public function setVisible(bool $visible): self
    {
        $this->options['visible'] = $visible;
        return $this;
    }
    
    /**
     * Get visibility
     * 
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->options['visible'] ?? true;
    }
    
    /**
     * Set block rendering
     * 
     * @param bool $block
     * @return self
     */
    public function setBlock(bool $block): self
    {
        $this->options['block'] = $block;
        return $this;
    }
    
    /**
     * Get block rendering status
     * 
     * @return bool
     */
    public function isBlock(): bool
    {
        return $this->options['block'] ?? false;
    }
    
    /**
     * Build CSS classes for the badge
     * 
     * @return array
     */
    protected function buildClasses(): array
    {
        $classes = ['badge'];
        
        // Add variant class
        $variant = $this->getVariant();
        $classes[] = 'bg-' . $variant;
        
        // Add pill class
        if ($this->isPill()) {
            $classes[] = 'rounded-pill';
        }
        
        // Add positioned classes
        if ($this->isPositioned()) {
            $classes[] = 'position-absolute';
            $classes[] = $this->getPosition();
        }
        
        return $classes;
    }
    
    
    
    /**
     * @return string
     */
    public function toHtmlAsString($doc = null): string
    {
        if (!$this->isVisible()) {
            return '';
        }
        $badge = HtmlTag::span()->setClass($this->buildClasses())->addContent($this->getContent());                    
        return $this->isBlock() ? HtmlTag::div(array(),$badge->toHtml()) : $badge->toHtml($doc);
    }
    
    /**
     * Create a new Badge instance
     * 
     * @param string $content
     * @param string $variant
     * @param bool $pill
     * @return self
     */
    public static function create(string $content = '', string $variant = 'primary', bool $pill = false,$defOptions=null): self
    {
        return new self([
            'content' => $content,
            'variant' => $variant,
            'pill' => $pill
        ],$defOptions);
    }
    
    /**
     * Create badge variants
     */
    
    /**
     * Create a primary badge
     * 
     * @param string $content
     * @param bool $pill
     * @return self
     */
    public static function primary(string $content, bool $pill = false,$defOptions=null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'primary',
            'pill' => $pill
        ],$defOptions);
    }
    
    /**
     * Create a secondary badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function secondary(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'secondary',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a success badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function success(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'success',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a danger badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function danger(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'danger',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a warning badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function warning(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'warning',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create an info badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function info(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'info',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a light badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function light(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'light',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a dark badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function dark(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'dark',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a white badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function white(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'white',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create a transparent badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function transparent(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'transparent',
            'pill' => $pill
        ], $defOptions);
    }
    
    /**
     * Create pill badges
     */
    
    /**
     * Create a primary pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function primaryPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'primary',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a secondary pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function secondaryPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'secondary',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a success pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function successPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'success',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a danger pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function dangerPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'danger',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a warning pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function warningPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'warning',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create an info pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function infoPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'info',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a light pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function lightPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'light',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a dark pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function darkPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'dark',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a white pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function whitePill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'white',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create a transparent pill badge
     * 
     * @param string $content
     * @param array|null $defOptions
     * @return self
     */
    public static function transparentPill(string $content, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'transparent',
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create positioned badges
     */
    
    /**
     * Create a positioned badge
     * 
     * @param string $content
     * @param string $variant
     * @param string $position
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function positioned(string $content, string $variant = 'primary', string $position = 'top-start', bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => $variant,
            'position' => $position,
            'pill' => $pill,
            'positioned' => true
        ], $defOptions);
    }
    
    /**
     * Create notification badge
     * 
     * @param string $content
     * @param string $variant
     * @param array|null $defOptions
     * @return self
     */
    public static function notification(string $content, string $variant = 'danger', $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => $variant,
            'pill' => true,
            'positioned' => true,
            'position' => 'top-end'
        ], $defOptions);
    }
    
    /**
     * Create counter badge
     * 
     * @param int $count
     * @param string $variant
     * @param array|null $defOptions
     * @return self
     */
    public static function counter(int $count, string $variant = 'primary', $defOptions = null): self
    {
        return new self([
            'content' => (string)$count,
            'variant' => $variant,
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create status badge
     * 
     * @param string $status
     * @param array|null $defOptions
     * @return self
     */
    public static function status(string $status, $defOptions = null): self
    {
        $variantMap = [
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            'error' => 'danger',
            'info' => 'info'
        ];
        
        $variant = $variantMap[strtolower($status)] ?? 'primary';
        return new self([
            'content' => $status,
            'variant' => $variant,
            'pill' => true
        ], $defOptions);
    }
    
    /**
     * Create block badges
     */
    
    /**
     * Create a block badge
     * 
     * @param string $content
     * @param string $variant
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function block(string $content, string $variant = 'primary', bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => $variant,
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create block badge variants
     */
    
    /**
     * Create a primary block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function primaryBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'primary',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a secondary block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function secondaryBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'secondary',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a success block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function successBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'success',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a danger block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function dangerBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'danger',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a warning block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function warningBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'warning',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create an info block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function infoBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'info',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a light block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function lightBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'light',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
    
    /**
     * Create a dark block badge
     * 
     * @param string $content
     * @param bool $pill
     * @param array|null $defOptions
     * @return self
     */
    public static function darkBlock(string $content, bool $pill = false, $defOptions = null): self
    {
        return new self([
            'content' => $content,
            'variant' => 'dark',
            'pill' => $pill,
            'block' => true
        ], $defOptions);
    }
}
?>
