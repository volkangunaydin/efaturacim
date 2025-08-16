<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Bootstrap Carousel Component
 * 
 * Creates a Bootstrap carousel with multiple slides, controls, and indicators.
 * Supports custom styling, autoplay, and Bootstrap 5 classes.
 */
class Carousel extends HtmlComponent
{
    /**
     * Initialize the component
     */
    public function initMe()
    {
        // Ensure slides is always an array
        if (!isset($this->options['slides']) || !is_array($this->options['slides'])) {
            $this->options['slides'] = [];
        }
        
        // Ensure carousel ID is set
        if (!isset($this->options['id']) || empty($this->options['id'])) {
            $this->options['id'] = 'carousel_' . uniqid();
        }
    }

    /**
     * Get default options for the carousel
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'id' => '',
            'slides' => [],
            'indicators' => true,
            'controls' => true,
            'crossfade' => false,
            'dark' => false,
            'class' => '',
            'style' => '',
            'interval' => 5000,
            'pause' => 'hover',
            'wrap' => true,
            'keyboard' => true,
            'ride' => 'carousel',
            'touch' => true,
            'data' => []
        ];
    }

    /**
     * Render the carousel as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $id = $this->options['id'];
        $slides = $this->options['slides'];
        $indicators = $this->options['indicators'];
        $controls = $this->options['controls'];
        $crossfade = $this->options['crossfade'];
        $dark = $this->options['dark'];
        $class = $this->options['class'];
        $style = $this->options['style'];
        $interval = $this->options['interval'];
        $pause = $this->options['pause'];
        $wrap = $this->options['wrap'];
        $keyboard = $this->options['keyboard'];
        $ride = $this->options['ride'];
        $touch = $this->options['touch'];
        $data = $this->options['data'];

        // Build carousel classes
        $carouselClass = 'carousel slide';
        
        if ($crossfade) {
            $carouselClass .= ' carousel-fade';
        }
        
        if ($dark) {
            $carouselClass .= ' carousel-dark';
        }
        
        // Add custom classes
        if (!empty($class)) {
            $carouselClass .= ' ' . $class;
        }

        // Build attributes
        $attributes = 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
        $attributes .= ' class="' . $carouselClass . '"';
        $attributes .= ' data-bs-ride="' . htmlspecialchars($ride, ENT_QUOTES, 'UTF-8') . '"';
        
        if ($interval !== 5000) {
            $attributes .= ' data-bs-interval="' . $interval . '"';
        }
        
        if ($pause !== 'hover') {
            $attributes .= ' data-bs-pause="' . htmlspecialchars($pause, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        if (!$wrap) {
            $attributes .= ' data-bs-wrap="false"';
        }
        
        if (!$keyboard) {
            $attributes .= ' data-bs-keyboard="false"';
        }
        
        if (!$touch) {
            $attributes .= ' data-bs-touch="false"';
        }
        
        if (!empty($style)) {
            $attributes .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Add data attributes
        foreach ($data as $key => $value) {
            $attributes .= ' data-bs-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $html = '<div ' . $attributes . '>';
        
        // Indicators
        if ($indicators && count($slides) > 1) {
            $html .= '<div class="carousel-indicators">';
            foreach ($slides as $index => $slide) {
                $active = $index === 0 ? ' active' : '';
                $html .= '<button type="button" data-bs-target="#' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" data-bs-slide-to="' . $index . '" class="' . $active . '" aria-current="' . ($index === 0 ? 'true' : 'false') . '" aria-label="Slide ' . ($index + 1) . '"></button>';
            }
            $html .= '</div>';
        }
        
        // Slides
        $html .= '<div class="carousel-inner">';
        foreach ($slides as $index => $slide) {
            $active = $index === 0 ? ' active' : '';
            $html .= $this->buildSlide($slide, $active, $index);
        }
        $html .= '</div>';
        
        // Controls
        if ($controls && count($slides) > 1) {
            $html .= '<button class="carousel-control-prev" type="button" data-bs-target="#' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" data-bs-slide="prev">';
            $html .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            $html .= '<span class="visually-hidden">Previous</span>';
            $html .= '</button>';
            $html .= '<button class="carousel-control-next" type="button" data-bs-target="#' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" data-bs-slide="next">';
            $html .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            $html .= '<span class="visually-hidden">Next</span>';
            $html .= '</button>';
        }
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Build individual slide
     * 
     * @param array|string $slide Slide configuration or HTML string
     * @param string $active Active class
     * @param int $index Slide index
     * @return string
     */
    private function buildSlide($slide, $active, $index)
    {
        // If slide is already HTML string, wrap it
        if (is_string($slide)) {
            return '<div class="carousel-item' . $active . '">' . $slide . '</div>';
        }
        
        // If slide is an array, build from configuration
        if (is_array($slide)) {
            $image = isset($slide['image']) ? $slide['image'] : '';
            $alt = isset($slide['alt']) ? $slide['alt'] : 'Slide ' . ($index + 1);
            $title = isset($slide['title']) ? $slide['title'] : '';
            $text = isset($slide['text']) ? $slide['text'] : '';
            $caption = isset($slide['caption']) ? $slide['caption'] : '';
            $class = isset($slide['class']) ? $slide['class'] : '';
            $style = isset($slide['style']) ? $slide['style'] : '';
            
            $slideClass = 'carousel-item' . $active;
            if (!empty($class)) {
                $slideClass .= ' ' . $class;
            }
            
            $html = '<div class="' . $slideClass . '"';
            if (!empty($style)) {
                $html .= ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= '>';
            
            // Image
            if (!empty($image)) {
                $html .= '<img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" class="d-block w-100" alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '">';
            }
            
            // Caption
            if (!empty($title) || !empty($text) || !empty($caption)) {
                $html .= '<div class="carousel-caption d-none d-md-block">';
                if (!empty($title)) {
                    $html .= '<h5>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h5>';
                }
                if (!empty($text)) {
                    $html .= '<p>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>';
                }
                if (!empty($caption)) {
                    $html .= $caption;
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
            return $html;
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
     * Create a simple carousel with images
     * 
     * @param array $images Array of image URLs
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function simple($images, $options = null)
    {
        $slides = [];
        foreach ($images as $image) {
            $slides[] = ['image' => $image];
        }
        
        $defaultOptions = [
            'slides' => $slides
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel with captions
     * 
     * @param array $slides Array of slide configurations with image, title, and text
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withCaptions($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a crossfade carousel
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function crossfade($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'crossfade' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a dark carousel
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function dark($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'dark' => true
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel without indicators
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutIndicators($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'indicators' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel without controls
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutControls($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'controls' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create an autoplay carousel
     * 
     * @param array $slides Array of slide configurations
     * @param int $interval Interval in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function autoplay($slides, $interval = 3000, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'interval' => $interval,
            'ride' => 'carousel'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a manual carousel (no autoplay)
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function manual($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'ride' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel with custom interval
     * 
     * @param array $slides Array of slide configurations
     * @param int $interval Interval in milliseconds
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withInterval($slides, $interval, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'interval' => $interval
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel with pause on hover
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function pauseOnHover($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'pause' => 'hover'
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel without keyboard navigation
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutKeyboard($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'keyboard' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel without touch/swipe
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withoutTouch($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'touch' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel that doesn't wrap
     * 
     * @param array $slides Array of slide configurations
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function noWrap($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides,
            'wrap' => false
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }

    /**
     * Create a carousel with custom HTML slides
     * 
     * @param array $slides Array of HTML strings for slides
     * @param array|null $options Additional options
     * @return string HTML string
     */
    public static function withHtmlSlides($slides, $options = null)
    {
        $defaultOptions = [
            'slides' => $slides
        ];
        
        return (new self($options, $defaultOptions))->toHtmlAsString();
    }
}
?>
