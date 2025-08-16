<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Accordion Component
 * 
 * Creates a Bootstrap accordion with multiple collapsible items.
 * Supports custom styling, multiple items, and Bootstrap 5 classes.
 */
class Accordion extends HtmlComponent
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
        
        // Ensure accordion ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'accordion_' . uniqid();
        }
    }

    /**
     * Get default options for the accordion
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'items' => [],
            'flush' => false,
            'alwaysOpen' => false,
            'multiple' => false,
            'class' => '',
            'style' => '',
            'firstOpen' => false
        ];
    }

    /**
     * Render the accordion as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $items = $this->options['items'];
        $flush = $this->options['flush'];
        $alwaysOpen = $this->options['alwaysOpen'];
        $multiple = $this->options['multiple'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $firstOpen = $this->options['firstOpen'];

        // Build accordion classes
        $accordionClass = 'accordion';
        if ($flush) {
            $accordionClass .= ' accordion-flush';
        }
        if (!empty($class)) {
            $accordionClass .= ' ' . $class;
        }

        // Build accordion attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        if ($alwaysOpen) {
            $attributes .= ' data-bs-parent=""';
        }
        if ($multiple) {
            $attributes .= ' data-bs-multiple="true"';
        }

        $html = '<div class="' . $accordionClass . '" ' . $attributes;
        if (!empty($style)) {
            $html .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        $html .= '>';

        foreach ($items as $index => $item) {
            $itemId = isset($item['id']) ? $item['id'] : $id . '_item_' . $index;
            $header = isset($item['header']) ? $item['header'] : 'Item ' . ($index + 1);
            $content = isset($item['content']) ? $item['content'] : '';
            $collapsed = isset($item['collapsed']) ? $item['collapsed'] : ($firstOpen && $index === 0 ? false : true);
            $itemClass = isset($item['class']) ? $item['class'] : '';
            $itemStyle = isset($item['style']) ? $item['style'] : '';

            $html .= '<div class="accordion-item';
            if (!empty($itemClass)) {
                $html .= ' ' . htmlspecialchars($itemClass, ENT_QUOTES, 'UTF-8');
            }
            $html .= '"';
            if (!empty($itemStyle)) {
                $html .= ' style="' . htmlspecialchars($itemStyle, ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= '>';

            // Header
            $html .= '<h2 class="accordion-header" id="heading_' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '">';
            $html .= '<button class="accordion-button' . ($collapsed ? ' collapsed' : '') . '" type="button" ';
            $html .= 'data-bs-toggle="collapse" data-bs-target="#collapse_' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'aria-expanded="' . ($collapsed ? 'false' : 'true') . '" ';
            $html .= 'aria-controls="collapse_' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '">';
            $html .= htmlspecialchars($header, ENT_QUOTES, 'UTF-8');
            $html .= '</button>';
            $html .= '</h2>';

            // Content
            $html .= '<div id="collapse_' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'class="accordion-collapse collapse' . ($collapsed ? '' : ' show') . '" ';
            $html .= 'aria-labelledby="heading_' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '" ';
            if (!$alwaysOpen) {
                $html .= 'data-bs-parent="#' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= '>';
            $html .= '<div class="accordion-body">';
            $html .= $content;
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
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
     * Create a simple accordion with title and content
     * 
     * @param string $title Accordion title
     * @param string $content Accordion content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($title, $content, $options = null)
    {
        $defaultOptions = [
            'items' => [
                [
                    'header' => $title,
                    'content' => $content,
                    'collapsed' => false
                ]
            ]
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an accordion with multiple items
     * 
     * @param array $items Array of items with 'header' and 'content' keys
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function multiple($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a flush accordion (no borders)
     * 
     * @param array $items Array of items
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
     * Create an accordion where multiple items can be open at once
     * 
     * @param array $items Array of items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function multipleOpen($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'multiple' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an accordion with the first item open by default
     * 
     * @param array $items Array of items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function firstOpen($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'firstOpen' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>