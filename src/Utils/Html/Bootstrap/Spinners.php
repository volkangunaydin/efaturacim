<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Spinners Component
 * 
 * Creates Bootstrap spinners with various types, sizes, and colors.
 * Supports different spinner types, sizes, colors, and Bootstrap 5 classes.
 */
class Spinners extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure spinner ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'spinner_' . uniqid();
        }
        
        // Ensure text is always set
        if (!isset($this->options['text'])) {
            $this->options['text'] = '';
        }
        
        // Ensure type is always set
        if (!isset($this->options['type'])) {
            $this->options['type'] = 'border';
        }
    }

    /**
     * Get default options for the spinner
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'type' => 'border', // border, grow
            'size' => '', // sm, lg, or empty for default
            'color' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
            'text' => '', // Text to display with spinner
            'textClass' => '', // Additional classes for text
            'textStyle' => '', // Inline styles for text
            'role' => 'status', // ARIA role
            'ariaLabel' => 'Loading...', // ARIA label
            'class' => '',
            'style' => '',
            'data' => []
        ];
    }

    /**
     * Render the spinner as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $type = $this->options['type'];
        $size = $this->options['size'];
        $color = $this->options['color'];
        $text = $this->options['text'];
        $textClass = $this->options['textClass'];
        $textStyle = $this->options['textStyle'];
        $role = $this->options['role'];
        $ariaLabel = $this->options['ariaLabel'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $data = $this->options['data'];

        // Build spinner classes
        $spinnerClasses = 'spinner-' . $type;
        if (!empty($size)) {
            $spinnerClasses .= ' spinner-' . $type . '-' . $size;
        }
        if (!empty($color)) {
            $spinnerClasses .= ' text-' . $color;
        }
        if (!empty($class)) {
            $spinnerClasses .= ' ' . $class;
        }

        // Build spinner attributes
        $spinnerAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $spinnerAttributes .= ' class="' . $spinnerClasses . '"';
        $spinnerAttributes .= ' role="' . htmlspecialchars($role, ENT_QUOTES, 'UTF-8') . '"';
        $spinnerAttributes .= ' aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($style)) {
            $spinnerAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $spinnerAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build spinner HTML
        $spinnerHtml = $this->buildSpinner($type, $spinnerAttributes);

        // Add text if provided
        if (!empty($text)) {
            $textAttributes = '';
            if (!empty($textClass)) {
                $textAttributes .= ' class="' . $textClass . '"';
            }
            if (!empty($textStyle)) {
                $textAttributes .= ' style="' . htmlspecialchars($textStyle, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            $spinnerHtml .= '<span' . $textAttributes . '>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>';
        }

        return $spinnerHtml;
    }

    /**
     * Build spinner element
     * 
     * @param string $type
     * @param string $attributes
     * @return string
     */
    private function buildSpinner($type, $attributes)
    {
        switch ($type) {
            case 'grow':
                return '<div ' . $attributes . '></div>';
            
            case 'border':
            default:
                return '<div ' . $attributes . '></div>';
        }
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
     * Create a simple border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($options = null)
    {
        return (new self($options))->toHtmlAsString();
    }

    /**
     * Create a border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function border($options = null)
    {
        $defaultOptions = [
            'type' => 'border'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function grow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark colored spinner
     * 
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a spinner with text
     * 
     * @param string $text Text to display
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withText($text, $type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function lightBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function lightGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function darkBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function darkGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small primary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallPrimaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large primary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largePrimaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small primary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallPrimaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large primary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largePrimaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small secondary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallSecondaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large secondary border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeSecondaryBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small secondary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallSecondaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large secondary grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeSecondaryGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small success border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallSuccessBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large success border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeSuccessBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small success grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallSuccessGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large success grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeSuccessGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small danger border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallDangerBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large danger border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeDangerBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small danger grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallDangerGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large danger grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeDangerGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small warning border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallWarningBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large warning border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeWarningBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small warning grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallWarningGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large warning grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeWarningGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small info border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallInfoBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large info border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeInfoBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small info grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallInfoGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large info grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeInfoGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small light border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallLightBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large light border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeLightBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small light grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallLightGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large light grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeLightGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small dark border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallDarkBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'sm',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large dark border spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeDarkBorder($options = null)
    {
        $defaultOptions = [
            'type' => 'border',
            'size' => 'lg',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small dark grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallDarkGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'sm',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large dark grow spinner
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeDarkGrow($options = null)
    {
        $defaultOptions = [
            'type' => 'grow',
            'size' => 'lg',
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a spinner with custom text
     * 
     * @param string $text Text to display
     * @param string $type Spinner type (border, grow)
     * @param string $color Spinner color
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomText($text, $type = 'border', $color = 'primary', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'color' => $color,
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a spinner with custom role
     * 
     * @param string $role Custom ARIA role
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomRole($role, $type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'role' => $role
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a spinner with custom aria label
     * 
     * @param string $ariaLabel Custom ARIA label
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomAriaLabel($ariaLabel, $type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'ariaLabel' => $ariaLabel
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a spinner with custom data attributes
     * 
     * @param array $data Custom data attributes
     * @param string $type Spinner type (border, grow)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomData($data = [], $type = 'border', $options = null)
    {
        $defaultOptions = [
            'type' => $type,
            'data' => $data
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
