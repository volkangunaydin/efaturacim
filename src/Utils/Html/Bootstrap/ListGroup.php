<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap List Group Component
 * 
 * Creates a Bootstrap list group with various item types and styling options.
 * Supports links, buttons, badges, and Bootstrap 5 classes.
 */
class ListGroup extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure items is always an array
        if (!isset($this->options['items']) || !is_array($this->options['items'])) {
            $this->options['items'] = [];
        }
        
        // Ensure list group ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'listgroup_' . uniqid();
        }
    }

    /**
     * Get default options for the list group
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'items' => [],
            'flush' => false,
            'numbered' => false,
            'horizontal' => false,
            'horizontalBreakpoint' => '', // sm, md, lg, xl, xxl
            'class' => '',
            'style' => '',
            'itemClass' => '',
            'itemStyle' => '',
            'active' => null, // index of active item
            'disabled' => [], // array of disabled item indices
            'data' => []
        ];
    }

    /**
     * Render the list group as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $items = $this->options['items'];
        $flush = $this->options['flush'];
        $numbered = $this->options['numbered'];
        $horizontal = $this->options['horizontal'];
        $horizontalBreakpoint = $this->options['horizontalBreakpoint'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $itemClass = $this->options['itemClass'];
        $itemStyle = $this->options['itemStyle'];
        $active = $this->options['active'];
        $disabled = $this->options['disabled'];
        $data = $this->options['data'];

        // Build list group classes
        $listGroupClass = 'list-group';
        
        if ($flush) {
            $listGroupClass .= ' list-group-flush';
        }
        
        if ($numbered) {
            $listGroupClass .= ' list-group-numbered';
        }
        
        if ($horizontal) {
            $listGroupClass .= ' list-group-horizontal';
            if (!empty($horizontalBreakpoint)) {
                $listGroupClass .= '-' . $horizontalBreakpoint;
            }
        }
        
        // Add custom classes
        if (!empty($class)) {
            $listGroupClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $listGroupClass . '"';
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<' . ($numbered ? 'ol' : 'ul') . ' ' . $attributes . '>';
        
        foreach ($items as $index => $item) {
            $isActive = ($active !== null && $active === $index);
            $isDisabled = in_array($index, $disabled);
            $html .= $this->buildListItem($item, $index, $isActive, $isDisabled, $itemClass, $itemStyle);
        }
        
        $html .= '</' . ($numbered ? 'ol' : 'ul') . '>';

        return $html;
    }

    /**
     * Build individual list item
     * 
     * @param array|string $item
     * @param int $index
     * @param bool $isActive
     * @param bool $isDisabled
     * @param string $itemClass
     * @param string $itemStyle
     * @return string
     */
    private function buildListItem($item, $index, $isActive, $isDisabled, $itemClass, $itemStyle)
    {
        // If item is already HTML string, wrap it
        if (is_string($item)) {
            $classes = 'list-group-item';
            if ($isActive) $classes .= ' active';
            if ($isDisabled) $classes .= ' disabled';
            if (!empty($itemClass)) $classes .= ' ' . $itemClass;
            
            $attributes = 'class="' . $classes . '"';
            if (!empty($itemStyle)) {
                $attributes .= ' style="' . htmlspecialchars($itemStyle, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            return '<li ' . $attributes . '>' . $item . '</li>';
        }
        
        // If item is an array, build from configuration
        if (is_array($item)) {
            $type = isset($item['type']) ? $item['type'] : 'li'; // li, link, button
            $text = isset($item['text']) ? $item['text'] : '';
            $href = isset($item['href']) ? $item['href'] : '#';
            $icon = isset($item['icon']) ? $item['icon'] : '';
            $badge = isset($item['badge']) ? $item['badge'] : '';
            $badgeVariant = isset($item['badgeVariant']) ? $item['badgeVariant'] : 'secondary';
            $class = isset($item['class']) ? $item['class'] : '';
            $style = isset($item['style']) ? $item['style'] : '';
            $onclick = isset($item['onclick']) ? $item['onclick'] : '';
            $data = isset($item['data']) ? $item['data'] : [];
            $active = isset($item['active']) ? $item['active'] : $isActive;
            $disabled = isset($item['disabled']) ? $item['disabled'] : $isDisabled;
            
            // Build item classes
            $itemClasses = 'list-group-item';
            if ($active) $itemClasses .= ' active';
            if ($disabled) $itemClasses .= ' disabled';
            if (!empty($class)) $itemClasses .= ' ' . $class;
            if (!empty($itemClass)) $itemClasses .= ' ' . $itemClass;
            
            // Build item attributes
            $itemAttributes = 'class="' . $itemClasses . '"';
            
            if (!empty($style)) {
                $itemAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
            }
            if (!empty($itemStyle)) {
                $itemAttributes .= ' style="' . htmlspecialchars($itemStyle, ENT_QUOTES, 'UTF-8') . '"';
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
            
            // Add badge if specified
            if (!empty($badge)) {
                $badgeClass = 'badge text-bg-' . $badgeVariant . ' rounded-pill';
                $itemContent .= ' <span class="' . $badgeClass . '">' . htmlspecialchars($badge, ENT_QUOTES, 'UTF-8') . '</span>';
            }
            
            // Build item element based on type
            if ($type === 'link') {
                $itemAttributes .= ' href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
                return '<a ' . $itemAttributes . '>' . $itemContent . '</a>';
            } elseif ($type === 'button') {
                $itemAttributes .= ' type="button"';
                return '<button ' . $itemAttributes . '>' . $itemContent . '</button>';
            } else {
                return '<li ' . $itemAttributes . '>' . $itemContent . '</li>';
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
     * Create a simple list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a flush list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function flush($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'flush' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a numbered list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function numbered($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'numbered' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a horizontal list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontal($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'horizontal' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a horizontal list group with breakpoint
     * 
     * @param array $items Array of list items
     * @param string $breakpoint Breakpoint (sm, md, lg, xl, xxl)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalWithBreakpoint($items, $breakpoint, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'horizontal' => true,
            'horizontalBreakpoint' => $breakpoint
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with links
     * 
     * @param array $items Array of link items with text and href
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withLinks($items, $options = null)
    {
        // Convert items to link format
        $linkItems = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $linkItems[] = array_merge($item, ['type' => 'link']);
            } else {
                $linkItems[] = ['text' => $item, 'type' => 'link', 'href' => '#'];
            }
        }
        
        $defaultOptions = [
            'items' => $linkItems
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with buttons
     * 
     * @param array $items Array of button items with text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtons($items, $options = null)
    {
        // Convert items to button format
        $buttonItems = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $buttonItems[] = array_merge($item, ['type' => 'button']);
            } else {
                $buttonItems[] = ['text' => $item, 'type' => 'button'];
            }
        }
        
        $defaultOptions = [
            'items' => $buttonItems
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with badges
     * 
     * @param array $items Array of items with text and badge
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withBadges($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with icons
     * 
     * @param array $items Array of items with text and icon
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withIcons($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with active item
     * 
     * @param array $items Array of list items
     * @param int $activeIndex Index of active item
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withActiveItem($items, $activeIndex, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'active' => $activeIndex
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with disabled items
     * 
     * @param array $items Array of list items
     * @param array $disabledIndices Array of disabled item indices
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDisabledItems($items, $disabledIndices, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'disabled' => $disabledIndices
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a horizontal small list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalSmall($items, $options = null)
    {
        return self::horizontalWithBreakpoint($items, 'sm', $options);
    }

    /**
     * Create a horizontal medium list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalMedium($items, $options = null)
    {
        return self::horizontalWithBreakpoint($items, 'md', $options);
    }

    /**
     * Create a horizontal large list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalLarge($items, $options = null)
    {
        return self::horizontalWithBreakpoint($items, 'lg', $options);
    }

    /**
     * Create a horizontal extra large list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalXl($items, $options = null)
    {
        return self::horizontalWithBreakpoint($items, 'xl', $options);
    }

    /**
     * Create a horizontal extra extra large list group
     * 
     * @param array $items Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontalXxl($items, $options = null)
    {
        return self::horizontalWithBreakpoint($items, 'xxl', $options);
    }

    /**
     * Create a list group from simple text array
     * 
     * @param array $texts Array of text strings
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fromTexts($texts, $options = null)
    {
        $items = [];
        foreach ($texts as $text) {
            $items[] = ['text' => $text];
        }
        
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group from simple text array with links
     * 
     * @param array $texts Array of text strings
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fromTextsWithLinks($texts, $options = null)
    {
        $items = [];
        foreach ($texts as $text) {
            $items[] = ['text' => $text, 'type' => 'link', 'href' => '#'];
        }
        
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group from simple text array with buttons
     * 
     * @param array $texts Array of text strings
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fromTextsWithButtons($texts, $options = null)
    {
        $items = [];
        foreach ($texts as $text) {
            $items[] = ['text' => $text, 'type' => 'button'];
        }
        
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a list group with custom HTML items
     * 
     * @param array $htmlItems Array of HTML strings
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHtmlItems($htmlItems, $options = null)
    {
        $defaultOptions = [
            'items' => $htmlItems
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
