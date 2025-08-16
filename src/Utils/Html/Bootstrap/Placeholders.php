<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Placeholders Component
 * 
 * Creates Bootstrap placeholders with various sizes, colors, and animation options.
 * Supports different placeholder types, sizes, colors, and Bootstrap 5 classes.
 */
class Placeholders extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure placeholder ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'placeholder_' . uniqid();
        }
        
        // Ensure text is always set
        if (!isset($this->options['text'])) {
            $this->options['text'] = '';
        }
        
        // Ensure width is always set
        if (!isset($this->options['width'])) {
            $this->options['width'] = '';
        }
        
        // Ensure height is always set
        if (!isset($this->options['height'])) {
            $this->options['height'] = '';
        }
    }

    /**
     * Get default options for the placeholder
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'text' => '',
            'type' => 'text', // text, button, img, heading, paragraph, table, card, list
            'size' => '', // sm, lg
            'color' => '', // primary, secondary, success, danger, warning, info, light, dark
            'width' => '', // 25, 50, 75, 100, or custom value
            'height' => '', // custom height value
            'animation' => false, // true for glow animation
            'glow' => false, // true for glow animation (alias for animation)
            'wave' => false, // true for wave animation
            'class' => '',
            'style' => '',
            'data' => []
        ];
    }

    /**
     * Render the placeholder as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $text = $this->options['text'];
        $type = $this->options['type'];
        $size = $this->options['size'];
        $color = $this->options['color'];
        $width = $this->options['width'];
        $height = $this->options['height'];
        $animation = $this->options['animation'];
        $glow = $this->options['glow'];
        $wave = $this->options['wave'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $data = $this->options['data'];

        // Build placeholder classes
        $placeholderClass = 'placeholder';
        
        if (!empty($size)) {
            $placeholderClass .= ' placeholder-' . $size;
        }
        
        if (!empty($color)) {
            $placeholderClass .= ' bg-' . $color;
        }
        
        if ($animation || $glow) {
            $placeholderClass .= ' placeholder-glow';
        }
        
        if ($wave) {
            $placeholderClass .= ' placeholder-wave';
        }
        
        if (!empty($class)) {
            $placeholderClass .= ' ' . $class;
        }

        // Build placeholder attributes
        $placeholderAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $placeholderAttributes .= ' class="' . $placeholderClass . '"';
        
        if (!empty($style)) {
            $placeholderAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add width if specified
        if (!empty($width)) {
            if (is_numeric($width)) {
                $placeholderAttributes .= ' style="width: ' . $width . '%"';
            } else {
                $placeholderAttributes .= ' style="width: ' . htmlspecialchars($width, ENT_QUOTES, 'UTF-8') . '"';
            }
        }
        
        // Add height if specified
        if (!empty($height)) {
            $placeholderAttributes .= ' style="height: ' . htmlspecialchars($height, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $placeholderAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build placeholder content
        $placeholderContent = !empty($text) ? htmlspecialchars($text, ENT_QUOTES, 'UTF-8') : '';

        // Build placeholder element based on type
        switch ($type) {
            case 'button':
                return '<button ' . $placeholderAttributes . ' disabled>' . $placeholderContent . '</button>';
            
            case 'img':
                return '<img ' . $placeholderAttributes . ' alt="placeholder">';
            
            case 'heading':
                return '<h1 ' . $placeholderAttributes . '>' . $placeholderContent . '</h1>';
            
            case 'paragraph':
                return '<p ' . $placeholderAttributes . '>' . $placeholderContent . '</p>';
            
            case 'table':
                return '<table ' . $placeholderAttributes . '><tbody><tr><td>' . $placeholderContent . '</td></tr></tbody></table>';
            
            case 'card':
                return '<div class="card" style="width: 18rem;"><div class="card-body"><h5 ' . $placeholderAttributes . '>' . $placeholderContent . '</h5><p class="card-text"><span class="placeholder col-7"></span><span class="placeholder col-4"></span><span class="placeholder col-4"></span><span class="placeholder col-6"></span><span class="placeholder col-8"></span></p><a href="#" class="btn btn-primary disabled placeholder col-6"></a></div></div>';
            
            case 'list':
                return '<ul class="list-group"><li class="list-group-item"><span ' . $placeholderAttributes . '>' . $placeholderContent . '</span></li></ul>';
            
            case 'text':
            default:
                return '<span ' . $placeholderAttributes . '>' . $placeholderContent . '</span>';
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
     * Create a simple placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a text placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function text($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'text'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function button($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'button'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an image placeholder
     * 
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function img($options = null)
    {
        $defaultOptions = [
            'type' => 'img'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a heading placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function heading($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'heading'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a paragraph placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function paragraph($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'paragraph'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a table placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function table($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'table'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function card($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'card'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function list($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'list'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark colored placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a placeholder with glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function glow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a placeholder with wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function wave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a placeholder with animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function animated($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'animation' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a placeholder with specific width
     * 
     * @param string|int $width Width (25, 50, 75, 100, or custom value)
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withWidth($width, $text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'width' => $width
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a placeholder with specific height
     * 
     * @param string $height Height value
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHeight($height, $text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'height' => $height
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a 25% width placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function width25($text = '', $options = null)
    {
        return self::withWidth(25, $text, $options);
    }

    /**
     * Create a 50% width placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function width50($text = '', $options = null)
    {
        return self::withWidth(50, $text, $options);
    }

    /**
     * Create a 75% width placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function width75($text = '', $options = null)
    {
        return self::withWidth(75, $text, $options);
    }

    /**
     * Create a 100% width placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function width100($text = '', $options = null)
    {
        return self::withWidth(100, $text, $options);
    }

    /**
     * Create a small primary placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallPrimary($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'sm',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large primary placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largePrimary($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'lg',
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small animated placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallAnimated($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'sm',
            'animation' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large animated placeholder
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeAnimated($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'size' => 'lg',
            'animation' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button placeholder with glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function buttonGlow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'button',
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button placeholder with wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function buttonWave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'button',
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a heading placeholder with glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function headingGlow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'heading',
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a paragraph placeholder with wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function paragraphWave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'paragraph',
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card placeholder with glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function cardGlow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'card',
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list placeholder with wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function listWave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'list',
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a text placeholder with 50% width and glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function text50Glow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'text',
            'width' => 50,
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a text placeholder with 75% width and wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function text75Wave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'text',
            'width' => 75,
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small primary button placeholder with glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function smallPrimaryButtonGlow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'button',
            'size' => 'sm',
            'color' => 'primary',
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large success button placeholder with wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function largeSuccessButtonWave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'button',
            'size' => 'lg',
            'color' => 'success',
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a heading placeholder with 100% width and glow animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function heading100Glow($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'heading',
            'width' => 100,
            'glow' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a paragraph placeholder with 50% width and wave animation
     * 
     * @param string $text Placeholder text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function paragraph50Wave($text = '', $options = null)
    {
        $defaultOptions = [
            'text' => $text,
            'type' => 'paragraph',
            'width' => 50,
            'wave' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>