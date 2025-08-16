<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Popovers Component
 * 
 * Creates Bootstrap popovers with various content, placement, and trigger options.
 * Supports different placements, triggers, content types, and Bootstrap 5 classes.
 */
class Popovers extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure popover ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'popover_' . uniqid();
        }
        
        // Ensure trigger element ID is set
        if (!isset($this->options['triggerId']) || empty($this->options['triggerId'])) {
            $this->options['triggerId'] = 'trigger_' . uniqid();
        }
        
        // Ensure title is always set
        if (!isset($this->options['title'])) {
            $this->options['title'] = '';
        }
        
        // Ensure content is always set
        if (!isset($this->options['content'])) {
            $this->options['content'] = '';
        }
    }

    /**
     * Get default options for the popover
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'triggerId' => '',
            'triggerText' => 'Click to toggle popover',
            'triggerType' => 'button', // button, link, span, div, etc.
            'title' => 'Popover title',
            'content' => 'Popover content',
            'placement' => 'top', // top, bottom, left, right
            'trigger' => 'click', // click, hover, focus, manual
            'animation' => true,
            'delay' => 0, // delay in milliseconds
            'html' => false, // allow HTML in title and content
            'sanitize' => true, // sanitize HTML
            'offset' => [0, 8], // [x, y] offset
            'fallbackPlacements' => ['top', 'right', 'bottom', 'left'],
            'boundary' => 'clippingParents', // clippingParents, viewport, element
            'customClass' => '',
            'popperConfig' => null,
            'class' => '',
            'style' => '',
            'triggerClass' => '',
            'triggerStyle' => '',
            'data' => []
        ];
    }

    /**
     * Render the popover as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $triggerId = $this->options['triggerId'];
        $triggerText = $this->options['triggerText'];
        $triggerType = $this->options['triggerType'];
        $title = $this->options['title'];
        $content = $this->options['content'];
        $placement = $this->options['placement'];
        $trigger = $this->options['trigger'];
        $animation = $this->options['animation'];
        $delay = $this->options['delay'];
        $html = $this->options['html'];
        $sanitize = $this->options['sanitize'];
        $offset = $this->options['offset'];
        $fallbackPlacements = $this->options['fallbackPlacements'];
        $boundary = $this->options['boundary'];
        $customClass = $this->options['customClass'];
        $popperConfig = $this->options['popperConfig'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $triggerClass = $this->options['triggerClass'];
        $triggerStyle = $this->options['triggerStyle'];
        $data = $this->options['data'];

        // Build trigger element
        $triggerElement = $this->buildTrigger($triggerId, $triggerText, $triggerType, $triggerClass, $triggerStyle, $id, $placement, $trigger, $animation, $delay, $html, $sanitize, $offset, $fallbackPlacements, $boundary, $customClass, $popperConfig, $data);

        // Build popover element
        $popoverElement = $this->buildPopover($id, $title, $content, $placement, $html, $class, $style);

        return $triggerElement . $popoverElement;
    }

    /**
     * Build trigger element
     * 
     * @param string $triggerId
     * @param string $triggerText
     * @param string $triggerType
     * @param string $triggerClass
     * @param string $triggerStyle
     * @param string $popoverId
     * @param string $placement
     * @param string $trigger
     * @param bool $animation
     * @param int $delay
     * @param bool $html
     * @param bool $sanitize
     * @param array $offset
     * @param array $fallbackPlacements
     * @param string $boundary
     * @param string $customClass
     * @param mixed $popperConfig
     * @param array $data
     * @return string
     */
    private function buildTrigger($triggerId, $triggerText, $triggerType, $triggerClass, $triggerStyle, $popoverId, $placement, $trigger, $animation, $delay, $html, $sanitize, $offset, $fallbackPlacements, $boundary, $customClass, $popperConfig, $data)
    {
        // Build trigger classes
        $classes = 'btn btn-secondary';
        if (!empty($triggerClass)) {
            $classes .= ' ' . $triggerClass;
        }

        // Build trigger attributes
        $attributes = 'id="' . htmlspecialchars($triggerId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $classes . '"';
        $attributes .= ' type="button"';
        $attributes .= ' data-bs-toggle="popover"';
        $attributes .= ' data-bs-target="#' . htmlspecialchars($popoverId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' data-bs-placement="' . htmlspecialchars($placement, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' data-bs-trigger="' . htmlspecialchars($trigger, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!$animation) {
            $attributes .= ' data-bs-animation="false"';
        }
        
        if ($delay > 0) {
            $attributes .= ' data-bs-delay="' . $delay . '"';
        }
        
        if ($html) {
            $attributes .= ' data-bs-html="true"';
        }
        
        if (!$sanitize) {
            $attributes .= ' data-bs-sanitize="false"';
        }
        
        if (!empty($offset)) {
            $attributes .= ' data-bs-offset="' . implode(',', $offset) . '"';
        }
        
        if (!empty($fallbackPlacements)) {
            $attributes .= ' data-bs-fallback-placements="' . implode(',', $fallbackPlacements) . '"';
        }
        
        if (!empty($boundary)) {
            $attributes .= ' data-bs-boundary="' . htmlspecialchars($boundary, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($customClass)) {
            $attributes .= ' data-bs-custom-class="' . htmlspecialchars($customClass, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($popperConfig)) {
            $attributes .= ' data-bs-popper-config="' . htmlspecialchars(json_encode($popperConfig), ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($triggerStyle)) {
            $attributes .= ' style="' . htmlspecialchars($triggerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build trigger element based on type
        switch ($triggerType) {
            case 'link':
                return '<a href="#" ' . $attributes . '>' . htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8') . '</a>';
            
            case 'span':
                return '<span ' . $attributes . ' style="cursor: pointer;">' . htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8') . '</span>';
            
            case 'div':
                return '<div ' . $attributes . ' style="cursor: pointer; display: inline-block;">' . htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8') . '</div>';
            
            case 'button':
            default:
                return '<button ' . $attributes . '>' . htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8') . '</button>';
        }
    }

    /**
     * Build popover element
     * 
     * @param string $id
     * @param string $title
     * @param string $content
     * @param string $placement
     * @param bool $html
     * @param string $class
     * @param string $style
     * @return string
     */
    private function buildPopover($id, $title, $content, $placement, $html, $class, $style)
    {
        // Build popover classes
        $classes = 'popover';
        if (!empty($class)) {
            $classes .= ' ' . $class;
        }

        // Build popover attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $classes . '"';
        $attributes .= ' role="tooltip"';
        $attributes .= ' data-bs-placement="' . htmlspecialchars($placement, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }

        $htmlContent = '<div ' . $attributes . '>';
        $htmlContent .= '<div class="popover-arrow"></div>';
        
        if (!empty($title)) {
            $htmlContent .= '<h3 class="popover-header">';
            if ($html) {
                $htmlContent .= $title;
            } else {
                $htmlContent .= htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            }
            $htmlContent .= '</h3>';
        }
        
        $htmlContent .= '<div class="popover-body">';
        if ($html) {
            $htmlContent .= $content;
        } else {
            $htmlContent .= htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        }
        $htmlContent .= '</div>';
        
        $htmlContent .= '</div>';

        return $htmlContent;
    }

    /**
     * Get JavaScript code lines for the component
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLines()
    {
        return null;
    }

    /**
     * Get JavaScript code lines for component initialization
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLinesForInit()
    {
        return null;
    }

    /**
     * Get JavaScript files required by the component
     * 
     * @return array|null Array of JavaScript file paths or null
     */
    public function getJsFiles()
    {
        return null;
    }

    /**
     * Get CSS files required by the component
     * 
     * @return array|null Array of CSS file paths or null
     */
    public function getCssFiles()
    {
        return null;
    }

    // SHORTCUTS - KISAYOLLAR

    /**
     * Create a simple popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function top($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottom($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function left($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function right($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a hover popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function hover($title, $content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'hover'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a focus popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function focus($title, $content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'focus'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a manual popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function manual($title, $content, $triggerText = 'Manual trigger', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'manual'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a link popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function link($title, $content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a span popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function span($title, $content, $triggerText = 'Click span', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'span'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a div popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function div($title, $content, $triggerText = 'Click div', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'div'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a popover with HTML content
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function html($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a popover without animation
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noAnimation($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a popover with delay
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDelay($title, $content, $triggerText = 'Click me', $delay = 500, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a popover with custom class
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param string $customClass Custom CSS class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomClass($title, $content, $triggerText = 'Click me', $customClass = '', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'customClass' => $customClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top hover popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topHover($title, $content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'trigger' => 'hover'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom focus popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomFocus($title, $content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'trigger' => 'focus'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left link popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftLink($title, $content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right span popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightSpan($title, $content, $triggerText = 'Click span', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'triggerType' => 'span'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topHtml($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomHtml($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftHtml($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightHtml($title, $content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a hover HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function hoverHtml($title, $content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'hover',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a focus HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function focusHtml($title, $content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'focus',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a manual HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function manualHtml($title, $content, $triggerText = 'Manual trigger', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'manual',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top hover HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topHoverHtml($title, $content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'trigger' => 'hover',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom focus HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomFocusHtml($title, $content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'trigger' => 'focus',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left link HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftLinkHtml($title, $content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'triggerType' => 'link',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right span HTML popover
     * 
     * @param string $title Popover title
     * @param string $content Popover content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightSpanHtml($title, $content, $triggerText = 'Click span', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'triggerType' => 'span',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>