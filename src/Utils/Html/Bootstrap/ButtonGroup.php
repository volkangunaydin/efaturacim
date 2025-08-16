<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Button Group Component
 * 
 * Creates a Bootstrap button group with multiple buttons.
 * Supports vertical layout, sizing, and Bootstrap 5 classes.
 */
class ButtonGroup extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure buttons is always an array
        if (!isset($this->options['buttons']) || !is_array($this->options['buttons'])) {
            $this->options['buttons'] = [];
        }
        
        // Ensure button group ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'btn-group_' . uniqid();
        }
    }

    /**
     * Get default options for the button group
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'buttons' => [],
            'vertical' => false,
            'size' => '',
            'class' => '',
            'style' => '',
            'role' => 'group',
            'ariaLabel' => 'Button group',
            'justified' => false,
            'wrapped' => false
        ];
    }

    /**
     * Render the button group as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $buttons = $this->options['buttons'];
        $vertical = $this->options['vertical'];
        $size = $this->options['size'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $role = $this->options['role'];
        $ariaLabel = $this->options['ariaLabel'];
        $justified = $this->options['justified'];
        $wrapped = $this->options['wrapped'];

        // Build button group classes
        $buttonGroupClass = 'btn-group';
        
        if ($vertical) {
            $buttonGroupClass = 'btn-group-vertical';
        }
        
        if ($wrapped) {
            $buttonGroupClass .= ' flex-wrap';
        }
        
        // Add size class
        if (!empty($size)) {
            $buttonGroupClass .= ' btn-group-' . $size;
        }
        
        // Add custom classes
        if (!empty($class)) {
            $buttonGroupClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $buttonGroupClass . '"';
        $attributes .= ' role="' . htmlspecialchars($role, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '';
        
        // Handle justified button groups
        if ($justified) {
            $html .= '<div class="btn-group w-100" role="group" aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8') . '">';
        } else {
            $html .= '<div ' . $attributes . '>';
        }

        // Add buttons
        foreach ($buttons as $index => $button) {
            // If button is already HTML string, use it directly
            if (is_string($button)) {
                $html .= $button;
            } 
            // If button is an array, create a Button component
            elseif (is_array($button)) {
                $buttonComponent = new Button($button);
                $html .= $buttonComponent->toHtmlAsString();
            }
            // If button is a Button component instance
            elseif ($button instanceof Button) {
                $html .= $button->toHtmlAsString();
            }
        }

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
     * Create a simple button group with basic buttons
     * 
     * @param array $buttonTexts Array of button texts
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($buttonTexts, $options = null)
    {
        $buttons = [];
        foreach ($buttonTexts as $text) {
            $buttons[] = [
                'text' => $text,
                'variant' => 'outline-secondary'
            ];
        }
        
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button group with primary buttons
     * 
     * @param array $buttonTexts Array of button texts
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($buttonTexts, $options = null)
    {
        $buttons = [];
        foreach ($buttonTexts as $text) {
            $buttons[] = [
                'text' => $text,
                'variant' => 'primary'
            ];
        }
        
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a vertical button group
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function vertical($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons,
            'vertical' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a large button group
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons,
            'size' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a small button group
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons,
            'size' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a justified button group
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function justified($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons,
            'justified' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a wrapped button group
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function wrapped($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons,
            'wrapped' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a toolbar with multiple button groups
     * 
     * @param array $buttonGroups Array of button group configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function toolbar($buttonGroups, $options = null)
    {
        $html = '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">';
        
        foreach ($buttonGroups as $group) {
            if (is_array($group)) {
                $buttonGroup = new self($group);
                $html .= $buttonGroup->toHtmlAsString();
            } elseif (is_string($group)) {
                $html .= $group;
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Create a button group with mixed variants
     * 
     * @param array $buttonConfigs Array of button configurations with text and variant
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function mixed($buttonConfigs, $options = null)
    {
        $buttons = [];
        foreach ($buttonConfigs as $config) {
            if (is_array($config)) {
                $buttons[] = $config;
            } elseif (is_string($config)) {
                $buttons[] = ['text' => $config, 'variant' => 'outline-secondary'];
            }
        }
        
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a button group with icons
     * 
     * @param array $buttonConfigs Array of button configurations with text, icon, and optional variant
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withIcons($buttonConfigs, $options = null)
    {
        $buttons = [];
        foreach ($buttonConfigs as $config) {
            if (is_array($config)) {
                $buttons[] = $config;
            }
        }
        
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a segmented button group (radio-style)
     * 
     * @param array $buttonConfigs Array of button configurations
     * @param string $name Name for the radio group
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function segmented($buttonConfigs, $name, $options = null)
    {
        $buttons = [];
        foreach ($buttonConfigs as $index => $config) {
            if (is_array($config)) {
                $config['type'] = 'radio';
                $config['name'] = $name;
                $config['id'] = $name . '_' . $index;
                $config['autocomplete'] = 'off';
                $buttons[] = $config;
            }
        }
        
        $defaultOptions = [
            'buttons' => $buttons,
            'role' => 'group'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a nested button group
     * 
     * @param array $mainButtons Array of main button configurations
     * @param array $dropdownButtons Array of dropdown button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDropdown($mainButtons, $dropdownButtons, $options = null)
    {
        $buttons = $mainButtons;
        
        // Add dropdown button
        $dropdownHtml = '<div class="btn-group" role="group">';
        $dropdownHtml .= '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
        $dropdownHtml .= 'Dropdown';
        $dropdownHtml .= '</button>';
        $dropdownHtml .= '<ul class="dropdown-menu">';
        
        foreach ($dropdownButtons as $button) {
            if (is_array($button)) {
                $text = isset($button['text']) ? $button['text'] : 'Item';
                $href = isset($button['href']) ? $button['href'] : '#';
                $dropdownHtml .= '<li><a class="dropdown-item" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a></li>';
            }
        }
        
        $dropdownHtml .= '</ul>';
        $dropdownHtml .= '</div>';
        
        $buttons[] = $dropdownHtml;
        
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
