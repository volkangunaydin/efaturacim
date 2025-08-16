<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Navbar Component
 * 
 * Creates a Bootstrap navbar with brand, navigation items, forms, and various configuration options.
 * Supports different color schemes, positioning, responsive behavior, and Bootstrap 5 classes.
 */
class Navbar extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure navbar ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'navbar_' . uniqid();
        }
        
        // Ensure brand is always set
        if (!isset($this->options['brand'])) {
            $this->options['brand'] = '';
        }
        
        // Ensure items is always an array
        if (!isset($this->options['items']) || !is_array($this->options['items'])) {
            $this->options['items'] = [];
        }
        
        // Ensure forms is always an array
        if (!isset($this->options['forms']) || !is_array($this->options['forms'])) {
            $this->options['forms'] = [];
        }
    }

    /**
     * Get default options for the navbar
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'brand' => '',
            'brandText' => 'Brand',
            'brandHref' => '#',
            'brandImage' => '',
            'brandImageAlt' => '',
            'items' => [],
            'forms' => [],
            'variant' => 'light', // light, dark
            'expand' => 'lg', // sm, md, lg, xl, xxl
            'fixed' => '', // top, bottom
            'sticky' => false,
            'container' => 'container-fluid', // container, container-fluid, false
            'class' => '',
            'style' => '',
            'brandClass' => '',
            'brandStyle' => '',
            'navbarClass' => '',
            'navbarStyle' => '',
            'collapseClass' => '',
            'collapseStyle' => '',
            'togglerClass' => '',
            'togglerStyle' => '',
            'togglerText' => 'Toggle navigation',
            'togglerIcon' => 'navbar-toggler-icon',
            'data' => []
        ];
    }

    /**
     * Render the navbar as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $brand = $this->options['brand'];
        $brandText = $this->options['brandText'];
        $brandHref = $this->options['brandHref'];
        $brandImage = $this->options['brandImage'];
        $brandImageAlt = $this->options['brandImageAlt'];
        $items = $this->options['items'];
        $forms = $this->options['forms'];
        $variant = $this->options['variant'];
        $expand = $this->options['expand'];
        $fixed = $this->options['fixed'];
        $sticky = $this->options['sticky'];
        $container = $this->options['container'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $brandClass = $this->options['brandClass'];
        $brandStyle = $this->options['brandStyle'];
        $navbarClass = $this->options['navbarClass'];
        $navbarStyle = $this->options['navbarStyle'];
        $collapseClass = $this->options['collapseClass'];
        $collapseStyle = $this->options['collapseStyle'];
        $togglerClass = $this->options['togglerClass'];
        $togglerStyle = $this->options['togglerStyle'];
        $togglerText = $this->options['togglerText'];
        $togglerIcon = $this->options['togglerIcon'];
        $data = $this->options['data'];

        // Build navbar classes
        $navbarClasses = 'navbar navbar-expand-' . $expand;
        $navbarClasses .= ' navbar-' . $variant;
        
        if ($fixed === 'top') {
            $navbarClasses .= ' fixed-top';
        } elseif ($fixed === 'bottom') {
            $navbarClasses .= ' fixed-bottom';
        }
        
        if ($sticky) {
            $navbarClasses .= ' sticky-top';
        }
        
        if (!empty($class)) {
            $navbarClasses .= ' ' . $class;
        }
        if (!empty($navbarClass)) {
            $navbarClasses .= ' ' . $navbarClass;
        }

        // Build navbar attributes
        $navbarAttributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $navbarAttributes .= ' class="' . $navbarClasses . '"';
        
        if (!empty($style)) {
            $navbarAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        if (!empty($navbarStyle)) {
            $navbarAttributes .= ' style="' . htmlspecialchars($navbarStyle, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $navbarAttributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<nav ' . $navbarAttributes . '>';
        
        // Add container if specified
        if ($container !== false) {
            $html .= '<div class="' . $container . '">';
        }
        
        // Build brand
        if (!empty($brand) || !empty($brandText)) {
            $html .= $this->buildBrand($brand, $brandText, $brandHref, $brandImage, $brandImageAlt, $brandClass, $brandStyle);
        }
        
        // Build toggler
        $html .= $this->buildToggler($id, $togglerClass, $togglerStyle, $togglerText, $togglerIcon);
        
        // Build collapse
        $html .= $this->buildCollapse($id, $items, $forms, $collapseClass, $collapseStyle);
        
        // Close container if specified
        if ($container !== false) {
            $html .= '</div>';
        }
        
        $html .= '</nav>';

        return $html;
    }

    /**
     * Build navbar brand
     * 
     * @param string $brand
     * @param string $brandText
     * @param string $brandHref
     * @param string $brandImage
     * @param string $brandImageAlt
     * @param string $brandClass
     * @param string $brandStyle
     * @return string
     */
    private function buildBrand($brand, $brandText, $brandHref, $brandImage, $brandImageAlt, $brandClass, $brandStyle)
    {
        // If custom brand HTML is provided, return it
        if (!empty($brand)) {
            return $brand;
        }
        
        // Build brand classes
        $brandClasses = 'navbar-brand';
        if (!empty($brandClass)) {
            $brandClasses .= ' ' . $brandClass;
        }

        // Build brand attributes
        $brandAttributes = 'class="' . $brandClasses . '"';
        $brandAttributes .= ' href="' . htmlspecialchars($brandHref, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($brandStyle)) {
            $brandAttributes .= ' style="' . htmlspecialchars($brandStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $brandContent = '';
        
        // Add brand image if specified
        if (!empty($brandImage)) {
            $brandContent .= '<img src="' . htmlspecialchars($brandImage, ENT_QUOTES, 'UTF-8') . '"';
            if (!empty($brandImageAlt)) {
                $brandContent .= ' alt="' . htmlspecialchars($brandImageAlt, ENT_QUOTES, 'UTF-8') . '"';
            }
            $brandContent .= ' class="d-inline-block align-text-top" style="width: 30px; height: 30px;"> ';
        }
        
        // Add brand text
        $brandContent .= htmlspecialchars($brandText, ENT_QUOTES, 'UTF-8');

        return '<a ' . $brandAttributes . '>' . $brandContent . '</a>';
    }

    /**
     * Build navbar toggler
     * 
     * @param string $navbarId
     * @param string $togglerClass
     * @param string $togglerStyle
     * @param string $togglerText
     * @param string $togglerIcon
     * @return string
     */
    private function buildToggler($navbarId, $togglerClass, $togglerStyle, $togglerText, $togglerIcon)
    {
        // Build toggler classes
        $togglerClasses = 'navbar-toggler';
        if (!empty($togglerClass)) {
            $togglerClasses .= ' ' . $togglerClass;
        }

        // Build toggler attributes
        $togglerAttributes = 'class="' . $togglerClasses . '"';
        $togglerAttributes .= ' type="button"';
        $togglerAttributes .= ' data-bs-toggle="collapse"';
        $togglerAttributes .= ' data-bs-target="#' . htmlspecialchars($navbarId, ENT_QUOTES, 'UTF-8') . '_collapse"';
        $togglerAttributes .= ' aria-controls="' . htmlspecialchars($navbarId, ENT_QUOTES, 'UTF-8') . '_collapse"';
        $togglerAttributes .= ' aria-expanded="false"';
        $togglerAttributes .= ' aria-label="' . htmlspecialchars($togglerText, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($togglerStyle)) {
            $togglerAttributes .= ' style="' . htmlspecialchars($togglerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        return '<button ' . $togglerAttributes . '><span class="' . $togglerIcon . '"></span></button>';
    }

    /**
     * Build navbar collapse
     * 
     * @param string $navbarId
     * @param array $items
     * @param array $forms
     * @param string $collapseClass
     * @param string $collapseStyle
     * @return string
     */
    private function buildCollapse($navbarId, $items, $forms, $collapseClass, $collapseStyle)
    {
        // Build collapse classes
        $collapseClasses = 'collapse navbar-collapse';
        if (!empty($collapseClass)) {
            $collapseClasses .= ' ' . $collapseClass;
        }

        // Build collapse attributes
        $collapseAttributes = 'id="' . htmlspecialchars($navbarId, ENT_QUOTES, 'UTF-8') . '_collapse"';
        $collapseAttributes .= ' class="' . $collapseClasses . '"';
        
        if (!empty($collapseStyle)) {
            $collapseAttributes .= ' style="' . htmlspecialchars($collapseStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $collapseAttributes . '>';
        
        // Build navbar items
        if (!empty($items)) {
            $html .= $this->buildNavbarItems($items);
        }
        
        // Build navbar forms
        if (!empty($forms)) {
            $html .= $this->buildNavbarForms($forms);
        }
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Build navbar items
     * 
     * @param array $items
     * @return string
     */
    private function buildNavbarItems($items)
    {
        $html = '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
        
        foreach ($items as $item) {
            $html .= $this->buildNavbarItem($item);
        }
        
        $html .= '</ul>';

        return $html;
    }

    /**
     * Build individual navbar item
     * 
     * @param array|string $item
     * @return string
     */
    private function buildNavbarItem($item)
    {
        // If item is already HTML string, return as is
        if (is_string($item)) {
            return '<li class="nav-item">' . $item . '</li>';
        }
        
        // If item is an array, build from configuration
        if (is_array($item)) {
            $type = isset($item['type']) ? $item['type'] : 'link'; // link, dropdown, divider, header
            $text = isset($item['text']) ? $item['text'] : '';
            $href = isset($item['href']) ? $item['href'] : '#';
            $icon = isset($item['icon']) ? $item['icon'] : '';
            $class = isset($item['class']) ? $item['class'] : '';
            $style = isset($item['style']) ? $item['style'] : '';
            $active = isset($item['active']) ? $item['active'] : false;
            $disabled = isset($item['disabled']) ? $item['disabled'] : false;
            $dropdown = isset($item['dropdown']) ? $item['dropdown'] : [];
            $onclick = isset($item['onclick']) ? $item['onclick'] : '';
            $data = isset($item['data']) ? $item['data'] : [];
            
            // Handle special item types
            if ($type === 'divider') {
                return '<li><hr class="dropdown-divider"></li>';
            }
            
            if ($type === 'header') {
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
            $itemClass = 'nav-item';
            if (!empty($class)) {
                $itemClass .= ' ' . $class;
            }
            
            // Build link classes
            $linkClass = 'nav-link';
            if ($active) {
                $linkClass .= ' active';
            }
            if ($disabled) {
                $linkClass .= ' disabled';
            }
            
            // Build link attributes
            $linkAttributes = 'class="' . $linkClass . '"';
            
            if ($type === 'link') {
                $linkAttributes .= ' href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($style)) {
                $linkAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($onclick)) {
                $linkAttributes .= ' onclick="' . htmlspecialchars($onclick, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if ($disabled) {
                $linkAttributes .= ' aria-disabled="true"';
            }
            
            if ($active) {
                $linkAttributes .= ' aria-current="page"';
            }
            
            // Add data attributes
            foreach ($data as $key => $value) {
                $linkAttributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            // Build link content
            $linkContent = '';
            if (!empty($icon)) {
                $linkContent .= '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i> ';
            }
            $linkContent .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            
            // Build item element
            if ($type === 'dropdown' && !empty($dropdown)) {
                return $this->buildDropdownItem($text, $dropdown, $itemClass, $linkAttributes, $linkContent);
            } else {
                return '<li class="' . $itemClass . '"><a ' . $linkAttributes . '>' . $linkContent . '</a></li>';
            }
        }
        
        return '';
    }

    /**
     * Build dropdown item
     * 
     * @param string $text
     * @param array $dropdown
     * @param string $itemClass
     * @param string $linkAttributes
     * @param string $linkContent
     * @return string
     */
    private function buildDropdownItem($text, $dropdown, $itemClass, $linkAttributes, $linkContent)
    {
        $dropdownId = 'dropdown_' . uniqid();
        
        $html = '<li class="' . $itemClass . ' dropdown">';
        $html .= '<a ' . $linkAttributes . ' href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
        $html .= $linkContent . ' <i class="bi-chevron-down"></i>';
        $html .= '</a>';
        $html .= '<ul class="dropdown-menu">';
        
        foreach ($dropdown as $dropdownItem) {
            if (is_string($dropdownItem)) {
                $html .= '<li>' . $dropdownItem . '</li>';
            } elseif (is_array($dropdownItem)) {
                $dropdownType = isset($dropdownItem['type']) ? $dropdownItem['type'] : 'link';
                $dropdownText = isset($dropdownItem['text']) ? $dropdownItem['text'] : '';
                $dropdownHref = isset($dropdownItem['href']) ? $dropdownItem['href'] : '#';
                $dropdownIcon = isset($dropdownItem['icon']) ? $dropdownItem['icon'] : '';
                $dropdownClass = isset($dropdownItem['class']) ? $dropdownItem['class'] : '';
                $dropdownStyle = isset($dropdownItem['style']) ? $dropdownItem['style'] : '';
                $dropdownDisabled = isset($dropdownItem['disabled']) ? $dropdownItem['disabled'] : false;
                $dropdownActive = isset($dropdownItem['active']) ? $dropdownItem['active'] : false;
                $dropdownOnclick = isset($dropdownItem['onclick']) ? $dropdownItem['onclick'] : '';
                
                if ($dropdownType === 'divider') {
                    $html .= '<li><hr class="dropdown-divider"></li>';
                } elseif ($dropdownType === 'header') {
                    $headerClass = 'dropdown-header';
                    if (!empty($dropdownClass)) {
                        $headerClass .= ' ' . $dropdownClass;
                    }
                    
                    $headerAttributes = 'class="' . $headerClass . '"';
                    if (!empty($dropdownStyle)) {
                        $headerAttributes .= ' style="' . htmlspecialchars($dropdownStyle, ENT_QUOTES, 'UTF-8') . '"';
                    }
                    
                    $html .= '<li><h6 ' . $headerAttributes . '>' . htmlspecialchars($dropdownText, ENT_QUOTES, 'UTF-8') . '</h6></li>';
                } else {
                    $dropdownItemClass = 'dropdown-item';
                    if ($dropdownDisabled) {
                        $dropdownItemClass .= ' disabled';
                    }
                    if ($dropdownActive) {
                        $dropdownItemClass .= ' active';
                    }
                    if (!empty($dropdownClass)) {
                        $dropdownItemClass .= ' ' . $dropdownClass;
                    }
                    
                    $dropdownItemAttributes = 'class="' . $dropdownItemClass . '"';
                    $dropdownItemAttributes .= ' href="' . htmlspecialchars($dropdownHref, ENT_QUOTES, 'UTF-8') . '"';
                    
                    if (!empty($dropdownStyle)) {
                        $dropdownItemAttributes .= ' style="' . htmlspecialchars($dropdownStyle, ENT_QUOTES, 'UTF-8') . '"';
                    }
                    
                    if (!empty($dropdownOnclick)) {
                        $dropdownItemAttributes .= ' onclick="' . htmlspecialchars($dropdownOnclick, ENT_QUOTES, 'UTF-8') . '"';
                    }
                    
                    if ($dropdownDisabled) {
                        $dropdownItemAttributes .= ' aria-disabled="true"';
                    }
                    
                    if ($dropdownActive) {
                        $dropdownItemAttributes .= ' aria-current="true"';
                    }
                    
                    $dropdownItemContent = '';
                    if (!empty($dropdownIcon)) {
                        $dropdownItemContent .= '<i class="' . htmlspecialchars($dropdownIcon, ENT_QUOTES, 'UTF-8') . '"></i> ';
                    }
                    $dropdownItemContent .= htmlspecialchars($dropdownText, ENT_QUOTES, 'UTF-8');
                    
                    $html .= '<li><a ' . $dropdownItemAttributes . '>' . $dropdownItemContent . '</a></li>';
                }
            }
        }
        
        $html .= '</ul>';
        $html .= '</li>';
        
        return $html;
    }

    /**
     * Build navbar forms
     * 
     * @param array $forms
     * @return string
     */
    private function buildNavbarForms($forms)
    {
        $html = '';
        
        foreach ($forms as $form) {
            if (is_string($form)) {
                $html .= $form;
            } elseif (is_array($form)) {
                $type = isset($form['type']) ? $form['type'] : 'search';
                $placeholder = isset($form['placeholder']) ? $form['placeholder'] : 'Search';
                $class = isset($form['class']) ? $form['class'] : '';
                $style = isset($form['style']) ? $form['style'] : '';
                $action = isset($form['action']) ? $form['action'] : '';
                $method = isset($form['method']) ? $form['method'] : 'get';
                
                $formClass = 'd-flex';
                if (!empty($class)) {
                    $formClass .= ' ' . $class;
                }
                
                $formAttributes = 'class="' . $formClass . '"';
                if (!empty($style)) {
                    $formAttributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
                }
                if (!empty($action)) {
                    $formAttributes .= ' action="' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8') . '"';
                }
                $formAttributes .= ' method="' . htmlspecialchars($method, ENT_QUOTES, 'UTF-8') . '"';
                
                $html .= '<form ' . $formAttributes . '>';
                $html .= '<input class="form-control me-2" type="' . $type . '" placeholder="' . htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') . '" aria-label="' . htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') . '">';
                $html .= '<button class="btn btn-outline-success" type="submit">Search</button>';
                $html .= '</form>';
            }
        }
        
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
     * Create a simple navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with custom brand
     * 
     * @param string $brand Custom brand HTML
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomBrand($brand, $items, $options = null)
    {
        $defaultOptions = [
            'brand' => $brand,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with brand image
     * 
     * @param string $brandText Brand text
     * @param string $brandImage Brand image URL
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withBrandImage($brandText, $brandImage, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'brandImage' => $brandImage,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with forms
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array $forms Form configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withForms($brandText, $items, $forms, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'forms' => $forms
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'variant' => 'dark'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a light navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'variant' => 'light'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a fixed top navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fixedTop($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'fixed' => 'top'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a fixed bottom navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fixedBottom($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'fixed' => 'bottom'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a sticky navbar
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function sticky($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'sticky' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with small breakpoint
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'expand' => 'sm'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with medium breakpoint
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function medium($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'expand' => 'md'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with large breakpoint
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'expand' => 'lg'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with extra large breakpoint
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function xl($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'expand' => 'xl'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with extra extra large breakpoint
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function xxl($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'expand' => 'xxl'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with container
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withContainer($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'container' => 'container'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar without container
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutContainer($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items,
            'container' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a navbar with search form
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items
     * @param string $placeholder Search placeholder
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withSearch($brandText, $items, $placeholder = 'Search', $options = null)
    {
        $forms = [
            [
                'type' => 'search',
                'placeholder' => $placeholder
            ]
        ];
        
        return self::withForms($brandText, $items, $forms, $options);
    }

    /**
     * Create a navbar from simple text array
     * 
     * @param string $brandText Brand text
     * @param array $texts Array of navigation text strings
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function fromTexts($brandText, $texts, $options = null)
    {
        $items = [];
        foreach ($texts as $text) {
            $items[] = ['text' => $text, 'href' => '#'];
        }
        
        return self::simple($brandText, $items, $options);
    }

    /**
     * Create a navbar with dropdown items
     * 
     * @param string $brandText Brand text
     * @param array $items Navigation items with dropdowns
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDropdowns($brandText, $items, $options = null)
    {
        $defaultOptions = [
            'brandText' => $brandText,
            'items' => $items
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
