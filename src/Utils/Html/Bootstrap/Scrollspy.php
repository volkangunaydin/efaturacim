<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Scrollspy Component
 * 
 * Creates Bootstrap scrollspy with navigation and content areas.
 * Supports different configurations, offsets, and Bootstrap 5 classes.
 */
class Scrollspy extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure scrollspy ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'scrollspy_' . uniqid();
        }
        
        // Ensure navigation ID is set
        if (!isset($this->options['navId']) || empty($this->options['navId'])) {
            $this->options['navId'] = 'nav_' . uniqid();
        }
        
        // Ensure content ID is set
        if (!isset($this->options['contentId']) || empty($this->options['contentId'])) {
            $this->options['contentId'] = 'content_' . uniqid();
        }
        
        // Ensure navigation items are always set
        if (!isset($this->options['navItems'])) {
            $this->options['navItems'] = [];
        }
        
        // Ensure content sections are always set
        if (!isset($this->options['contentSections'])) {
            $this->options['contentSections'] = [];
        }
    }

    /**
     * Get default options for the scrollspy
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'navId' => '',
            'contentId' => '',
            'navItems' => [], // Array of navigation items
            'contentSections' => [], // Array of content sections
            'offset' => 10, // Offset in pixels
            'method' => 'auto', // auto, position
            'smooth' => true, // Enable smooth scrolling
            'navClass' => 'nav nav-pills flex-column',
            'navStyle' => '',
            'contentClass' => '',
            'contentStyle' => '',
            'containerClass' => 'row',
            'containerStyle' => '',
            'navContainerClass' => 'col-md-3',
            'navContainerStyle' => '',
            'contentContainerClass' => 'col-md-9',
            'contentContainerStyle' => '',
            'activeClass' => 'active',
            'data' => []
        ];
    }

    /**
     * Render the scrollspy as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $navId = $this->options['navId'];
        $contentId = $this->options['contentId'];
        $navItems = $this->options['navItems'];
        $contentSections = $this->options['contentSections'];
        $offset = $this->options['offset'];
        $method = $this->options['method'];
        $smooth = $this->options['smooth'];
        $navClass = $this->options['navClass'];
        $navStyle = $this->options['navStyle'];
        $contentClass = $this->options['contentClass'];
        $contentStyle = $this->options['contentStyle'];
        $containerClass = $this->options['containerClass'];
        $containerStyle = $this->options['containerStyle'];
        $navContainerClass = $this->options['navContainerClass'];
        $navContainerStyle = $this->options['navContainerStyle'];
        $contentContainerClass = $this->options['contentContainerClass'];
        $contentContainerStyle = $this->options['contentContainerStyle'];
        $activeClass = $this->options['activeClass'];
        $data = $this->options['data'];

        // Build main container
        $containerAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $containerAttributes .= ' class="' . $containerClass . '"';
        
        if (!empty($containerStyle)) {
            $containerAttributes .= ' style="' . htmlspecialchars($containerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $containerAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build navigation
        $navigation = $this->buildNavigation($navId, $navItems, $navClass, $navStyle, $navContainerClass, $navContainerStyle, $smooth);

        // Build content
        $content = $this->buildContent($contentId, $contentSections, $contentClass, $contentStyle, $contentContainerClass, $contentContainerStyle, $offset, $method, $activeClass);

        return '<div ' . $containerAttributes . '>' . $navigation . $content . '</div>';
    }

    /**
     * Build navigation section
     * 
     * @param string $navId
     * @param array $navItems
     * @param string $navClass
     * @param string $navStyle
     * @param string $navContainerClass
     * @param string $navContainerStyle
     * @param bool $smooth
     * @return string
     */
    private function buildNavigation($navId, $navItems, $navClass, $navStyle, $navContainerClass, $navContainerStyle, $smooth)
    {
        // Build nav container attributes
        $navContainerAttributes = 'class="' . $navContainerClass . '"';
        if (!empty($navContainerStyle)) {
            $navContainerAttributes .= ' style="' . htmlspecialchars($navContainerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build nav attributes
        $navAttributes = 'id="' . htmlspecialchars($navId, ENT_QUOTES, 'UTF-8') . '"';
        $navAttributes .= ' class="' . $navClass . '"';
        $navAttributes .= ' role="tablist"';
        
        if (!empty($navStyle)) {
            $navAttributes .= ' style="' . htmlspecialchars($navStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $navContainerAttributes . '>';
        $html .= '<nav ' . $navAttributes . '>';

        foreach ($navItems as $index => $item) {
            $itemId = isset($item['id']) ? $item['id'] : 'section_' . ($index + 1);
            $itemText = isset($item['text']) ? $item['text'] : 'Section ' . ($index + 1);
            $itemClass = isset($item['class']) ? $item['class'] : '';
            $itemStyle = isset($item['style']) ? $item['style'] : '';

            $html .= $this->buildNavItem($itemId, $itemText, $itemClass, $itemStyle, $smooth, $index === 0);
        }

        $html .= '</nav>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Build navigation item
     * 
     * @param string $itemId
     * @param string $itemText
     * @param string $itemClass
     * @param string $itemStyle
     * @param bool $smooth
     * @param bool $isActive
     * @return string
     */
    private function buildNavItem($itemId, $itemText, $itemClass, $itemStyle, $smooth, $isActive)
    {
        // Build item classes
        $classes = 'nav-link';
        if ($isActive) {
            $classes .= ' active';
        }
        if (!empty($itemClass)) {
            $classes .= ' ' . $itemClass;
        }

        // Build item attributes
        $attributes = 'class="' . $classes . '"';
        $attributes .= ' href="#' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' role="tab"';
        $attributes .= ' aria-controls="' . htmlspecialchars($itemId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' aria-selected="' . ($isActive ? 'true' : 'false') . '"';
        
        if ($smooth) {
            $attributes .= ' data-bs-smooth-scroll="true"';
        }
        
        if (!empty($itemStyle)) {
            $attributes .= ' style="' . htmlspecialchars($itemStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<a ' . $attributes . '>' . htmlspecialchars($itemText, ENT_QUOTES, 'UTF-8') . '</a>';
    }

    /**
     * Build content section
     * 
     * @param string $contentId
     * @param array $contentSections
     * @param string $contentClass
     * @param string $contentStyle
     * @param string $contentContainerClass
     * @param string $contentContainerStyle
     * @param int $offset
     * @param string $method
     * @param string $activeClass
     * @return string
     */
    private function buildContent($contentId, $contentSections, $contentClass, $contentStyle, $contentContainerClass, $contentContainerStyle, $offset, $method, $activeClass)
    {
        // Build content container attributes
        $contentContainerAttributes = 'class="' . $contentContainerClass . '"';
        if (!empty($contentContainerStyle)) {
            $contentContainerAttributes .= ' style="' . htmlspecialchars($contentContainerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build content attributes
        $contentAttributes = 'id="' . htmlspecialchars($contentId, ENT_QUOTES, 'UTF-8') . '"';
        $contentAttributes .= ' class="' . $contentClass . '"';
        $contentAttributes .= ' data-bs-spy="scroll"';
        $contentAttributes .= ' data-bs-target="#' . htmlspecialchars($contentId, ENT_QUOTES, 'UTF-8') . '"';
        $contentAttributes .= ' data-bs-offset="' . $offset . '"';
        $contentAttributes .= ' data-bs-method="' . htmlspecialchars($method, ENT_QUOTES, 'UTF-8') . '"';
        $contentAttributes .= ' tabindex="0"';
        
        if (!empty($contentStyle)) {
            $contentAttributes .= ' style="' . htmlspecialchars($contentStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $contentContainerAttributes . '>';
        $html .= '<div ' . $contentAttributes . '>';

        foreach ($contentSections as $index => $section) {
            $sectionId = isset($section['id']) ? $section['id'] : 'section_' . ($index + 1);
            $sectionTitle = isset($section['title']) ? $section['title'] : 'Section ' . ($index + 1);
            $sectionContent = isset($section['content']) ? $section['content'] : 'Content for section ' . ($index + 1);
            $sectionClass = isset($section['class']) ? $section['class'] : '';
            $sectionStyle = isset($section['style']) ? $section['style'] : '';

            $html .= $this->buildContentSection($sectionId, $sectionTitle, $sectionContent, $sectionClass, $sectionStyle, $index === 0);
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Build content section
     * 
     * @param string $sectionId
     * @param string $sectionTitle
     * @param string $sectionContent
     * @param string $sectionClass
     * @param string $sectionStyle
     * @param bool $isActive
     * @return string
     */
    private function buildContentSection($sectionId, $sectionTitle, $sectionContent, $sectionClass, $sectionStyle, $isActive)
    {
        // Build section classes
        $classes = 'content-section';
        if ($isActive) {
            $classes .= ' active';
        }
        if (!empty($sectionClass)) {
            $classes .= ' ' . $sectionClass;
        }

        // Build section attributes
        $attributes = 'id="' . htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $classes . '"';
        $attributes .= ' role="tabpanel"';
        $attributes .= ' aria-labelledby="' . htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') . '-tab"';
        
        if (!empty($sectionStyle)) {
            $attributes .= ' style="' . htmlspecialchars($sectionStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $attributes . '>';
        $html .= '<h4>' . htmlspecialchars($sectionTitle, ENT_QUOTES, 'UTF-8') . '</h4>';
        $html .= '<div class="section-content">' . $sectionContent . '</div>';
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
     * Create a simple scrollspy
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with pills navigation
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function pills($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'nav nav-pills flex-column'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with tabs navigation
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function tabs($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'nav nav-tabs flex-column'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with list group navigation
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function listGroup($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'list-group list-group-flush'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with custom offset
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param int $offset Offset in pixels
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withOffset($navItems, $contentSections, $offset = 50, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'offset' => $offset
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with position method
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function position($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'method' => 'position'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy without smooth scrolling
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noSmooth($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'smooth' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with custom layout
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param string $navContainerClass Navigation container class
     * @param string $contentContainerClass Content container class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function customLayout($navItems, $contentSections, $navContainerClass = 'col-md-3', $contentContainerClass = 'col-md-9', $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => $navContainerClass,
            'contentContainerClass' => $contentContainerClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with sidebar layout
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function sidebar($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-2',
            'contentContainerClass' => 'col-md-10',
            'navClass' => 'nav nav-pills flex-column'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with wide layout
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function wide($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-4',
            'contentContainerClass' => 'col-md-8'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with narrow layout
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function narrow($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-1',
            'contentContainerClass' => 'col-md-11'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with pills and offset
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param int $offset Offset in pixels
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function pillsWithOffset($navItems, $contentSections, $offset = 50, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'nav nav-pills flex-column',
            'offset' => $offset
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with tabs and position method
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function tabsPosition($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'nav nav-tabs flex-column',
            'method' => 'position'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with list group and no smooth scrolling
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function listGroupNoSmooth($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navClass' => 'list-group list-group-flush',
            'smooth' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with sidebar and pills
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function sidebarPills($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-2',
            'contentContainerClass' => 'col-md-10',
            'navClass' => 'nav nav-pills flex-column'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with wide layout and tabs
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function wideTabs($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-4',
            'contentContainerClass' => 'col-md-8',
            'navClass' => 'nav nav-tabs flex-column'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with narrow layout and list group
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function narrowListGroup($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-1',
            'contentContainerClass' => 'col-md-11',
            'navClass' => 'list-group list-group-flush'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with sidebar, pills, and offset
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param int $offset Offset in pixels
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function sidebarPillsOffset($navItems, $contentSections, $offset = 50, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-2',
            'contentContainerClass' => 'col-md-10',
            'navClass' => 'nav nav-pills flex-column',
            'offset' => $offset
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with wide layout, tabs, and position method
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function wideTabsPosition($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-4',
            'contentContainerClass' => 'col-md-8',
            'navClass' => 'nav nav-tabs flex-column',
            'method' => 'position'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with narrow layout, list group, and no smooth scrolling
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function narrowListGroupNoSmooth($navItems, $contentSections, $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'navContainerClass' => 'col-md-1',
            'contentContainerClass' => 'col-md-11',
            'navClass' => 'list-group list-group-flush',
            'smooth' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with custom active class
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param string $activeClass Custom active class
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomActiveClass($navItems, $contentSections, $activeClass = 'custom-active', $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'activeClass' => $activeClass
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a scrollspy with custom data attributes
     * 
     * @param array $navItems Navigation items array
     * @param array $contentSections Content sections array
     * @param array $data Custom data attributes
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomData($navItems, $contentSections, $data = [], $options = null)
    {
        $defaultOptions = [
            'navItems' => $navItems,
            'contentSections' => $contentSections,
            'data' => $data
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
