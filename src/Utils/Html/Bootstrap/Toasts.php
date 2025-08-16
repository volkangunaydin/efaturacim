<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Toasts Component
 * 
 * Creates Bootstrap toasts with various configurations and content.
 * Supports different colors, positions, animations, and Bootstrap 5 classes.
 */
class Toasts extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure toast ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'toast_' . uniqid();
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
     * Get default options for the toast
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'title' => 'Toast Title',
            'content' => 'Toast content goes here',
            'color' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
            'autohide' => true,
            'delay' => 5000, // delay in milliseconds
            'animation' => true,
            'show' => false, // show on load
            'position' => 'top-0 end-0', // toast position
            'headerClass' => '',
            'headerStyle' => '',
            'bodyClass' => '',
            'bodyStyle' => '',
            'class' => '',
            'style' => '',
            'data' => []
        ];
    }

    /**
     * Render the toast as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $title = $this->options['title'];
        $content = $this->options['content'];
        $color = $this->options['color'];
        $autohide = $this->options['autohide'];
        $delay = $this->options['delay'];
        $animation = $this->options['animation'];
        $show = $this->options['show'];
        $position = $this->options['position'];
        $headerClass = $this->options['headerClass'];
        $headerStyle = $this->options['headerStyle'];
        $bodyClass = $this->options['bodyClass'];
        $bodyStyle = $this->options['bodyStyle'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $data = $this->options['data'];

        // Build toast classes
        $toastClasses = 'toast';
        if (!empty($color)) {
            $toastClasses .= ' bg-' . $color;
        }
        if (!$animation) {
            $toastClasses .= ' fade';
        }
        if ($show) {
            $toastClasses .= ' show';
        }
        if (!empty($class)) {
            $toastClasses .= ' ' . $class;
        }

        // Build toast attributes
        $toastAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $toastAttributes .= ' class="' . $toastClasses . '"';
        $toastAttributes .= ' role="alert"';
        $toastAttributes .= ' aria-live="assertive"';
        $toastAttributes .= ' aria-atomic="true"';
        
        if (!$autohide) {
            $toastAttributes .= ' data-bs-autohide="false"';
        }
        
        if ($delay > 0) {
            $toastAttributes .= ' data-bs-delay="' . $delay . '"';
        }
        
        if (!$animation) {
            $toastAttributes .= ' data-bs-animation="false"';
        }
        
        if (!empty($style)) {
            $toastAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $toastAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build toast HTML
        $toastHtml = $this->buildToast($toastAttributes, $title, $content, $headerClass, $headerStyle, $bodyClass, $bodyStyle);

        return $toastHtml;
    }

    /**
     * Build toast element
     * 
     * @param string $attributes
     * @param string $title
     * @param string $content
     * @param string $headerClass
     * @param string $headerStyle
     * @param string $bodyClass
     * @param string $bodyStyle
     * @return string
     */
    private function buildToast($attributes, $title, $content, $headerClass, $headerStyle, $bodyClass, $bodyStyle)
    {
        $html = '<div ' . $attributes . '>';
        
        // Build header
        if (!empty($title)) {
            $headerAttributes = 'class="toast-header';
            if (!empty($headerClass)) {
                $headerAttributes .= ' ' . $headerClass;
            }
            $headerAttributes .= '"';
            
            if (!empty($headerStyle)) {
                $headerAttributes .= ' style="' . htmlspecialchars($headerStyle, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            $html .= '<div ' . $headerAttributes . '>';
            $html .= '<strong class="me-auto">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</strong>';
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
            $html .= '</div>';
        }
        
        // Build body
        $bodyAttributes = 'class="toast-body';
        if (!empty($bodyClass)) {
            $bodyAttributes .= ' ' . $bodyClass;
        }
        $bodyAttributes .= '"';
        
        if (!empty($bodyStyle)) {
            $bodyAttributes .= ' style="' . htmlspecialchars($bodyStyle, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        $html .= '<div ' . $bodyAttributes . '>' . $content . '</div>';
        $html .= '</div>';
        
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
     * Create a simple toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'primary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a secondary toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'secondary'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'success'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'danger'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'warning'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'info'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast without autohide
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function persistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function showOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary persistent toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryPersistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'primary',
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success persistent toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successPersistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'success',
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger persistent toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerPersistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'danger',
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning persistent toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningPersistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'warning',
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info persistent toast
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoPersistent($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'info',
            'autohide' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryWithDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'primary',
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successWithDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'success',
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerWithDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'danger',
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningWithDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'warning',
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info toast with custom delay
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param int $delay Delay in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoWithDelay($title, $content, $delay = 3000, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'info',
            'delay' => $delay
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryNoAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'primary',
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successNoAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'success',
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerNoAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'danger',
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningNoAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'warning',
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info toast without animation
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoNoAnimation($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'info',
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primaryShowOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'primary',
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function successShowOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'success',
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a danger toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dangerShowOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'danger',
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a warning toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warningShowOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'warning',
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an info toast that shows on load
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function infoShowOnLoad($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'color' => 'info',
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast with custom position
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param string $position Toast position
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withPosition($title, $content, $position = 'top-0 end-0', $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'position' => $position
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast with custom header class
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param string $headerClass Custom header class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHeaderClass($title, $content, $headerClass, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'headerClass' => $headerClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast with custom body class
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param string $bodyClass Custom body class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withBodyClass($title, $content, $bodyClass, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'bodyClass' => $bodyClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toast with custom data attributes
     * 
     * @param string $title Toast title
     * @param string $content Toast content
     * @param array $data Custom data attributes
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomData($title, $content, $data = [], $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'data' => $data
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
