<?php
namespace Efaturacim\Util\Utils\Html\MiscJs;

use Efaturacim\Util\Utils\Html\HtmlComponent;
use Efaturacim\Util\Utils\Array\AssocArray;
use Efaturacim\Util\Utils\CastUtil;

/**
 * JqueryToast Component
 * 
 * This class provides a wrapper for the jQuery Toast plugin (https://kamranahmed.info/toast)
 * It allows easy creation and management of toast notifications with various options.
 * 
 * Usage:
 * $toast = new JqueryToast([
 *     'text' => 'Your message here',
 *     'heading' => 'Success',
 *     'icon' => 'success',
 *     'position' => 'top-right'
 * ]);
 */
class JqueryToast extends HtmlComponent {
    
    /**
     * @var string Asset path key for jQuery Toast resources
     */
    protected $assetPathKey = 'jquery_toast';
    
    /**
     * Get default options for the component
     * 
     * @return array Default options
     */
    public function getDefaultOptions() {
        return [
            'text' => 'Toast message',
            'heading' => null,
            'icon' => null,
            'showHideTransition' => 'fade',
            'allowToastClose' => true,
            'hideAfter' => 3000,
            'stack' => 5,
            'position' => 'bottom-left',
            'bgColor' => '#444',
            'textColor' => '#eee',
            'textAlign' => 'left',
            'loader' => true,
            'loaderBg' => '#9EC600',
            'width' => null, // Added width option
            'beforeShow' => null,
            'afterShown' => null,
            'beforeHide' => null,
            'afterHidden' => null,
            'delay' => 0,
            'js'=>'https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.js',
            'css'=>'https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.css'
        ];
    }
    
    /**
     * Initialize the component
     */
    public function initMe() {
        // Component-specific initialization if needed
    }
    
    /**
     * Render the component as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null) {
        // This component doesn't generate HTML directly
        // It generates JavaScript to show the toast
        return '';
    }
    
    /**
     * Get JavaScript code lines for the component
     * 
     * @return array JavaScript code lines
     */
    public function getJsLines() {
        $options = $this->options;
        
        // Build the toast options object
        $toastOptions = [];
        
        // Required text
        $toastOptions[] = "text: " . json_encode($options['text']);
        
        // Optional heading
        if (!empty($options['heading'])) {
            $toastOptions[] = "heading: " . json_encode($options['heading']);
        }
        
        // Optional icon
        if (!empty($options['icon'])) {
            $toastOptions[] = "icon: " . json_encode($options['icon']);
        }
        
        // Show/hide transition
        $toastOptions[] = "showHideTransition: " . json_encode($options['showHideTransition']);
        
        // Allow toast close
        $toastOptions[] = "allowToastClose: " . ($options['allowToastClose'] ? 'true' : 'false');
        
        // Hide after timer
        if ($options['hideAfter'] === false) {
            $toastOptions[] = "hideAfter: false";
        } else {
            $toastOptions[] = "hideAfter: " . intval($options['hideAfter']);
        }
        
        // Stack
        if ($options['stack'] === false) {
            $toastOptions[] = "stack: false";
        } else {
            $toastOptions[] = "stack: " . intval($options['stack']);
        }
        
        // Position
        if (is_array($options['position'])) {
            $toastOptions[] = "position: " . json_encode($options['position']);
        } else {
            $toastOptions[] = "position: " . json_encode($options['position']);
        }
        
        // Background color
        $toastOptions[] = "bgColor: " . json_encode($options['bgColor']);
        
        // Text color
        $toastOptions[] = "textColor: " . json_encode($options['textColor']);
        
        // Text alignment
        $toastOptions[] = "textAlign: " . json_encode($options['textAlign']);
        
        // Loader
        $toastOptions[] = "loader: " . ($options['loader'] ? 'true' : 'false');
        
        // Loader background
        $toastOptions[] = "loaderBg: " . json_encode($options['loaderBg']);
        
        // Width
        if (!empty($options['width'])) {
            $toastOptions[] = "width: " . json_encode($options['width']);
        }
        
        // Event handlers
        if (!empty($options['beforeShow'])) {
            $toastOptions[] = "beforeShow: " . $options['beforeShow'];
        }
        
        if (!empty($options['afterShown'])) {
            $toastOptions[] = "afterShown: " . $options['afterShown'];
        }
        
        if (!empty($options['beforeHide'])) {
            $toastOptions[] = "beforeHide: " . $options['beforeHide'];
        }
        
        if (!empty($options['afterHidden'])) {
            $toastOptions[] = "afterHidden: " . $options['afterHidden'];
        }
        
        // Build the final JavaScript
        $jsCode = "$.toast({\n";
        $jsCode .= "    " . implode(",\n    ", $toastOptions) . "\n";
        $jsCode .= "});";
        
        $delay = AssocArray::getVal($this->options, 'delay', 0, CastUtil::$DATA_INT);
        if ($delay > 0) {
            // Wrap in setTimeout for delay
            $jsCode = "setTimeout(function() {\n    " . $jsCode . "\n}, " . $delay . ");";
        }        
        return [$jsCode];
        
    }
    
    /**
     * Get JavaScript code lines for component initialization
     * 
     * @return array|null JavaScript initialization code lines
     */
    public function getJsLinesForInit() {
        // No initialization needed for toast
        return null;
    }
    
    /**
     * Get JavaScript files required by the component
     * 
     * @return array JavaScript file paths
     */
    public function getJsFiles() {
        if($this->hasAssetPath()){
            return ['jquery'=>null,''.$this->assetPathKey=>$this->assetPath."query.toast.min.js"];   
        }else{
            return ['jquery'=>@$this->options['jquery'],''.$this->assetPathKey=>@$this->options['js']];
        }                
    }
    
    /**
     * Get CSS files required by the component
     * 
     * @return array CSS file paths
     */
    public function getCssFiles() {
        if($this->hasAssetPath()){
            return ['bootstrap'=>null,''.$this->assetPathKey=>$this->assetPath."jquery.toast.min.css"];
        }else{
            return ['bootstrap'=>null,''.$this->assetPathKey=>@$this->options['css']];
        }
    }
    
    /**
     * Create a simple toast with just text
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function simple($doc, $text, $options = null) {
        $options = AssocArray::newArray(['text' => $text], $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Create a success toast
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function success($doc, $text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'success', 'bgColor' => '#28a745'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Create an error toast
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function error($doc, $text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'error', 'bgColor' => '#dc3545'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Create a warning toast
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function warning($doc, $text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'warning', 'bgColor' => '#ffc107'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Create an info toast
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function info($doc, $text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'info', 'bgColor' => '#17a2b8'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Create a sticky toast (doesn't auto-hide)
     * 
     * @param mixed $doc Document context
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param string|null $icon Optional icon
     * @param array|null $options Additional options
     * @return string HTML output
     */
    public static function sticky($doc, $text, $heading = null, $icon = null, $options = null) {
        $defaultOptions = ['text' => $text, 'hideAfter' => false];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        if ($icon) {
            $defaultOptions['icon'] = $icon;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return (new self($options))->toHtml($doc);
    }
    
    /**
     * Reset all toasts
     * 
     * @param mixed $doc Document context
     * @return string HTML output with reset JavaScript
     */
    public static function resetAll($doc) {
        $jsCode = '$.toast().reset("all");';
        return "<script>$jsCode</script>";
    }
    
    /**
     * Set custom position for toast
     * 
     * @param string|array $position Position string or array with top/bottom/left/right values
     * @return JqueryToast
     */
    public function setPosition($position) {
        $this->options['position'] = $position;
        return $this;
    }
    
    /**
     * Set custom colors
     * 
     * @param string $bgColor Background color
     * @param string $textColor Text color
     * @return JqueryToast
     */
    public function setColors($bgColor, $textColor) {
        $this->options['bgColor'] = $bgColor;
        $this->options['textColor'] = $textColor;
        return $this;
    }
    
    /**
     * Set custom timing
     * 
     * @param int|false $hideAfter Time in milliseconds or false for sticky
     * @return JqueryToast
     */
    public function setTiming($hideAfter) {
        $this->options['hideAfter'] = $hideAfter;
        return $this;
    }
    
    /**
     * Set transition type
     * 
     * @param string $transition 'fade', 'slide', or 'plain'
     * @return JqueryToast
     */
    public function setTransition($transition) {
        $this->options['showHideTransition'] = $transition;
        return $this;
    }
    
    /**
     * Set custom width for toast
     * 
     * @param string $width Width value (e.g., '300px', '50%', 'auto')
     * @return JqueryToast
     */
    public function setWidth($width) {
        $this->options['width'] = $width;
        return $this;
    }
    
    /**
     * Set position to top-right
     * 
     * @return JqueryToast
     */
    public function setPositionTopRight() {
        $this->options['position'] = 'top-right';
        return $this;
    }
    
    /**
     * Set position to top-left
     * 
     * @return JqueryToast
     */
    public function setPositionTopLeft() {
        $this->options['position'] = 'top-left';
        return $this;
    }
    
    /**
     * Set position to top-center
     * 
     * @return JqueryToast
     */
    public function setPositionTopCenter() {
        $this->options['position'] = 'top-center';
        return $this;
    }
    
    /**
     * Set position to bottom-right
     * 
     * @return JqueryToast
     */
    public function setPositionBottomRight() {
        $this->options['position'] = 'bottom-right';
        return $this;
    }
    
    /**
     * Set position to bottom-left
     * 
     * @return JqueryToast
     */
    public function setPositionBottomLeft() {
        $this->options['position'] = 'bottom-left';
        return $this;
    }
    
    /**
     * Set position to bottom-center
     * 
     * @return JqueryToast
     */
    public function setPositionBottomCenter() {
        $this->options['position'] = 'bottom-center';
        return $this;
    }
    
    /**
     * Set position to mid-top
     * 
     * @return JqueryToast
     */
    public function setPositionMidTop() {
        $this->options['position'] = 'mid-top';
        return $this;
    }
    
    /**
     * Set position to mid-bottom
     * 
     * @return JqueryToast
     */
    public function setPositionMidBottom() {
        $this->options['position'] = 'mid-bottom';
        return $this;
    }
    
    /**
     * Set position to center
     * 
     * @return JqueryToast
     */
    public function setPositionCenter() {
        $this->options['position'] = 'center';
        return $this;
    }
    
    /**
     * Disable stacking for this toast
     * 
     * @return JqueryToast
     */
    public function setNoStack() {
        $this->options['stack'] = false;
        return $this;
    }
    
    /**
     * Set custom stack value for this toast
     * 
     * @param int $stack Stack value (higher numbers = separate stacks)
     * @return JqueryToast
     */
    public function setStack($stack) {
        $this->options['stack'] = intval($stack);
        return $this;
    }
    
    /**
     * Create a new success toast instance
     * 
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newSuccess($text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'success', 'bgColor' => '#28a745'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return new self($options);
    }
    
    /**
     * Create a new error toast instance
     * 
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newError($text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'error', 'bgColor' => '#dc3545'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return new self($options);
    }
    
    /**
     * Create a new warning toast instance
     * 
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newWarning($text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'warning', 'bgColor' => '#ffc107'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return new self($options);
    }
    
    /**
     * Create a new info toast instance
     * 
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newInfo($text, $heading = null, $options = null) {
        $defaultOptions = ['text' => $text, 'icon' => 'info', 'bgColor' => '#17a2b8'];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return new self($options);
    }
    
    /**
     * Create a new sticky toast instance
     * 
     * @param string $text Toast message
     * @param string|null $heading Optional heading
     * @param string|null $icon Optional icon
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newSticky($text, $heading = null, $icon = null, $options = null) {
        $defaultOptions = ['text' => $text, 'hideAfter' => false];
        if ($heading) {
            $defaultOptions['heading'] = $heading;
        }
        if ($icon) {
            $defaultOptions['icon'] = $icon;
        }
        $options = AssocArray::newArray($defaultOptions, $options);
        return new self($options);
    }
    
    /**
     * Create a new simple toast instance
     * 
     * @param string $text Toast message
     * @param array|null $options Additional options
     * @return JqueryToast
     */
    public static function newSimple($text, $options = null) {
        $options = AssocArray::newArray(['text' => $text], $options);
        return new self($options);
    }
    
    /**
     * Create multiple toasts from messages array
     * 
     * @param mixed $doc Document context
     * @param array $messages Array of message objects with 'text', 'type', 't' keys
     * @param array|null $defaultOptions Default options for all toasts
     * @return string HTML output
     */
    public static function fromMessages($doc, $messages, $defaultOptions = null) {
        if (!is_array($messages) || empty($messages)) {
            return '';
        }
        
        $delay = 500; // Başlangıç delay'i
        $output = '';
        
        foreach ($messages as $msg) {
            $message = $msg['text'] ?? '';
            $type = $msg['type'] ?? 'info';
            $timestamp = $msg['t'] ?? '';
            
            // Default options'ı delay ile birleştir
            $options = $defaultOptions ?? [];
            $options['delay'] = $delay;
            
            // Mesaj tipine göre toast oluştur
            switch ($type) {
                case 'success':
                    $output .= self::newSuccess($message, "Başarı", $options)->setPositionTopRight()->toHtml($doc);
                    break;
                case 'error':
                    $output .= self::newError($message, "Hata", $options)->setPositionTopRight()->toHtml($doc);
                    break;
                case 'warning':
                    $output .= self::newWarning($message, "Uyarı", $options)->setPositionTopRight()->toHtml($doc);
                    break;
                case 'info':
                default:
                    $output .= self::newInfo($message, "Bilgi", $options)->setPositionTopRight()->toHtml($doc);
                    break;
            }
            
            $delay += 500; // Her toast için 500ms artır
        }
        
        return $output;
    }
}
