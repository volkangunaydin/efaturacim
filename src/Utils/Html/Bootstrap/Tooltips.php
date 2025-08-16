<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Tooltips Component
 * 
 * Creates Bootstrap tooltips with various configurations and content.
 * Supports different placements, triggers, animations, and Bootstrap 5 classes.
 */
class Tooltips extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure tooltip ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'tooltip_' . uniqid();
        }
        
        // Ensure trigger element ID is set
        if (!isset($this->options['triggerId']) || empty($this->options['triggerId'])) {
            $this->options['triggerId'] = 'trigger_' . uniqid();
        }
        
        // Ensure content is always set
        if (!isset($this->options['content'])) {
            $this->options['content'] = '';
        }
    }

    /**
     * Get default options for the tooltip
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'triggerId' => '',
            'triggerText' => 'Hover to see tooltip',
            'triggerType' => 'button', // button, link, span, div, etc.
            'content' => 'Tooltip content',
            'placement' => 'top', // top, bottom, left, right
            'trigger' => 'hover', // hover, focus, click, manual
            'animation' => true,
            'delay' => 0, // delay in milliseconds
            'html' => false, // allow HTML in content
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
     * Render the tooltip as HTML string
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

        // Build tooltip element
        $tooltipElement = $this->buildTooltip($id, $content, $placement, $html, $class, $style);

        return $triggerElement . $tooltipElement;
    }

    /**
     * Build trigger element
     * 
     * @param string $triggerId
     * @param string $triggerText
     * @param string $triggerType
     * @param string $triggerClass
     * @param string $triggerStyle
     * @param string $tooltipId
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
    private function buildTrigger($triggerId, $triggerText, $triggerType, $triggerClass, $triggerStyle, $tooltipId, $placement, $trigger, $animation, $delay, $html, $sanitize, $offset, $fallbackPlacements, $boundary, $customClass, $popperConfig, $data)
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
        $attributes .= ' data-bs-toggle="tooltip"';
        $attributes .= ' data-bs-target="#' . htmlspecialchars($tooltipId, ENT_QUOTES, 'UTF-8') . '"';
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
     * Build tooltip element
     * 
     * @param string $id
     * @param string $content
     * @param string $placement
     * @param bool $html
     * @param string $class
     * @param string $style
     * @return string
     */
    private function buildTooltip($id, $content, $placement, $html, $class, $style)
    {
        // Build tooltip classes
        $classes = 'tooltip';
        if (!empty($class)) {
            $classes .= ' ' . $class;
        }

        // Build tooltip attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $classes . '"';
        $attributes .= ' role="tooltip"';
        $attributes .= ' data-bs-placement="' . htmlspecialchars($placement, ENT_QUOTES, 'UTF-8') . '"';

        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }

        $htmlContent = '<div ' . $attributes . '>';
        $htmlContent .= '<div class="tooltip-arrow"></div>';
        $htmlContent .= '<div class="tooltip-inner">';
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
     * Create a simple tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function top($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottom($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function left($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function right($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a focus tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function focus($content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'focus'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a click tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function click($content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'click'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a manual tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function manual($content, $triggerText = 'Manual trigger', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'manual'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a link tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function link($content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a span tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function span($content, $triggerText = 'Hover span', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'span'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a div tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function div($content, $triggerText = 'Hover div', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'triggerType' => 'div'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with HTML content
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function html($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip without animation
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noAnimation($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with delay
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDelay($content, $triggerText = 'Hover me', $delay = 500, $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with custom class
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param string $customClass Custom CSS class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomClass($content, $triggerText = 'Hover me', $customClass = '', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'customClass' => $customClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top focus tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topFocus($content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'trigger' => 'focus'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom click tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomClick($content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'trigger' => 'click'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left link tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftLink($content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right span tooltip
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightSpan($content, $triggerText = 'Hover span', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'triggerType' => 'span'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topHtml($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomHtml($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftHtml($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightHtml($content, $triggerText = 'Hover me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a focus HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function focusHtml($content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'focus',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a click HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function clickHtml($content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'click',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a manual HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function manualHtml($content, $triggerText = 'Manual trigger', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'trigger' => 'manual',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top focus HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topFocusHtml($content, $triggerText = 'Focus me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'top',
            'trigger' => 'focus',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom click HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomClickHtml($content, $triggerText = 'Click me', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'bottom',
            'trigger' => 'click',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a left link HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftLinkHtml($content, $triggerText = 'Click link', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'left',
            'triggerType' => 'link',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right span HTML tooltip
     * 
     * @param string $content Tooltip content (HTML)
     * @param string $triggerText Trigger text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightSpanHtml($content, $triggerText = 'Hover span', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'placement' => 'right',
            'triggerType' => 'span',
            'html' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with custom offset
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array $offset Custom offset [x, y]
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withOffset($content, $triggerText = 'Hover me', $offset = [0, 10], $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'offset' => $offset
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with custom fallback placements
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array $fallbackPlacements Custom fallback placements
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withFallbackPlacements($content, $triggerText = 'Hover me', $fallbackPlacements = ['top', 'right', 'bottom', 'left'], $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'fallbackPlacements' => $fallbackPlacements
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with custom boundary
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param string $boundary Custom boundary
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withBoundary($content, $triggerText = 'Hover me', $boundary = 'viewport', $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'boundary' => $boundary
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a tooltip with custom data attributes
     * 
     * @param string $content Tooltip content
     * @param string $triggerText Trigger text
     * @param array $data Custom data attributes
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomData($content, $triggerText = 'Hover me', $data = [], $options = null)
    {
        $defaultOptions = [
            'content' => $content,
            'triggerText' => $triggerText,
            'data' => $data
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
