<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Card Component
 * 
 * Creates a Bootstrap card with header, body, footer, and various content sections.
 * Supports custom styling, images, and Bootstrap 5 classes.
 */
class Card extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure card ID is set if not provided
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'card_' . uniqid();
        }
    }

    /**
     * Get default options for the card
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'title' => '',
            'subtitle' => '',
            'text' => '',
            'body' => '',
            'header' => '',
            'footer' => '',
            'image' => '',
            'imageAlt' => '',
            'imageTop' => true,
            'imageBottom' => false,
            'overlay' => false,
            'class' => '',
            'style' => '',
            'headerClass' => '',
            'bodyClass' => '',
            'footerClass' => '',
            'titleClass' => '',
            'subtitleClass' => '',
            'textClass' => '',
            'imageClass' => '',
            'color' => '',
            'border' => '',
            'textColor' => '',
            'horizontal' => false,
            'horizontalBreakpoint' => '',
            'listGroup' => [],
            'buttons' => [],
            'links' => [],
            'badges' => [],
            'tabs' => [],
            'accordion' => false,
            'collapsible' => false,
            'collapsed' => false,
            'data' => []
        ];
    }

    /**
     * Render the card as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $title = $this->options['title'];
        $subtitle = $this->options['subtitle'];
        $text = $this->options['text'];
        $body = $this->options['body'];
        $header = $this->options['header'];
        $footer = $this->options['footer'];
        $image = $this->options['image'];
        $imageAlt = $this->options['imageAlt'];
        $imageTop = $this->options['imageTop'];
        $imageBottom = $this->options['imageBottom'];
        $overlay = $this->options['overlay'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $headerClass = $this->options['headerClass'];
        $bodyClass = $this->options['bodyClass'];
        $footerClass = $this->options['footerClass'];
        $titleClass = $this->options['titleClass'];
        $subtitleClass = $this->options['subtitleClass'];
        $textClass = $this->options['textClass'];
        $imageClass = $this->options['imageClass'];
        $color = $this->options['color'];
        $border = $this->options['border'];
        $textColor = $this->options['textColor'];
        $horizontal = $this->options['horizontal'];
        $horizontalBreakpoint = $this->options['horizontalBreakpoint'];
        $listGroup = $this->options['listGroup'];
        $buttons = $this->options['buttons'];
        $links = $this->options['links'];
        $badges = $this->options['badges'];
        $tabs = $this->options['tabs'];
        $accordion = $this->options['accordion'];
        $collapsible = $this->options['collapsible'];
        $collapsed = $this->options['collapsed'];
        $data = $this->options['data'];

        // Build card classes
        $cardClass = 'card';
        
        if (!empty($color)) {
            $cardClass .= ' text-bg-' . $color;
        }
        
        if (!empty($border)) {
            $cardClass .= ' border-' . $border;
        }
        
        if (!empty($textColor)) {
            $cardClass .= ' text-' . $textColor;
        }
        
        if ($horizontal && !empty($horizontalBreakpoint)) {
            $cardClass .= ' ' . $horizontalBreakpoint;
        }
        
        if ($accordion) {
            $cardClass .= ' accordion-item';
        }
        
        // Add custom classes
        if (!empty($class)) {
            $cardClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $cardClass . '"';
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '';
        
        // Handle horizontal layout
        if ($horizontal) {
            $html .= '<div ' . $attributes . '>';
            $html .= '<div class="row g-0">';
            
            // Image column
            if (!empty($image)) {
                $html .= '<div class="col-md-4">';
                $html .= '<img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" class="card-img-top h-100' . (!empty($imageClass) ? ' ' . $imageClass : '') . '" alt="' . htmlspecialchars($imageAlt, ENT_QUOTES, 'UTF-8') . '">';
                $html .= '</div>';
            }
            
            // Content column
            $html .= '<div class="col-md-' . (empty($image) ? '12' : '8') . '">';
            $html .= '<div class="card-body">';
            $html .= $this->buildCardContent($title, $subtitle, $text, $body, $titleClass, $subtitleClass, $textClass, $bodyClass);
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div ' . $attributes . '>';
            
            // Top image
            if (!empty($image) && $imageTop && !$overlay) {
                $html .= '<img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" class="card-img-top' . (!empty($imageClass) ? ' ' . $imageClass : '') . '" alt="' . htmlspecialchars($imageAlt, ENT_QUOTES, 'UTF-8') . '">';
            }
            
            // Header
            if (!empty($header)) {
                $html .= '<div class="card-header' . (!empty($headerClass) ? ' ' . $headerClass : '') . '">';
                $html .= $header;
                $html .= '</div>';
            }
            
            // Body with overlay
            if ($overlay && !empty($image)) {
                $html .= '<div class="card-img-overlay">';
                $html .= $this->buildCardContent($title, $subtitle, $text, $body, $titleClass, $subtitleClass, $textClass, $bodyClass);
                $html .= '</div>';
            } else {
                // Regular body
                if (!empty($title) || !empty($subtitle) || !empty($text) || !empty($body) || !empty($listGroup) || !empty($buttons) || !empty($links) || !empty($badges) || !empty($tabs)) {
                    $html .= '<div class="card-body' . (!empty($bodyClass) ? ' ' . $bodyClass : '') . '">';
                    $html .= $this->buildCardContent($title, $subtitle, $text, $body, $titleClass, $subtitleClass, $textClass, '');
                    $html .= '</div>';
                }
            }
            
            // Bottom image
            if (!empty($image) && $imageBottom) {
                $html .= '<img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" class="card-img-bottom' . (!empty($imageClass) ? ' ' . $imageClass : '') . '" alt="' . htmlspecialchars($imageAlt, ENT_QUOTES, 'UTF-8') . '">';
            }
            
            // Footer
            if (!empty($footer)) {
                $html .= '<div class="card-footer' . (!empty($footerClass) ? ' ' . $footerClass : '') . '">';
                $html .= $footer;
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Build card content (title, subtitle, text, body, and other elements)
     * 
     * @param string $title
     * @param string $subtitle
     * @param string $text
     * @param string $body
     * @param string $titleClass
     * @param string $subtitleClass
     * @param string $textClass
     * @param string $bodyClass
     * @return string
     */
    private function buildCardContent($title, $subtitle, $text, $body, $titleClass, $subtitleClass, $textClass, $bodyClass)
    {
        $content = '';
        
        // Title
        if (!empty($title)) {
            $content .= '<h5 class="card-title' . (!empty($titleClass) ? ' ' . $titleClass : '') . '">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h5>';
        }
        
        // Subtitle
        if (!empty($subtitle)) {
            $content .= '<h6 class="card-subtitle mb-2 text-muted' . (!empty($subtitleClass) ? ' ' . $subtitleClass : '') . '">' . htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') . '</h6>';
        }
        
        // Text
        if (!empty($text)) {
            $content .= '<p class="card-text' . (!empty($textClass) ? ' ' . $textClass : '') . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        
        // Body content
        if (!empty($body)) {
            $content .= '<div class="' . $bodyClass . '">' . $body . '</div>';
        }
        
        // List group
        if (!empty($this->options['listGroup']) && is_array($this->options['listGroup'])) {
            $content .= '<ul class="list-group list-group-flush">';
            foreach ($this->options['listGroup'] as $item) {
                if (is_array($item)) {
                    $text = isset($item['text']) ? $item['text'] : '';
                    $active = isset($item['active']) ? $item['active'] : false;
                    $disabled = isset($item['disabled']) ? $item['disabled'] : false;
                    $href = isset($item['href']) ? $item['href'] : '';
                    $class = isset($item['class']) ? $item['class'] : '';
                    
                    $itemClass = 'list-group-item';
                    if ($active) $itemClass .= ' active';
                    if ($disabled) $itemClass .= ' disabled';
                    if (!empty($class)) $itemClass .= ' ' . $class;
                    
                    if (!empty($href)) {
                        $content .= '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" class="' . $itemClass . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a>';
                    } else {
                        $content .= '<li class="' . $itemClass . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</li>';
                    }
                } else {
                    $content .= '<li class="list-group-item">' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</li>';
                }
            }
            $content .= '</ul>';
        }
        
        // Buttons
        if (!empty($this->options['buttons']) && is_array($this->options['buttons'])) {
            foreach ($this->options['buttons'] as $button) {
                if (is_array($button)) {
                    $buttonComponent = new Button($button);
                    $content .= $buttonComponent->toHtmlAsString();
                } elseif (is_string($button)) {
                    $content .= $button;
                }
            }
        }
        
        // Links
        if (!empty($this->options['links']) && is_array($this->options['links'])) {
            foreach ($this->options['links'] as $link) {
                if (is_array($link)) {
                    $text = isset($link['text']) ? $link['text'] : 'Link';
                    $href = isset($link['href']) ? $link['href'] : '#';
                    $class = isset($link['class']) ? $link['class'] : 'card-link';
                    
                    $content .= '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" class="' . $class . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a>';
                }
            }
        }
        
        // Badges
        if (!empty($this->options['badges']) && is_array($this->options['badges'])) {
            foreach ($this->options['badges'] as $badge) {
                if (is_array($badge)) {
                    $text = isset($badge['text']) ? $badge['text'] : 'Badge';
                    $variant = isset($badge['variant']) ? $badge['variant'] : 'secondary';
                    $class = isset($badge['class']) ? $badge['class'] : '';
                    
                    $badgeClass = 'badge text-bg-' . $variant;
                    if (!empty($class)) $badgeClass .= ' ' . $class;
                    
                    $content .= '<span class="' . $badgeClass . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>';
                }
            }
        }
        
        return $content;
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
     * Create a simple card with title and text
     * 
     * @param string $title Card title
     * @param string $text Card text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($title, $text, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with image
     * 
     * @param string $image Image URL
     * @param string $title Card title
     * @param string $text Card text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withImage($image, $title, $text, $options = null)
    {
        $defaultOptions = [
            'image' => $image,
            'title' => $title,
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with overlay
     * 
     * @param string $image Image URL
     * @param string $title Card title
     * @param string $text Card text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function overlay($image, $title, $text, $options = null)
    {
        $defaultOptions = [
            'image' => $image,
            'title' => $title,
            'text' => $text,
            'overlay' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a horizontal card
     * 
     * @param string $image Image URL
     * @param string $title Card title
     * @param string $text Card text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function horizontal($image, $title, $text, $options = null)
    {
        $defaultOptions = [
            'image' => $image,
            'title' => $title,
            'text' => $text,
            'horizontal' => true,
            'horizontalBreakpoint' => 'card-horizontal'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with header and footer
     * 
     * @param string $header Header content
     * @param string $body Body content
     * @param string $footer Footer content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHeaderFooter($header, $body, $footer, $options = null)
    {
        $defaultOptions = [
            'header' => $header,
            'body' => $body,
            'footer' => $footer
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with list group
     * 
     * @param array $listItems Array of list items
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withListGroup($listItems, $options = null)
    {
        $defaultOptions = [
            'listGroup' => $listItems
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with buttons
     * 
     * @param array $buttons Array of button configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withButtons($buttons, $options = null)
    {
        $defaultOptions = [
            'buttons' => $buttons
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with links
     * 
     * @param array $links Array of link configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withLinks($links, $options = null)
    {
        $defaultOptions = [
            'links' => $links
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a colored card
     * 
     * @param string $color Bootstrap color variant
     * @param string $title Card title
     * @param string $text Card text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function colored($color, $title, $text, $options = null)
    {
        $defaultOptions = [
            'color' => $color,
            'title' => $title,
            'text' => $text
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a card with badges
     * 
     * @param array $badges Array of badge configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withBadges($badges, $options = null)
    {
        $defaultOptions = [
            'badges' => $badges
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a collapsible card
     * 
     * @param string $title Card title
     * @param string $body Card body content
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function collapsible($title, $body, $options = null)
    {
        $defaultOptions = [
            'title' => $title,
            'body' => $body,
            'collapsible' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
