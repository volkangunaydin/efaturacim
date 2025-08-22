<?php
/**
 * D3Charts.php
 * 
 * Created: 2024-12-19
 * Prompt: "Can you implement Efaturacim\Util\Utils\Html\Charts\D3Charts class which extends Efaturacim\Util\Utils\Html\Charts\ChartsBase class. 
 * Please read the comments at the start of the HtmlComponent.php source file and create the class accordingly. 
 * Please include time and the prompt string at the top of the target file in comments. 
 * the website of d3 charts is : @https://d3js.org/ 
 * please provide me simple static funciton for a Stacked Bar Chart"
 * 
 * D3.js integration for chart components.
 * Provides D3.js specific functionality for creating custom data visualizations
 * with unparalleled flexibility as described at https://d3js.org/
 */

namespace Efaturacim\Util\Utils\Html\Charts;

use Efaturacim\Util\Utils\Html\Charts\ChartsBase;
use Efaturacim\Util\Utils\Json\JsonUtil;

/**
 * D3.js chart component
 * 
 * This class provides D3.js integration for creating custom data visualizations.
 * D3.js is the JavaScript library for bespoke data visualization that provides
 * unparalleled flexibility for creating custom dynamic visualizations.
 * 
 * Features include:
 * - Selections and transitions
 * - Scales and axes
 * - Shapes (arcs, areas, curves, lines, links, pies, stacks, symbols)
 * - Interactions (panning, zooming, brushing, dragging)
 * - Layouts (treemaps, trees, force-directed graphs, Voronoi, contours, chords, circle-packing)
 * - Geographic maps
 * 
 * @see https://d3js.org/
 */
class D3Charts extends ChartsBase
{
    /**
     * @var string D3.js version to use
     */
    protected $d3Version = '7.9.0';
    
    /**
     * @var array D3.js specific options
     */
    protected $d3Options = [];
    
    /**
     * @var bool Whether to include D3.js from CDN
     */
    protected $useCdn = true;
    
    /**
     * @var string D3.js CDN URL
     */
    protected $d3CdnUrl = 'https://d3js.org/d3.v7.min.js';
    
    /**
     * Initialize the D3 chart component
     */
    public function initMe()
    {
        parent::initMe();
        
        // Set D3-specific asset path key
        $this->assetPathKey = 'd3';
        
        // Set default chart type for D3
        if (empty($this->chartType)) {
            $this->chartType = 'd3-custom';
        }
    }
    
    /**
     * Get default options for the D3 chart component
     * 
     * @return array Array of default options
     */
    public function getDefaultOptions()
    {
        return array_merge(parent::getDefaultOptions(), [
            'd3Version' => '7.9.0',
            'useCdn' => true,
            'd3CdnUrl' => 'https://d3js.org/d3.v7.min.js',
            'd3Options' => [],
            'margin' => [
                'top' => 20,
                'right' => 20,
                'bottom' => 30,
                'left' => 40
            ],
            'responsive' => true,
            'animate' => true,
            'duration' => 750
        ]);
    }
    
    /**
     * Set D3.js version
     * 
     * @param string $version D3.js version
     * @return $this
     */
    public function setD3Version($version)
    {
        $this->d3Version = $version;
        return $this;
    }
    
    /**
     * Get D3.js version
     * 
     * @return string D3.js version
     */
    public function getD3Version()
    {
        return $this->d3Version;
    }
    
    /**
     * Set D3.js specific options
     * 
     * @param array $options D3.js options
     * @return $this
     */
    public function setD3Options($options)
    {
        $this->d3Options = array_merge($this->d3Options, $options);
        return $this;
    }
    
    /**
     * Get D3.js options
     * 
     * @return array D3.js options
     */
    public function getD3Options()
    {
        return $this->d3Options;
    }
    
    /**
     * Set whether to use CDN for D3.js
     * 
     * @param bool $useCdn Whether to use CDN
     * @return $this
     */
    public function setUseCdn($useCdn)
    {
        $this->useCdn = $useCdn;
        return $this;
    }
    
    /**
     * Get whether to use CDN for D3.js
     * 
     * @return bool Whether to use CDN
     */
    public function getUseCdn()
    {
        return $this->useCdn;
    }
    
    /**
     * Render the D3 chart as HTML string
     * 
     * @param mixed $doc Document context (optional)
     * @return string HTML string representation
     */
    public function toHtmlAsString($doc = null)
    {
        $html = '<div id="' . $this->chartId . '" class="' . $this->containerClass . ' d3-chart" ';
        $html .= 'style="width: ' . $this->dimensions['width'] . 'px; height: ' . $this->dimensions['height'] . 'px;">';
        
        if ($this->showTitle && !empty($this->title)) {
            $html .= '<h3 class="chart-title">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        $html .= '<svg id="' . $this->chartId . '_svg" class="d3-svg"></svg>';
        
        if ($this->showLegend) {
            $html .= '<div class="chart-legend" id="' . $this->chartId . '_legend"></div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get JavaScript code lines for the D3 chart
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLines()
    {
        $config = $this->generateConfig();
        $data = JsonUtil::toJsonStringWithOptions($this->data);
        $options = JsonUtil::toJsonStringWithOptions($this->d3Options);
        
        return [
            '// D3 Chart Configuration',
            'var chartConfig = ' . JsonUtil::toJsonStringWithOptions($config, ['pretty'=>$this->prettyPrint,'js_function'=>true,'jquery_selector'=>true]) . ';',
            'var chartData = ' . $data . ';',
            'var d3Options = ' . $options . ';',
            '',
            '// Initialize D3 Chart',
            'if (typeof d3 !== "undefined") {',
            '    createD3Chart("' . $this->chartId . '", chartData, chartConfig, d3Options);',
            '} else {',
            '    console.error("D3.js is not loaded");',
            '}'
        ];
    }
    
    /**
     * Get JavaScript code lines for D3 chart initialization
     * 
     * @return array|null Array of JavaScript code lines or null
     */
    public function getJsLinesForInit()
    {
        return [
            '// D3 Chart initialization function',
            'function createD3Chart(containerId, data, config, options) {',
            '    var container = d3.select("#" + containerId);',
            '    var svg = container.select("svg");',
            '    ',
            '    // Clear previous content',
            '    svg.selectAll("*").remove();',
            '    ',
            '    // Set SVG dimensions',
            '    svg.attr("width", config.width)',
            '       .attr("height", config.height);',
            '    ',
            '    // Create chart based on type',
            '    switch(config.type) {',
            '        case "stacked-bar":',
            '            createStackedBarChart(svg, data, config, options);',
            '            break;',
            '        case "line":',
            '            createLineChart(svg, data, config, options);',
            '            break;',
            '        case "pie":',
            '            createPieChart(svg, data, config, options);',
            '            break;',
            '        default:',
            '            console.log("Chart type not implemented:", config.type);',
            '    }',
            '}',
            '',
            '// Stacked Bar Chart function',
            'function createStackedBarChart(svg, data, config, options) {',
            '    var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '    var width = config.width - margin.left - margin.right;',
            '    var height = config.height - margin.top - margin.bottom;',
            '    ',
            '    var g = svg.append("g")',
            '        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");',
            '    ',
            '    // Create scales',
            '    var x = d3.scaleBand()',
            '        .range([0, width])',
            '        .padding(0.1);',
            '    ',
            '    var y = d3.scaleLinear()',
            '        .range([height, 0]);',
            '    ',
            '    var z = d3.scaleOrdinal()',
            '        .range(config.colors || d3.schemeCategory10);',
            '    ',
            '    // Process data for stacked bar chart',
            '    var keys = Object.keys(data[0]).filter(function(key) { return key !== "category"; });',
            '    var stack = d3.stack().keys(keys);',
            '    var series = stack(data);',
            '    ',
            '    // Set domains',
            '    x.domain(data.map(function(d) { return d.category; }));',
            '    y.domain([0, d3.max(series, function(d) { return d3.max(d, function(d) { return d[1]; }); })]).nice();',
            '    z.domain(keys);',
            '    ',
            '    // Add bars',
            '    g.append("g")',
            '        .selectAll("g")',
            '        .data(series)',
            '        .enter().append("g")',
            '        .attr("fill", function(d) { return z(d.key); })',
            '        .selectAll("rect")',
            '        .data(function(d) { return d; })',
            '        .enter().append("rect")',
            '        .attr("x", function(d) { return x(d.data.category); })',
            '        .attr("y", function(d) { return y(d[1]); })',
            '        .attr("height", function(d) { return y(d[0]) - y(d[1]); })',
            '        .attr("width", x.bandwidth());',
            '    ',
            '    // Add axes',
            '    g.append("g")',
            '        .attr("transform", "translate(0," + height + ")")',
            '        .call(d3.axisBottom(x));',
            '    ',
            '    g.append("g")',
            '        .call(d3.axisLeft(y));',
            '}',
            '',
            '// Line Chart function',
            'function createLineChart(svg, data, config, options) {',
            '    // Implementation for line chart',
            '    console.log("Line chart implementation");',
            '}',
            '',
            '// Pie Chart function',
            'function createPieChart(svg, data, config, options) {',
            '    // Implementation for pie chart',
            '    console.log("Pie chart implementation");',
            '}'
        ];
    }
    
    /**
     * Get JavaScript files required by the D3 chart
     * 
     * @return array|null Array of JavaScript file paths or null
     */
    public function getJsFiles()
    {
        if ($this->useCdn) {
            return [
                'd3' => $this->d3CdnUrl
            ];
        } else {
            return [
                'd3' => $this->assetPath . '/js/d3.v' . $this->d3Version . '.min.js'
            ];
        }
    }
    
    /**
     * Get CSS files required by the D3 chart
     * 
     * @return array|null Array of CSS file paths or null
     */
    public function getCssFiles()
    {
        return [
            'd3' => $this->assetPath . '/css/d3-charts.css'
        ];
    }
    
    /**
     * Create a simple stacked bar chart
     * 
     * This static function provides a quick way to create a stacked bar chart
     * using D3.js with minimal configuration.
     * 
     * @param array $data Chart data in format: [
     *     ['category' => 'A', 'series1' => 10, 'series2' => 20],
     *     ['category' => 'B', 'series1' => 15, 'series2' => 25],
     *     ...
     * ]
     * @param array $options Chart options
     * @return D3Charts Chart instance
     */
    public static function createStackedBarChart($data, $options = [])
    {
        $defaultOptions = [
            'chartType' => 'stacked-bar',
            'title' => 'Stacked Bar Chart',
            'width' => 600,
            'height' => 400,
            'colors' => [
                '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
                '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
            ],
            'margin' => [
                'top' => 20,
                'right' => 20,
                'bottom' => 30,
                'left' => 40
            ],
            'showLegend' => true,
            'showTitle' => true
        ];
        
        $mergedOptions = array_merge($defaultOptions, $options);
        
        $chart = new self([
            'data' => $data
        ], $mergedOptions);
        
        return $chart;
    }
    
    /**
     * Generate D3-specific configuration
     * 
     * @return array Chart configuration
     */
    protected function generateConfig()
    {
        $config = parent::generateConfig();
        
        return array_merge($config, [
            'd3Version' => $this->d3Version,
            'useCdn' => $this->useCdn,
            'd3CdnUrl' => $this->d3CdnUrl,
            'd3Options' => $this->d3Options,
            'margin' => $this->options['margin'] ?? [
                'top' => 20,
                'right' => 20,
                'bottom' => 30,
                'left' => 40
            ],
            'responsive' => $this->options['responsive'] ?? true,
            'animate' => $this->options['animate'] ?? true,
            'duration' => $this->options['duration'] ?? 750
        ]);
    }
    
    /**
     * Validate D3 chart data
     * 
     * @param array $data Chart data to validate
     * @return bool True if data is valid
     */
    protected function validateData($data)
    {
        if (!parent::validateData($data)) {
            return false;
        }
        
        // For stacked bar chart, data should be an array of objects
        // with at least one category and one series
        if ($this->chartType === 'stacked-bar') {
            if (!isset($data[0]) || !is_array($data[0])) {
                return false;
            }
            
            $firstItem = $data[0];
            if (!isset($firstItem['category'])) {
                return false;
            }
            
            // Check if there's at least one series column
            $hasSeries = false;
            foreach ($firstItem as $key => $value) {
                if ($key !== 'category' && is_numeric($value)) {
                    $hasSeries = true;
                    break;
                }
            }
            
            return $hasSeries;
        }
        
        return true;
    }
    
    /**
     * Prepare D3 chart data for rendering
     * 
     * @param array $data Raw chart data
     * @return array Processed chart data
     */
    protected function prepareData($data)
    {
        if (!$this->validateData($data)) {
            return [];
        }
        
        // For stacked bar chart, ensure data is in correct format
        if ($this->chartType === 'stacked-bar') {
            $processedData = [];
            foreach ($data as $item) {
                $processedItem = [];
                foreach ($item as $key => $value) {
                    if ($key === 'category') {
                        $processedItem[$key] = $value;
                    } else {
                        $processedItem[$key] = (float) $value;
                    }
                }
                $processedData[] = $processedItem;
            }
            return $processedData;
        }
        
        return $data;
    }
}
?>
