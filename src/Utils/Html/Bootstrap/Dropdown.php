<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Dropdown Component
 * 
 * Creates a Bootstrap dropdown with trigger button and menu items.
 * Supports various trigger types, menu positioning, and Bootstrap 5 classes.
 */
class Dropdown extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure dropdown ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'dropdown_' . uniqid();
        }
        
        // Ensure items is always an array
        if (!isset($this->options['items']) || !is_array($this->options['items'])) {
            $this->options['items'] = [];
        }
    }

    /**
     * Get default options for the dropdown
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'trigger' => '',
            'triggerText' => 'Dropdown',
            'triggerVariant' => 'primary',
            'triggerSize' => '',
            'triggerIcon' => '',
            'triggerIconEnd' => 'bi-chevron-down',
            'triggerType' => 'button', // button, link, div
            'items' => [],
            'direction' => 'down', // down, up, start, end
            'autoClose' => true, // true, false, 'inside', 'outside'
            'dark' => false,
            'class' => '',
            'style' => '',
            'triggerClass' => '',
            'triggerStyle' => '',
            'menuClass' => '',
            'menuStyle' => '',
            'split' => false,
            'dropup' => false,
            'dropstart' => false,
            'dropend' => false,
            'centered' => false,
            'rightAligned' => false,
            'data' => []
        ];
    }

    /**
     * Render the dropdown as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $trigger = $this->options['trigger'];
        $triggerText = $this->options['triggerText'];
        $triggerVariant = $this->options['triggerVariant'];
        $triggerSize = $this->options['triggerSize'];
        $triggerIcon = $this->options['triggerIcon'];
        $triggerIconEnd = $this->options['triggerIconEnd'];
        $triggerType = $this->options['triggerType'];
        $items = $this->options['items'];
        $direction = $this->options['direction'];
        $autoClose = $this->options['autoClose'];
        $dark = $this->options['dark'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $triggerClass = $this->options['triggerClass'];
        $triggerStyle = $this->options['triggerStyle'];
        $menuClass = $this->options['menuClass'];
        $menuStyle = $this->options['menuStyle'];
        $split = $this->options['split'];
        $dropup = $this->options['dropup'];
        $dropstart = $this->options['dropstart'];
        $dropend = $this->options['dropend'];
        $centered = $this->options['centered'];
        $rightAligned = $this->options['rightAligned'];
        $data = $this->options['data'];

        // Build dropdown classes
        $dropdownClass = 'dropdown';
        
        if ($dropup) {
            $dropdownClass = 'dropup';
        } elseif ($dropstart) {
            $dropdownClass = 'dropstart';
        } elseif ($dropend) {
            $dropdownClass = 'dropend';
        }
        
        if ($centered) {
            $dropdownClass .= ' dropdown-center';
        }
        
        if ($rightAligned) {
            $dropdownClass .= ' dropend';
        }
        
        if ($dark) {
            $dropdownClass .= ' dropdown-menu-dark';
        }
        
        // Add custom classes
        if (!empty($class)) {
            $dropdownClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $dropdownClass . '"';
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $attributes . '>';
        
        // Build trigger
        if (!empty($trigger)) {
            $html .= $trigger;
        } else {
            $html .= $this->buildTrigger($id, $triggerText, $triggerVariant, $triggerSize, $triggerIcon, $triggerIconEnd, $triggerType, $triggerClass, $triggerStyle, $split);
        }
        
        // Build menu
        $html .= $this->buildMenu($id, $items, $autoClose, $menuClass, $menuStyle, $dark);
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Build trigger element
     * 
     * @param string $dropdownId
     * @param string $triggerText
     * @param string $triggerVariant
     * @param string $triggerSize
     * @param string $triggerIcon
     * @param string $triggerIconEnd
     * @param string $triggerType
     * @param string $triggerClass
     * @param string $triggerStyle
     * @param bool $split
     * @return string
     */
    private function buildTrigger($dropdownId, $triggerText, $triggerVariant, $triggerSize, $triggerIcon, $triggerIconEnd, $triggerType, $triggerClass, $triggerStyle, $split)
    {
        if ($split) {
            return $this->buildSplitTrigger($dropdownId, $triggerText, $triggerVariant, $triggerSize, $triggerIcon, $triggerClass, $triggerStyle);
        }

        // Build trigger classes
        $classes = '';
        if ($triggerType === 'button') {
            $classes = 'btn btn-' . $triggerVariant . ' dropdown-toggle';
            if (!empty($triggerSize)) {
                $classes .= ' btn-' . $triggerSize;
            }
        }
        if (!empty($triggerClass)) {
            $classes .= ' ' . $triggerClass;
        }

        // Build attributes
        $attributes = 'type="button"';
        $attributes .= ' data-bs-toggle="dropdown"';
        $attributes .= ' aria-expanded="false"';
        
        if (!empty($classes)) {
            $attributes .= ' class="' . $classes . '"';
        }
        
        if (!empty($triggerStyle)) {
            $attributes .= ' style="' . htmlspecialchars($triggerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build trigger content
        $triggerContent = '';
        if (!empty($triggerIcon)) {
            $triggerContent .= '<i class="' . htmlspecialchars($triggerIcon, ENT_QUOTES, 'UTF-8') . '"></i> ';
        }
        $triggerContent .= htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8');
        if (!empty($triggerIconEnd)) {
            $triggerContent .= ' <i class="' . htmlspecialchars($triggerIconEnd, ENT_QUOTES, 'UTF-8') . '"></i>';
        }

        // Build trigger element
        if ($triggerType === 'link') {
            return '<a href="#" ' . $attributes . '>' . $triggerContent . '</a>';
        } elseif ($triggerType === 'div') {
            return '<div ' . $attributes . '>' . $triggerContent . '</div>';
        } else {
            return '<button ' . $attributes . '>' . $triggerContent . '</button>';
        }
    }

    /**
     * Build split trigger (button + dropdown toggle)
     * 
     * @param string $dropdownId
     * @param string $triggerText
     * @param string $triggerVariant
     * @param string $triggerSize
     * @param string $triggerIcon
     * @param string $triggerClass
     * @param string $triggerStyle
     * @return string
     */
    private function buildSplitTrigger($dropdownId, $triggerText, $triggerVariant, $triggerSize, $triggerIcon, $triggerClass, $triggerStyle)
    {
        // Main button
        $mainButtonClasses = 'btn btn-' . $triggerVariant;
        if (!empty($triggerSize)) {
            $mainButtonClasses .= ' btn-' . $triggerSize;
        }
        if (!empty($triggerClass)) {
            $mainButtonClasses .= ' ' . $triggerClass;
        }

        $mainButtonContent = '';
        if (!empty($triggerIcon)) {
            $mainButtonContent .= '<i class="' . htmlspecialchars($triggerIcon, ENT_QUOTES, 'UTF-8') . '"></i> ';
        }
        $mainButtonContent .= htmlspecialchars($triggerText, ENT_QUOTES, 'UTF-8');

        $mainButtonAttributes = 'type="button"';
        $mainButtonAttributes .= ' class="' . $mainButtonClasses . '"';
        if (!empty($triggerStyle)) {
            $mainButtonAttributes .= ' style="' . htmlspecialchars($triggerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Dropdown toggle button
        $toggleClasses = 'btn btn-' . $triggerVariant . ' dropdown-toggle dropdown-toggle-split';
        if (!empty($triggerSize)) {
            $toggleClasses .= ' btn-' . $triggerSize;
        }

        $toggleAttributes = 'type="button"';
        $toggleAttributes .= ' data-bs-toggle="dropdown"';
        $toggleAttributes .= ' aria-expanded="false"';
        $toggleAttributes .= ' class="' . $toggleClasses . '"';

        $html = '<button ' . $mainButtonAttributes . '>' . $mainButtonContent . '</button>';
        $html .= '<button ' . $toggleAttributes . '>';
        $html .= '<span class="visually-hidden">Toggle Dropdown</span>';
        $html .= '</button>';

        return $html;
    }

    /**
     * Build dropdown menu
     * 
     * @param string $dropdownId
     * @param array $items
     * @param mixed $autoClose
     * @param string $menuClass
     * @param string $menuStyle
     * @param bool $dark
     * @return string
     */
    private function buildMenu($dropdownId, $items, $autoClose, $menuClass, $menuStyle, $dark)
    {
        // Build menu classes
        $menuClasses = 'dropdown-menu';
        if ($dark) {
            $menuClasses .= ' dropdown-menu-dark';
        }
        if (!empty($menuClass)) {
            $menuClasses .= ' ' . $menuClass;
        }

        // Build menu attributes
        $menuAttributes = 'class="' . $menuClasses . '"';
        
        if ($autoClose !== true) {
            if ($autoClose === false) {
                $menuAttributes .= ' data-bs-auto-close="false"';
            } elseif ($autoClose === 'inside') {
                $menuAttributes .= ' data-bs-auto-close="inside"';
            } elseif ($autoClose === 'outside') {
                $menuAttributes .= ' data-bs-auto-close="outside"';
            }
        }
        
        if (!empty($menuStyle)) {
            $menuAttributes .= ' style="' . htmlspecialchars($menuStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<ul ' . $menuAttributes . '>';
        
        foreach ($items as $item) {
            $html .= $this->buildMenuItem($item);
        }
        
        $html .= '</ul>';

        return $html;
    }

    /**
     * Build individual menu item
     * 
     * @param array|string $item
     * @return string
     */
    private function buildMenuItem($item)
    {
        // If item is already HTML string, return as is
        if (is_string($item)) {
            return $item;
        }
        
        // If item is an array, build from configuration
        if (is_array($item)) {
            $type = isset($item['type']) ? $item['type'] : 'link';
            $text = isset($item['text']) ? $item['text'] : '';
            $href = isset($item['href']) ? $item['href'] : '#';
            $icon = isset($item['icon']) ? $item['icon'] : '';
            $class = isset($item['class']) ? $item['class'] : '';
            $style = isset($item['style']) ? $item['style'] : '';
            $disabled = isset($item['disabled']) ? $item['disabled'] : false;
            $active = isset($item['active']) ? $item['active'] : false;
            $divider = isset($item['divider']) ? $item['divider'] : false;
            $header = isset($item['header']) ? $item['header'] : false;
            $onclick = isset($item['onclick']) ? $item['onclick'] : '';
            $data = isset($item['data']) ? $item['data'] : [];
            
            // Handle special item types
            if ($divider) {
                return '<li><hr class="dropdown-divider"></li>';
            }
            
            if ($header) {
                $headerClass = 'dropdown-header';
                if (!empty($class)) {
                    $headerClass .= ' ' . $class;
                }
                
                $headerAttributes = 'class="' . $headerClass . '"';
                if (!empty($style)) {
                    $headerAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
                }
                
                return '<li><h6 ' . $headerAttributes . '>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</h6></li>';
            }
            
            // Build item classes
            $itemClass = 'dropdown-item';
            if ($disabled) {
                $itemClass .= ' disabled';
            }
            if ($active) {
                $itemClass .= ' active';
            }
            if (!empty($class)) {
                $itemClass .= ' ' . $class;
            }
            
            // Build item attributes
            $itemAttributes = 'class="' . $itemClass . '"';
            
            if ($type === 'link') {
                $itemAttributes .= ' href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($style)) {
                $itemAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($onclick)) {
                $itemAttributes .= ' onclick="' . htmlspecialchars($onclick, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if ($disabled) {
                $itemAttributes .= ' aria-disabled="true"';
            }
            
            if ($active) {
                $itemAttributes .= ' aria-current="true"';
            }
            
            // Add data attributes
            foreach ($data as $key => $value) {
                $itemAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            // Build item content
            $itemContent = '';
            if (!empty($icon)) {
                $itemContent .= '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i> ';
            }
            $itemContent .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            
            // Build item element
            if ($type === 'button') {
                return '<li><button type="button" ' . $itemAttributes . '>' . $itemContent . '</button></li>';
            } else {
                return '<li><a ' . $itemAttributes . '>' . $itemContent . '</a></li>';
            }
        }
        
        return '';
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
     * Create a simple dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with custom trigger
     * 
     * @param string $trigger Custom trigger HTML
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomTrigger($trigger, $items, $options = null)
    {
        $defaultOptions = [
            'trigger' => $trigger,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a split dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function split($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'split' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropup
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dropup($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'dropup' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropstart (left dropdown)
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dropstart($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'dropstart' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropend (right dropdown)
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dropend($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'dropend' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a centered dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function centered($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'centered' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a right-aligned dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function rightAligned($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'rightAligned' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'dark' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with button variant
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param string $variant Button variant (primary, secondary, success, etc.)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtonVariant($triggerText, $items, $variant, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'triggerVariant' => $variant
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with button size
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param string $size Button size (sm, lg)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtonSize($triggerText, $items, $size, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'triggerSize' => $size
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with icon
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param string $icon Icon class (e.g., 'fas fa-cog')
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withIcon($triggerText, $items, $icon, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'triggerIcon' => $icon
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with link trigger
     * 
     * @param string $triggerText Trigger link text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withLinkTrigger($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown with div trigger
     * 
     * @param string $triggerText Trigger div text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDivTrigger($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'triggerType' => 'div'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dropdown that doesn't auto-close
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noAutoClose($triggerText, $items, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'items' => $items,
            'autoClose' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'primary', $options);
    }

    /**
     * Create a secondary button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'secondary', $options);
    }

    /**
     * Create a success button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'success', $options);
    }

    /**
     * Create a danger button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'danger', $options);
    }

    /**
     * Create a warning button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'warning', $options);
    }

    /**
     * Create an info button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'info', $options);
    }

    /**
     * Create a light button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'light', $options);
    }

    /**
     * Create a dark button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function darkButton($triggerText, $items, $options = null)
    {
        return self::withButtonVariant($triggerText, $items, 'dark', $options);
    }

    /**
     * Create a small button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($triggerText, $items, $options = null)
    {
        return self::withButtonSize($triggerText, $items, 'sm', $options);
    }

    /**
     * Create a large button dropdown
     * 
     * @param string $triggerText Trigger button text
     * @param array $items Array of menu items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($triggerText, $items, $options = null)
    {
        return self::withButtonSize($triggerText, $items, 'lg', $options);
    }
}
?>
