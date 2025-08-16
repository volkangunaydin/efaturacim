<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Modal Component
 * 
 * Creates a Bootstrap modal with header, body, footer, and various configuration options.
 * Supports different sizes, scrollable content, centered positioning, and Bootstrap 5 classes.
 */
class Modal extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure modal ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'modal_' . uniqid();
        }
        
        // Ensure content is always set
        if (!isset($this->options['content'])) {
            $this->options['content'] = '';
        }
        
        // Ensure header is always set
        if (!isset($this->options['header'])) {
            $this->options['header'] = '';
        }
        
        // Ensure footer is always set
        if (!isset($this->options['footer'])) {
            $this->options['footer'] = '';
        }
    }

    /**
     * Get default options for the modal
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'title' => 'Modal Title',
            'content' => '',
            'header' => '',
            'footer' => '',
            'size' => '', // sm, lg, xl
            'fullscreen' => false,
            'fullscreenBreakpoint' => '', // sm, md, lg, xl, xxl
            'scrollable' => false,
            'centered' => false,
            'staticBackdrop' => false,
            'backdrop' => true, // true, false, 'static'
            'keyboard' => true,
            'focus' => true,
            'show' => false,
            'class' => '',
            'style' => '',
            'dialogClass' => '',
            'dialogStyle' => '',
            'contentClass' => '',
            'contentStyle' => '',
            'headerClass' => '',
            'headerStyle' => '',
            'bodyClass' => '',
            'bodyStyle' => '',
            'footerClass' => '',
            'footerStyle' => '',
            'closeButton' => true,
            'closeButtonText' => '&times;',
            'closeButtonClass' => '',
            'closeButtonStyle' => '',
            'closeButtonLabel' => 'Close',
            'data' => []
        ];
    }

    /**
     * Render the modal as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $title = $this->options['title'];
        $content = $this->options['content'];
        $header = $this->options['header'];
        $footer = $this->options['footer'];
        $size = $this->options['size'];
        $fullscreen = $this->options['fullscreen'];
        $fullscreenBreakpoint = $this->options['fullscreenBreakpoint'];
        $scrollable = $this->options['scrollable'];
        $centered = $this->options['centered'];
        $staticBackdrop = $this->options['staticBackdrop'];
        $backdrop = $this->options['backdrop'];
        $keyboard = $this->options['keyboard'];
        $focus = $this->options['focus'];
        $show = $this->options['show'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $dialogClass = $this->options['dialogClass'];
        $dialogStyle = $this->options['dialogStyle'];
        $contentClass = $this->options['contentClass'];
        $contentStyle = $this->options['contentStyle'];
        $headerClass = $this->options['headerClass'];
        $headerStyle = $this->options['headerStyle'];
        $bodyClass = $this->options['bodyClass'];
        $bodyStyle = $this->options['bodyStyle'];
        $footerClass = $this->options['footerClass'];
        $footerStyle = $this->options['footerStyle'];
        $closeButton = $this->options['closeButton'];
        $closeButtonText = $this->options['closeButtonText'];
        $closeButtonClass = $this->options['closeButtonClass'];
        $closeButtonStyle = $this->options['closeButtonStyle'];
        $closeButtonLabel = $this->options['closeButtonLabel'];
        $data = $this->options['data'];

        // Build modal classes
        $modalClass = 'modal fade';
        if ($show) {
            $modalClass .= ' show';
        }
        if (!empty($class)) {
            $modalClass .= ' ' . $class;
        }

        // Build modal attributes
        $modalAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $modalAttributes .= ' class="' . $modalClass . '"';
        $modalAttributes .= ' tabindex="-1"';
        $modalAttributes .= ' aria-labelledby="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '_label"';
        $modalAttributes .= ' aria-hidden="true"';
        
        if (!empty($style)) {
            $modalAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $modalAttributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build dialog classes
        $dialogClasses = 'modal-dialog';
        if ($scrollable) {
            $dialogClasses .= ' modal-dialog-scrollable';
        }
        if ($centered) {
            $dialogClasses .= ' modal-dialog-centered';
        }
        if (!empty($size)) {
            $dialogClasses .= ' modal-' . $size;
        }
        if ($fullscreen) {
            $dialogClasses .= ' modal-fullscreen';
            if (!empty($fullscreenBreakpoint)) {
                $dialogClasses .= '-' . $fullscreenBreakpoint . '-down';
            }
        }
        if (!empty($dialogClass)) {
            $dialogClasses .= ' ' . $dialogClass;
        }

        // Build dialog attributes
        $dialogAttributes = 'class="' . $dialogClasses . '"';
        if (!empty($dialogStyle)) {
            $dialogAttributes .= ' style="' . htmlspecialchars($dialogStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build content classes
        $contentClasses = 'modal-content';
        if (!empty($contentClass)) {
            $contentClasses .= ' ' . $contentClass;
        }

        // Build content attributes
        $contentAttributes = 'class="' . $contentClasses . '"';
        if (!empty($contentStyle)) {
            $contentAttributes .= ' style="' . htmlspecialchars($contentStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $modalAttributes . '>';
        $html .= '<div ' . $dialogAttributes . '>';
        $html .= '<div ' . $contentAttributes . '>';
        
        // Build header
        if (!empty($header) || !empty($title)) {
            $html .= $this->buildHeader($id, $title, $header, $closeButton, $closeButtonText, $closeButtonClass, $closeButtonStyle, $closeButtonLabel, $headerClass, $headerStyle);
        }
        
        // Build body
        $html .= $this->buildBody($content, $bodyClass, $bodyStyle);
        
        // Build footer
        if (!empty($footer)) {
            $html .= $this->buildFooter($footer, $footerClass, $footerStyle);
        }
        
        $html .= '</div>'; // modal-content
        $html .= '</div>'; // modal-dialog
        $html .= '</div>'; // modal

        return $html;
    }

    /**
     * Build modal header
     * 
     * @param string $modalId
     * @param string $title
     * @param string $header
     * @param bool $closeButton
     * @param string $closeButtonText
     * @param string $closeButtonClass
     * @param string $closeButtonStyle
     * @param string $closeButtonLabel
     * @param string $headerClass
     * @param string $headerStyle
     * @return string
     */
    private function buildHeader($modalId, $title, $header, $closeButton, $closeButtonText, $closeButtonClass, $closeButtonStyle, $closeButtonLabel, $headerClass, $headerStyle)
    {
        // Build header classes
        $headerClasses = 'modal-header';
        if (!empty($headerClass)) {
            $headerClasses .= ' ' . $headerClass;
        }

        // Build header attributes
        $headerAttributes = 'class="' . $headerClasses . '"';
        if (!empty($headerStyle)) {
            $headerAttributes .= ' style="' . htmlspecialchars($headerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $headerAttributes . '>';
        
        // Add title
        if (!empty($title)) {
            $html .= '<h5 class="modal-title" id="' . htmlspecialchars($modalId, ENT_QUOTES, 'UTF-8') . '_label">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h5>';
        }
        
        // Add custom header content
        if (!empty($header)) {
            $html .= $header;
        }
        
        // Add close button
        if ($closeButton) {
            $html .= $this->buildCloseButton($closeButtonText, $closeButtonClass, $closeButtonStyle, $closeButtonLabel);
        }
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Build close button
     * 
     * @param string $buttonText
     * @param string $buttonClass
     * @param string $buttonStyle
     * @param string $buttonLabel
     * @return string
     */
    private function buildCloseButton($buttonText, $buttonClass, $buttonStyle, $buttonLabel)
    {
        // Build button classes
        $buttonClasses = 'btn-close';
        if (!empty($buttonClass)) {
            $buttonClasses .= ' ' . $buttonClass;
        }

        // Build button attributes
        $buttonAttributes = 'type="button"';
        $buttonAttributes .= ' class="' . $buttonClasses . '"';
        $buttonAttributes .= ' data-bs-dismiss="modal"';
        $buttonAttributes .= ' aria-label="' . htmlspecialchars($buttonLabel, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($buttonStyle)) {
            $buttonAttributes .= ' style="' . htmlspecialchars($buttonStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<button ' . $buttonAttributes . '>' . $buttonText . '</button>';
    }

    /**
     * Build modal body
     * 
     * @param string $content
     * @param string $bodyClass
     * @param string $bodyStyle
     * @return string
     */
    private function buildBody($content, $bodyClass, $bodyStyle)
    {
        // Build body classes
        $bodyClasses = 'modal-body';
        if (!empty($bodyClass)) {
            $bodyClasses .= ' ' . $bodyClass;
        }

        // Build body attributes
        $bodyAttributes = 'class="' . $bodyClasses . '"';
        if (!empty($bodyStyle)) {
            $bodyAttributes .= ' style="' . htmlspecialchars($bodyStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<div ' . $bodyAttributes . '>' . $content . '</div>';
    }

    /**
     * Build modal footer
     * 
     * @param string $footer
     * @param string $footerClass
     * @param string $footerStyle
     * @return string
     */
    private function buildFooter($footer, $footerClass, $footerStyle)
    {
        // Build footer classes
        $footerClasses = 'modal-footer';
        if (!empty($footerClass)) {
            $footerClasses .= ' ' . $footerClass;
        }

        // Build footer attributes
        $footerAttributes = 'class="' . $footerClasses . '"';
        if (!empty($footerStyle)) {
            $footerAttributes .= ' style="' . htmlspecialchars($footerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<div ' . $footerAttributes . '>' . $footer . '</div>';
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
     * Create a simple modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
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
     * Create a modal with custom header
     * 
     * @param string $header Custom header HTML
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomHeader($header, $content, $options = null)
    {
        $defaultOptions = [
            'header' => $header,
            'content' => $content
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal with footer
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param string $footer Modal footer
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withFooter($title, $content, $footer, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an extra large modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function xl($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'size' => 'xl'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a fullscreen modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreen($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'fullscreen' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a fullscreen modal with breakpoint
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param string $breakpoint Breakpoint (sm, md, lg, xl, xxl)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenWithBreakpoint($title, $content, $breakpoint, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'fullscreen' => true,
            'fullscreenBreakpoint' => $breakpoint
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollable modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function scrollable($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'scrollable' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a centered modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function centered($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'centered' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal with static backdrop
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function staticBackdrop($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'staticBackdrop' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal without backdrop
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noBackdrop($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'backdrop' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal without keyboard support
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noKeyboard($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'keyboard' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal without focus trap
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noFocus($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'focus' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal that shows by default
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function show($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'show' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal without close button
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noCloseButton($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'closeButton' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a modal with custom close button
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param string $closeButtonText Close button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomCloseButton($title, $content, $closeButtonText, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'closeButtonText' => $closeButtonText
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a fullscreen small modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenSmall($title, $content, $options = null)
    {
        return self::fullscreenWithBreakpoint($title, $content, 'sm', $options);
    }

    /**
     * Create a fullscreen medium modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenMedium($title, $content, $options = null)
    {
        return self::fullscreenWithBreakpoint($title, $content, 'md', $options);
    }

    /**
     * Create a fullscreen large modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenLarge($title, $content, $options = null)
    {
        return self::fullscreenWithBreakpoint($title, $content, 'lg', $options);
    }

    /**
     * Create a fullscreen extra large modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenXl($title, $content, $options = null)
    {
        return self::fullscreenWithBreakpoint($title, $content, 'xl', $options);
    }

    /**
     * Create a fullscreen extra extra large modal
     * 
     * @param string $title Modal title
     * @param string $content Modal content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fullscreenXxl($title, $content, $options = null)
    {
        return self::fullscreenWithBreakpoint($title, $content, 'xxl', $options);
    }

    /**
     * Create a confirmation modal
     * 
     * @param string $title Modal title
     * @param string $message Confirmation message
     * @param string $confirmText Confirm button text
     * @param string $cancelText Cancel button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function confirmation($title, $message, $confirmText = 'Confirm', $cancelText = 'Cancel', $options = null)
    {
        $footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . htmlspecialchars($cancelText, ENT_QUOTES, 'UTF-8') . '</button>';
        $footer .= '<button type="button" class="btn btn-primary">' . htmlspecialchars($confirmText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $message,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a form modal
     * 
     * @param string $title Modal title
     * @param string $formContent Form HTML content
     * @param string $submitText Submit button text
     * @param string $cancelText Cancel button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function form($title, $formContent, $submitText = 'Submit', $cancelText = 'Cancel', $options = null)
    {
        $footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . htmlspecialchars($cancelText, ENT_QUOTES, 'UTF-8') . '</button>';
        $footer .= '<button type="submit" class="btn btn-primary">' . htmlspecialchars($submitText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $formContent,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an alert modal
     * 
     * @param string $title Modal title
     * @param string $message Alert message
     * @param string $buttonText Button text
     * @param string $buttonVariant Button variant (primary, secondary, success, etc.)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function alert($title, $message, $buttonText = 'OK', $buttonVariant = 'primary', $options = null)
    {
        $footer = '<button type="button" class="btn btn-' . $buttonVariant . '" data-bs-dismiss="modal">' . htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $message,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success alert modal
     * 
     * @param string $title Modal title
     * @param string $message Success message
     * @param string $buttonText Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($title, $message, $buttonText = 'OK', $options = null)
    {
        return self::alert($title, $message, $buttonText, 'success', $options);
    }

    /**
     * Create a danger alert modal
     * 
     * @param string $title Modal title
     * @param string $message Danger message
     * @param string $buttonText Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($title, $message, $buttonText = 'OK', $options = null)
    {
        return self::alert($title, $message, $buttonText, 'danger', $options);
    }

    /**
     * Create a warning alert modal
     * 
     * @param string $title Modal title
     * @param string $message Warning message
     * @param string $buttonText Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($title, $message, $buttonText = 'OK', $options = null)
    {
        return self::alert($title, $message, $buttonText, 'warning', $options);
    }

    /**
     * Create an info alert modal
     * 
     * @param string $title Modal title
     * @param string $message Info message
     * @param string $buttonText Button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($title, $message, $buttonText = 'OK', $options = null)
    {
        return self::alert($title, $message, $buttonText, 'info', $options);
    }
}
?>
