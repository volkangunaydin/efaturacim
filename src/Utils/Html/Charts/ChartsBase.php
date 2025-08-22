<?php
/**
 * ChartsBase.php
 * 
 * Created: 2024-12-19
 * Prompt: "Can you implement Efaturacim\Util\Utils\Html\Charts\ChartsBase class which extends Efaturacim\Util\Utils\Html\HtmlComponent class. 
 * Please read the comments at the start of the HtmlComponent.php source file and create the class accordingly. 
 * Please include time and the prompt string at the top of the target file in comments. 
 * The aim of the class is: To create a base class for the chart libraries like d3. echarts etc."
 * 
 * Base class for chart libraries like D3, ECharts, etc.
 * Provides common functionality for chart components including data management,
 * configuration options, and rendering capabilities.
 */

namespace Efaturacim\Util\Utils\Html\Charts;

use Efaturacim\Util\Utils\Html\HtmlComponent;

/**
 * Base class for chart libraries like D3, ECharts, etc.
 * 
 * This class provides a foundation for creating chart components with common
 * functionality such as data management, configuration options, and rendering
 * capabilities. Subclasses should implement specific chart library integrations.
 */
class ChartsBase extends HtmlComponent
{
    /**
     * @var string Unique identifier for the chart instance
     */
    protected $chartId;
    
    /**
     * @var array Chart data
     */
    protected $data = [];
    
    /**
     * @var array Chart configuration options
     */
    protected $chartOptions = [];

    /**
     * @var array D3.js options
     */
    protected $prettyPrint = true;
    
    /**
     * @var string Chart type (e.g., 'line', 'bar', 'pie', etc.)
     */
    protected $chartType = 'line';
    
    /**
     * @var array Chart dimensions
     */
    protected $dimensions = [
        'width' => 600,
        'height' => 400
    ];
    
    /**
     * @var string Chart container CSS class
     */
    protected $containerClass = 'chart-container';
    
    /**
     * @var bool Whether to show chart legend
     */
    protected $showLegend = true;
    
    /**
     * @var bool Whether to show chart title
     */
    protected $showTitle = true;
    
    /**
     * @var string Chart title text
     */
    protected $title = '';
    
    /**
     * @var array Chart colors palette
     */
    protected $colors = [
        '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
        '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
    ];
    
    /**
     * Initialize the chart component
     */
    public function initMe()
    {
        parent::initMe();
        
        // Generate unique chart ID if not provided
        if (empty($this->chartId)) {
            $this->chartId = 'chart_' . uniqid();
        }
        
        // Set asset path key for chart libraries
        $this->assetPathKey = 'charts';
    }
    
    /**
     * Get default options for the chart component
     * 
     * @return array Array of default options
     */
    public function getDefaultOptions()
    {
        return [
            'chartId' => null,
            'data' => [],
            'chartOptions' => [],
            'chartType' => 'line',
            'width' => 600,
            'height' => 400,
            'containerClass' => 'chart-container',
            'showLegend' => true,
            'showTitle' => true,
            'title' => '',
            'colors' => [
                '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
                '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
            ]
        ];
    }
    
    /**
     * Set chart data
     * 
     * @param array $data Chart data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Get chart data
     * 
     * @return array Chart data
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Set chart type
     * 
     * @param string $type Chart type (e.g., 'line', 'bar', 'pie', etc.)
     * @return $this
     */
    public function setChartType($type)
    {
        $this->chartType = $type;
        return $this;
    }
    
    /**
     * Get chart type
     * 
     * @return string Chart type
     */
    public function getChartType()
    {
        return $this->chartType;
    }
    
    /**
     * Set chart dimensions
     * 
     * @param int $width Chart width
     * @param int $height Chart height
     * @return $this
     */
    public function setDimensions($width, $height)
    {
        $this->dimensions['width'] = $width;
        $this->dimensions['height'] = $height;
        return $this;
    }
    
    /**
     * Get chart dimensions
     * 
     * @return array Chart dimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    /**
     * Set chart title
     * 
     * @param string $title Chart title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get chart title
     * 
     * @return string Chart title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set chart colors
     * 
     * @param array $colors Array of color values
     * @return $this
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
        return $this;
    }
    
    /**
     * Get chart colors
     * 
     * @return array Chart colors
     */
    public function getColors()
    {
        return $this->colors;
    }
    
    /**
     * Set chart options
     * 
     * @param array $options Chart configuration options
     * @return $this
     */
    public function setChartOptions($options)
    {
        $this->chartOptions = array_merge($this->chartOptions, $options);
        return $this;
    }
    
    /**
     * Get chart options
     * 
     * @return array Chart options
     */
    public function getChartOptions()
    {
        return $this->chartOptions;
    }
    
    /**
     * Get chart ID
     * 
     * @return string Chart ID
     */
    public function getChartId()
    {
        return $this->chartId;
    }
    
    /**
     * Render the chart as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $html = '<div id="' . $this->chartId . '" class="' . $this->containerClass . '" ';
        $html .= 'style="width: ' . $this->dimensions['width'] . 'px; height: ' . $this->dimensions['height'] . 'px;">';
        
        if ($this->showTitle && !empty($this->title)) {
            $html .= '<h3 class="chart-title">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        $html .= '<div class="chart-content"></div>';
        
        if ($this->showLegend) {
            $html .= '<div class="chart-legend"></div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get JavaScript code lines for the chart
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLines()
    {
        // Base implementation - subclasses should override
        return [
            '// Chart initialization code will be implemented in subclasses'
        ];
    }
    
    /**
     * Get JavaScript code lines for chart initialization
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLinesForInit()
    {
        // Base implementation - subclasses should override
        return [
            '// Chart initialization code will be implemented in subclasses'
        ];
    }
    
    /**
     * Get JavaScript files required by the chart
     * 
     * @return array|null Array of JavaScript file paths or null
     */
    public function getJsFiles()
    {
        // Base implementation - subclasses should override
        return null;
    }
    
    /**
     * Get CSS files required by the chart
     * 
     * @return array|null Array of CSS file paths or null
     */
    public function getCssFiles()
    {
        return [
            $this->assetPath . '/css/charts.css'
        ];
    }
    
    /**
     * Validate chart data
     * 
     * @param array $data Chart data to validate
     * @return bool True if data is valid
     */
    protected function validateData($data)
    {
        return is_array($data) && !empty($data);
    }
    
    /**
     * Prepare chart data for rendering
     * 
     * @param array $data Raw chart data
     * @return array Processed chart data
     */
    protected function prepareData($data)
    {
        if (!$this->validateData($data)) {
            return [];
        }
        
        return $data;
    }
    
    /**
     * Generate chart configuration
     * 
     * @return array Chart configuration
     */
    protected function generateConfig()
    {
        return [
            'id' => $this->chartId,
            'type' => $this->chartType,
            'width' => $this->dimensions['width'],
            'height' => $this->dimensions['height'],
            'title' => $this->title,
            'showLegend' => $this->showLegend,
            'colors' => $this->colors,
            'options' => $this->chartOptions
        ];
    }
}
?>
