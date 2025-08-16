<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Collapse Component
 * 
 * Creates a Bootstrap collapse element with trigger and content.
 * Supports accordion behavior, custom styling, and Bootstrap 5 classes.
 */
class Collapse extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure collapse ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'collapse_' . uniqid();
        }
        
        // Ensure trigger ID is set if not provided
        if (!isset($this->options['triggerId']) || empty($this->options['triggerId'])) {
            $this->options['triggerId'] = 'trigger_' . $this->options['id'];
        }
    }

    /**
     * Get default options for the collapse
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'triggerId' => '',
            'trigger' => '',
            'content' => '',
            'expanded' => false,
            'accordion' => false,
            'parent' => '',
            'class' => '',
            'style' => '',
            'triggerClass' => '',
            'triggerStyle' => '',
            'contentClass' => '',
            'contentStyle' => '',
            'triggerType' => 'button', // button, link, div
            'triggerVariant' => 'primary', // Bootstrap button variant
            'triggerSize' => '', // sm, lg
            'triggerIcon' => '',
            'triggerIconExpanded' => '',
            'triggerText' => 'Toggle',
            'triggerTextExpanded' => '',
            'animation' => true,
            'data' => []
        ];
    }

    /**
     * Render the collapse as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $triggerId = $this->options['triggerId'];
        $trigger = $this->options['trigger'];
        $content = $this->options['content'];
        $expanded = $this->options['expanded'];
        $accordion = $this->options['accordion'];
        $parent = $this->options['parent'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $triggerClass = $this->options['triggerClass'];
        $triggerStyle = $this->options['triggerStyle'];
        $contentClass = $this->options['contentClass'];
        $contentStyle = $this->options['contentStyle'];
        $triggerType = $this->options['triggerType'];
        $triggerVariant = $this->options['triggerVariant'];
        $triggerSize = $this->options['triggerSize'];
        $triggerIcon = $this->options['triggerIcon'];
        $triggerIconExpanded = $this->options['triggerIconExpanded'];
        $triggerText = $this->options['triggerText'];
        $triggerTextExpanded = $this->options['triggerTextExpanded'];
        $animation = $this->options['animation'];
        $data = $this->options['data'];

        $html = '';

        // Build trigger
        if (!empty($trigger)) {
            $html .= $trigger;
        } else {
            $html .= $this->buildTrigger($triggerId, $id, $expanded, $triggerType, $triggerVariant, $triggerSize, $triggerIcon, $triggerIconExpanded, $triggerText, $triggerTextExpanded, $triggerClass, $triggerStyle, $accordion, $parent);
        }

        // Build collapse content
        $html .= $this->buildCollapseContent($id, $content, $expanded, $accordion, $parent, $class, $style, $contentClass, $contentStyle, $animation, $data);

        return $html;
    }

    /**
     * Build trigger element
     * 
     * @param string $triggerId
     * @param string $targetId
     * @param bool $expanded
     * @param string $triggerType
     * @param string $triggerVariant
     * @param string $triggerSize
     * @param string $triggerIcon
     * @param string $triggerIconExpanded
     * @param string $triggerText
     * @param string $triggerTextExpanded
     * @param string $triggerClass
     * @param string $triggerStyle
     * @param bool $accordion
     * @param string $parent
     * @return string
     */
    private function buildTrigger($triggerId, $targetId, $expanded, $triggerType, $triggerVariant, $triggerSize, $triggerIcon, $triggerIconExpanded, $triggerText, $triggerTextExpanded, $triggerClass, $triggerStyle, $accordion, $parent)
    {
        // Determine current state
        $isExpanded = $expanded;
        $currentText = $isExpanded && !empty($triggerTextExpanded) ? $triggerTextExpanded : $triggerText;
        $currentIcon = $isExpanded && !empty($triggerIconExpanded) ? $triggerIconExpanded : $triggerIcon;

        // Build trigger classes
        $classes = '';
        if ($triggerType === 'button') {
            $classes = 'btn btn-' . $triggerVariant;
            if (!empty($triggerSize)) {
                $classes .= ' btn-' . $triggerSize;
            }
        }
        if (!empty($triggerClass)) {
            $classes .= ' ' . $triggerClass;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($triggerId, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' type="button"';
        $attributes .= ' data-bs-toggle="collapse"';
        $attributes .= ' data-bs-target="#' . htmlspecialchars($targetId, ENT_QUOTES, 'UTF-8') . '"';
        
        if ($accordion && !empty($parent)) {
            $attributes .= ' data-bs-parent="#' . htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if ($isExpanded) {
            $attributes .= ' aria-expanded="true"';
        } else {
            $attributes .= ' aria-expanded="false"';
        }
        
        $attributes .= ' aria-controls="' . htmlspecialchars($targetId, ENT_QUOTES, 'UTF-8') . '"';
        
        if (!empty($classes)) {
            $attributes .= ' class="' . $classes . '"';
        }
        
        if (!empty($triggerStyle)) {
            $attributes .= ' style="' . htmlspecialchars($triggerStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build trigger content
        $triggerContent = '';
        if (!empty($currentIcon)) {
            $triggerContent .= '<i class="' . htmlspecialchars($currentIcon, ENT_QUOTES, 'UTF-8') . '"></i> ';
        }
        $triggerContent .= htmlspecialchars($currentText, ENT_QUOTES, 'UTF-8');

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
     * Build collapse content
     * 
     * @param string $id
     * @param string $content
     * @param bool $expanded
     * @param bool $accordion
     * @param string $parent
     * @param string $class
     * @param string $style
     * @param string $contentClass
     * @param string $contentStyle
     * @param bool $animation
     * @param array $data
     * @return string
     */
    private function buildCollapseContent($id, $content, $expanded, $accordion, $parent, $class, $style, $contentClass, $contentStyle, $animation, $data)
    {
        // Build collapse classes
        $collapseClass = 'collapse';
        if ($expanded) {
            $collapseClass .= ' show';
        }
        if (!$animation) {
            $collapseClass .= ' collapse-horizontal';
        }
        if (!empty($class)) {
            $collapseClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $collapseClass . '"';
        
        if ($accordion && !empty($parent)) {
            $attributes .= ' data-bs-parent="#' . htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        // Build content wrapper
        $contentWrapperClass = 'collapse-content';
        if (!empty($contentClass)) {
            $contentWrapperClass .= ' ' . $contentClass;
        }

        $contentWrapperStyle = '';
        if (!empty($contentStyle)) {
            $contentWrapperStyle = ' style="' . htmlspecialchars($contentStyle, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $attributes . '>';
        $html .= '<div class="' . $contentWrapperClass . '"' . $contentWrapperStyle . '>';
        $html .= $content;
        $html .= '</div>';
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
     * Create a simple collapse with button trigger
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($triggerText, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with custom trigger
     * 
     * @param string $trigger Custom trigger HTML
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCustomTrigger($trigger, $content, $options = null)
    {
        $defaultOptions = [
            'trigger' => $trigger,
            'content' => $content
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an expanded collapse (initially open)
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function expanded($triggerText, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'expanded' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with link trigger
     * 
     * @param string $triggerText Trigger link text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withLinkTrigger($triggerText, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerType' => 'link'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with div trigger
     * 
     * @param string $triggerText Trigger div text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withDivTrigger($triggerText, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerType' => 'div'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with button variant
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param string $variant Button variant (primary, secondary, success, etc.)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtonVariant($triggerText, $content, $variant, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerVariant' => $variant
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with button size
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param string $size Button size (sm, lg)
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtonSize($triggerText, $content, $size, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerSize' => $size
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with icon
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param string $icon Icon class (e.g., 'fas fa-chevron-down')
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withIcon($triggerText, $content, $icon, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerIcon' => $icon
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with expandable icon
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param string $iconCollapsed Icon when collapsed (e.g., 'fas fa-chevron-down')
     * @param string $iconExpanded Icon when expanded (e.g., 'fas fa-chevron-up')
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withExpandableIcon($triggerText, $content, $iconCollapsed, $iconExpanded, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'triggerIcon' => $iconCollapsed,
            'triggerIconExpanded' => $iconExpanded
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse with expandable text
     * 
     * @param string $triggerTextCollapsed Text when collapsed
     * @param string $triggerTextExpanded Text when expanded
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withExpandableText($triggerTextCollapsed, $triggerTextExpanded, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerTextCollapsed,
            'triggerTextExpanded' => $triggerTextExpanded,
            'content' => $content
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse without animation
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutAnimation($triggerText, $content, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'animation' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapse for accordion
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param string $parentAccordionId Parent accordion ID
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function forAccordion($triggerText, $content, $parentAccordionId, $options = null)
    {
        $defaultOptions = [
            'triggerText' => $triggerText,
            'content' => $content,
            'accordion' => true,
            'parent' => $parentAccordionId
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a primary button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function primary($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'primary', $options);
    }

    /**
     * Create a secondary button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function secondary($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'secondary', $options);
    }

    /**
     * Create a success button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function success($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'success', $options);
    }

    /**
     * Create a danger button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function danger($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'danger', $options);
    }

    /**
     * Create a warning button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function warning($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'warning', $options);
    }

    /**
     * Create an info button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function info($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'info', $options);
    }

    /**
     * Create a light button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function light($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'light', $options);
    }

    /**
     * Create a dark button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($triggerText, $content, $options = null)
    {
        return self::withButtonVariant($triggerText, $content, 'dark', $options);
    }

    /**
     * Create a small button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function small($triggerText, $content, $options = null)
    {
        return self::withButtonSize($triggerText, $content, 'sm', $options);
    }

    /**
     * Create a large button collapse
     * 
     * @param string $triggerText Trigger button text
     * @param string $content Collapse content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function large($triggerText, $content, $options = null)
    {
        return self::withButtonSize($triggerText, $content, 'lg', $options);
    }
}
?>
