<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Button Component
 * 
 * Creates a Bootstrap button with various styles, sizes, and states.
 * Supports custom styling, icons, and Bootstrap 5 classes.
 */
class Button extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure button ID is set if not provided
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'btn_' . uniqid();
        }
    }

    /**
     * Get default options for the button
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'text' => 'Button',
            'type' => 'button',
            'variant' => 'primary',
            'size' => '',
            'outline' => false,
            'disabled' => false,
            'href' => '',
            'target' => '',
            'class' => '',
            'style' => '',
            'icon' => '',
            'iconPosition' => 'left',
            'loading' => false,
            'loadingText' => 'Loading...',
            'block' => false,
            'rounded' => false,
            'pill' => false,
            'data' => [],
            'onclick' => '',
            'form' => '',
            'formaction' => '',
            'formmethod' => '',
            'formtarget' => '',
            'value' => '',
            'name' => '',
            'autofocus' => false,
            'tabindex' => ''
        ];
    }

    /**
     * Render the button as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $text = $this->options['text'];
        $type = $this->options['type'];
        $variant = $this->options['variant'];
        $size = $this->options['size'];
        $outline = $this->options['outline'];
        $disabled = $this->options['disabled'];
        $href = $this->options['href'];
        $target = $this->options['target'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $icon = $this->options['icon'];
        $iconPosition = $this->options['iconPosition'];
        $loading = $this->options['loading'];
        $loadingText = $this->options['loadingText'];
        $block = $this->options['block'];
        $rounded = $this->options['rounded'];
        $pill = $this->options['pill'];
        $data = $this->options['data'];
        $onclick = $this->options['onclick'];
        $form = $this->options['form'];
        $formaction = $this->options['formaction'];
        $formmethod = $this->options['formmethod'];
        $formtarget = $this->options['formtarget'];
        $value = $this->options['value'];
        $name = $this->options['name'];
        $autofocus = $this->options['autofocus'];
        $tabindex = $this->options['tabindex'];

        // Build button classes
        $buttonClass = 'btn';
        
        // Add variant class
        if ($outline) {
            $buttonClass .= ' btn-outline-' . $variant;
        } else {
            $buttonClass .= ' btn-' . $variant;
        }
        
        // Add size class
        if (!empty($size)) {
            $buttonClass .= ' btn-' . $size;
        }
        
        // Add block class
        if ($block) {
            $buttonClass .= ' w-100';
        }
        
        // Add rounded classes
        if ($pill) {
            $buttonClass .= ' rounded-pill';
        } elseif ($rounded) {
            $buttonClass .= ' rounded';
        }
        
        // Add custom classes
        if (!empty($class)) {
            $buttonClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $buttonClass . '"';
        
        if ($disabled) {
            $attributes .= ' disabled';
        }
        
        if (!empty($onclick)) {
            $attributes .= ' onclick="' . htmlspecialchars($onclick, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($form)) {
            $attributes .= ' form="' . htmlspecialchars($form, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($formaction)) {
            $attributes .= ' formaction="' . htmlspecialchars($formaction, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($formmethod)) {
            $attributes .= ' formmethod="' . htmlspecialchars($formmethod, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($formtarget)) {
            $attributes .= ' formtarget="' . htmlspecialchars($formtarget, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($value)) {
            $attributes .= ' value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($name)) {
            $attributes .= ' name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if ($autofocus) {
            $attributes .= ' autofocus';
        }
        
        if (!empty($tabindex)) {
            $attributes .= ' tabindex="' . htmlspecialchars($tabindex, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build content
        $content = '';
        
        if ($loading) {
            $content .= '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>';
            $content .= htmlspecialchars($loadingText, ENT_QUOTES, 'UTF-8');
        } else {
            // Add icon
            if (!empty($icon)) {
                if ($iconPosition === 'right') {
                    $content .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
                    $content .= ' <i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i>';
                } else {
                    $content .= '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i> ';
                    $content .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
                }
            } else {
                $content .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        // Determine if it's a link button or regular button
        if (!empty($href)) {
            $html = '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
            if (!empty($target)) {
                $html .= ' target="' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= ' ' . $attributes . '>';
            $html .= $content;
            $html .= '</a>';
        } else {
            $html = '<button type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '" ' . $attributes . '>';
            $html .= $content;
            $html .= '</button>';
        }

        return $html;
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
     * Create a primary button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an outline button
     * 
     * @param string $text Button text
     * @param string $variant Button variant
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function outline($text, $variant = 'primary', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'variant' => $variant,
            'outline' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a link button
     * 
     * @param string $text Button text
     * @param string $href Link URL
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function link($text, $href, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'href' => $href,
            'variant' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button with icon
     * 
     * @param string $text Button text
     * @param string $icon Icon class
     * @param string $position Icon position (left/right)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withIcon($text, $icon, $position = 'left', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'icon' => $icon,
            'iconPosition' => $position
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a loading button
     * 
     * @param string $text Button text
     * @param string $loadingText Loading text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function loading($text, $loadingText = 'Loading...', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'loading' => true,
            'loadingText' => $loadingText
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a block button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function block($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'block' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a pill button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function pill($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'pill' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a submit button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function submit($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'submit'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a reset button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function reset($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'reset'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a disabled button
     * 
     * @param string $text Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function disabled($text, $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'disabled' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
