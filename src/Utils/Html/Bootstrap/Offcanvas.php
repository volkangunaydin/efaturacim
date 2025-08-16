<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Offcanvas Component
 * 
 * Creates a Bootstrap offcanvas with header, body, footer, and various configuration options.
 * Supports different placements, sizes, scrollable content, and Bootstrap 5 classes.
 */
class Offcanvas extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure offcanvas ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'offcanvas_' . uniqid();
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
     * Get default options for the offcanvas
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'title' => 'Offcanvas Title',
            'content' => '',
            'header' => '',
            'footer' => '',
            'placement' => 'start', // start, end, top, bottom
            'size' => '', // sm, md, lg, xl
            'scrollable' => false,
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
     * Render the offcanvas as HTML string
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
        $placement = $this->options['placement'];
        $size = $this->options['size'];
        $scrollable = $this->options['scrollable'];
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

        // Build offcanvas classes
        $offcanvasClass = 'offcanvas offcanvas-' . $placement;
        if ($show) {
            $offcanvasClass .= ' show';
        }
        if (!empty($class)) {
            $offcanvasClass .= ' ' . $class;
        }

        // Build offcanvas attributes
        $offcanvasAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $offcanvasAttributes .= ' class="' . $offcanvasClass . '"';
        $offcanvasAttributes .= ' tabindex="-1"';
        $offcanvasAttributes .= ' aria-labelledby="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '_label"';
        $offcanvasAttributes .= ' aria-hidden="true"';
        
        if (!empty($style)) {
            $offcanvasAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $offcanvasAttributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build dialog classes
        $dialogClasses = 'offcanvas-dialog';
        if ($scrollable) {
            $dialogClasses .= ' offcanvas-dialog-scrollable';
        }
        if (!empty($size)) {
            $dialogClasses .= ' offcanvas-' . $size;
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
        $contentClasses = 'offcanvas-content';
        if (!empty($contentClass)) {
            $contentClasses .= ' ' . $contentClass;
        }

        // Build content attributes
        $contentAttributes = 'class="' . $contentClasses . '"';
        if (!empty($contentStyle)) {
            $contentAttributes .= ' style="' . htmlspecialchars($contentStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $offcanvasAttributes . '>';
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
        
        $html .= '</div>'; // offcanvas-content
        $html .= '</div>'; // offcanvas-dialog
        $html .= '</div>'; // offcanvas

        return $html;
    }

    /**
     * Build offcanvas header
     * 
     * @param string $offcanvasId
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
    private function buildHeader($offcanvasId, $title, $header, $closeButton, $closeButtonText, $closeButtonClass, $closeButtonStyle, $closeButtonLabel, $headerClass, $headerStyle)
    {
        // Build header classes
        $headerClasses = 'offcanvas-header';
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
            $html .= '<h5 class="offcanvas-title" id="' . htmlspecialchars($offcanvasId, ENT_QUOTES, 'UTF-8') . '_label">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h5>';
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
        $buttonAttributes .= ' data-bs-dismiss="offcanvas"';
        $buttonAttributes .= ' aria-label="' . htmlspecialchars($buttonLabel, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($buttonStyle)) {
            $buttonAttributes .= ' style="' . htmlspecialchars($buttonStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<button ' . $buttonAttributes . '>' . $buttonText . '</button>';
    }

    /**
     * Build offcanvas body
     * 
     * @param string $content
     * @param string $bodyClass
     * @param string $bodyStyle
     * @return string
     */
    private function buildBody($content, $bodyClass, $bodyStyle)
    {
        // Build body classes
        $bodyClasses = 'offcanvas-body';
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
     * Build offcanvas footer
     * 
     * @param string $footer
     * @param string $footerClass
     * @param string $footerStyle
     * @return string
     */
    private function buildFooter($footer, $footerClass, $footerStyle)
    {
        // Build footer classes
        $footerClasses = 'offcanvas-footer';
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
     * Create a simple offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas with custom header
     * 
     * @param string $header Custom header HTML
     * @param string $content Offcanvas content
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
     * Create an offcanvas with footer
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param string $footer Offcanvas footer
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
     * Create a start offcanvas (left side)
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function start($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'placement' => 'start'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an end offcanvas (right side)
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function end($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'placement' => 'end'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a top offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function top($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'placement' => 'top'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a bottom offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottom($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'placement' => 'bottom'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create a medium offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function medium($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'size' => 'md'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an extra large offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create a scrollable offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas without backdrop
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas with static backdrop
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function staticBackdrop($title, $content, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $content,
            'backdrop' => 'static'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an offcanvas without keyboard support
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas without focus trap
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas that shows by default
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas without close button
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create an offcanvas with custom close button
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
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
     * Create a left sidebar offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function leftSidebar($title, $content, $options = null)
    {
        return self::start($title, $content, $options);
    }

    /**
     * Create a right sidebar offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightSidebar($title, $content, $options = null)
    {
        return self::end($title, $content, $options);
    }

    /**
     * Create a top drawer offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function topDrawer($title, $content, $options = null)
    {
        return self::top($title, $content, $options);
    }

    /**
     * Create a bottom drawer offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $content Offcanvas content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function bottomDrawer($title, $content, $options = null)
    {
        return self::bottom($title, $content, $options);
    }

    /**
     * Create a navigation offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $navigationContent Navigation content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function navigation($title, $navigationContent, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $navigationContent,
            'placement' => 'start'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a settings offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $settingsContent Settings content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function settings($title, $settingsContent, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'content' => $settingsContent,
            'placement' => 'end'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a form offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $formContent Form content
     * @param string $submitText Submit button text
     * @param string $cancelText Cancel button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function form($title, $formContent, $submitText = 'Submit', $cancelText = 'Cancel', $options = null)
    {
        $footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">' . htmlspecialchars($cancelText, ENT_QUOTES, 'UTF-8') . '</button>';
        $footer .= '<button type="submit" class="btn btn-primary">' . htmlspecialchars($submitText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $formContent,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a confirmation offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $message Confirmation message
     * @param string $confirmText Confirm button text
     * @param string $cancelText Cancel button text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function confirmation($title, $message, $confirmText = 'Confirm', $cancelText = 'Cancel', $options = null)
    {
        $footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">' . htmlspecialchars($cancelText, ENT_QUOTES, 'UTF-8') . '</button>';
        $footer .= '<button type="button" class="btn btn-primary">' . htmlspecialchars($confirmText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $message,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an alert offcanvas
     * 
     * @param string $title Offcanvas title
     * @param string $message Alert message
     * @param string $buttonText Button text
     * @param string $buttonVariant Button variant (primary, secondary, success, etc.)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function alert($title, $message, $buttonText = 'OK', $buttonVariant = 'primary', $options = null)
    {
        $footer = '<button type="button" class="btn btn-' . $buttonVariant . '" data-bs-dismiss="offcanvas">' . htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') . '</button>';
        
        $defaultOptions = [
            'title' => $title,
            'content' => $message,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a success alert offcanvas
     * 
     * @param string $title Offcanvas title
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
     * Create a danger alert offcanvas
     * 
     * @param string $title Offcanvas title
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
     * Create a warning alert offcanvas
     * 
     * @param string $title Offcanvas title
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
     * Create an info alert offcanvas
     * 
     * @param string $title Offcanvas title
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
