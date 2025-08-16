<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Breadcrumb Component
 * 
 * Creates a Bootstrap breadcrumb navigation component.
 * Supports custom styling, multiple items, and Bootstrap 5 classes.
 */
class Breadcrumb extends HtmlComponent
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
        
        // Ensure breadcrumb ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'breadcrumb_' . uniqid();
        }
    }

    /**
     * Get default options for the breadcrumb
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'items' => [],
            'divider' => '/',
            'ariaLabel' => 'breadcrumb',
            'class' => '',
            'style' => '',
            'homeIcon' => '',
            'homeText' => 'Home',
            'homeUrl' => '#'
        ];
    }

    /**
     * Render the breadcrumb as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $items = $this->options['items'];
        $divider = $this->options['divider'];
        $ariaLabel = $this->options['ariaLabel'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $homeIcon = $this->options['homeIcon'];
        $homeText = $this->options['homeText'];
        $homeUrl = $this->options['homeUrl'];

        // Build breadcrumb classes
        $breadcrumbClass = 'breadcrumb';
        if (!empty($class)) {
            $breadcrumbClass .= ' ' . $class;
        }

        // Build breadcrumb attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8') . '"';

        $html = '<nav ' . $attributes;
        if (!empty($style)) {
            $html .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        $html .= '>';
        $html .= '<ol class="' . $breadcrumbClass . '">';

        // Add home item if specified
        if (!empty($homeText)) {
            $html .= '<li class="breadcrumb-item">';
            if (!empty($homeUrl) && $homeUrl !== '#') {
                $html .= '<a href="' . htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') . '">';
            }
            if (!empty($homeIcon)) {
                $html .= '<i class="' . htmlspecialchars($homeIcon, ENT_QUOTES, 'UTF-8') . '"></i> ';
            }
            $html .= htmlspecialchars($homeText, ENT_QUOTES, 'UTF-8');
            if (!empty($homeUrl) && $homeUrl !== '#') {
                $html .= '</a>';
            }
            $html .= '</li>';
        }

        // Add breadcrumb items
        foreach ($items as $index => $item) {
            $text = isset($item['text']) ? $item['text'] : 'Item ' . ($index + 1);
            $url = isset($item['url']) ? $item['url'] : '';
            $active = isset($item['active']) ? $item['active'] : ($index === count($items) - 1);
            $icon = isset($item['icon']) ? $item['icon'] : '';
            $itemClass = isset($item['class']) ? $item['class'] : '';
            $itemStyle = isset($item['style']) ? $item['style'] : '';

            $html .= '<li class="breadcrumb-item';
            if ($active) {
                $html .= ' active';
            }
            if (!empty($itemClass)) {
                $html .= ' ' . htmlspecialchars($itemClass, ENT_QUOTES, 'UTF-8');
            }
            $html .= '"';
            if (!empty($itemStyle)) {
                $html .= ' style="' . htmlspecialchars($itemStyle, ENT_QUOTES, 'UTF-8') . '"';
            }
            if ($active) {
                $html .= ' aria-current="page"';
            }
            $html .= '>';

            if (!$active && !empty($url)) {
                $html .= '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
            }

            if (!empty($icon)) {
                $html .= '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i> ';
            }
            $html .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

            if (!$active && !empty($url)) {
                $html .= '</a>';
            }

            $html .= '</li>';
        }

        $html .= '</ol>';
        $html .= '</nav>';

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
     * Create a simple breadcrumb with title
     * 
     * @param string $title Breadcrumb title
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($title, $options = null)
    {
        $defaultOptions = [
            'items' => [
                [
                    'text' => $title,
                    'active' => true
                ]
            ]
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a breadcrumb with multiple items
     * 
     * @param array $items Array of items with 'text' and optional 'url' keys
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
     * Create a breadcrumb with home icon
     * 
     * @param array $items Array of items
     * @param string $homeIcon Icon class for home
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHomeIcon($items, $homeIcon, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'homeIcon' => $homeIcon
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a breadcrumb with custom divider
     * 
     * @param array $items Array of items
     * @param string $divider Custom divider character
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDivider($items, $divider, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'divider' => $divider
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a breadcrumb without home item
     * 
     * @param array $items Array of items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutHome($items, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'homeText' => ''
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a breadcrumb with custom home URL
     * 
     * @param array $items Array of items
     * @param string $homeUrl Custom home URL
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHomeUrl($items, $homeUrl, $options = null)
    {
        $defaultOptions = [
            'items' => $items,
            'homeUrl' => $homeUrl
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a breadcrumb from URL path
     * 
     * @param string $path URL path (e.g., '/admin/users/edit')
     * @param string $baseUrl Base URL for links
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fromPath($path, $baseUrl = '', $options = null)
    {
        $pathParts = array_filter(explode('/', trim($path, '/')));
        $items = [];
        $currentPath = '';

        foreach ($pathParts as $index => $part) {
            $currentPath .= '/' . $part;
            $items[] = [
                'text' => ucfirst(str_replace(['-', '_'], ' ', $part)),
                'url' => $baseUrl . $currentPath,
                'active' => ($index === count($pathParts) - 1)
            ];
        }

        $defaultOptions = [
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
