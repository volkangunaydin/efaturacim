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
 * D3.js integration for stacked bar chart components.
 * Provides D3.js specific functionality for creating stacked bar charts
 * with unparalleled flexibility as described at https://d3js.org/
 */

namespace Efaturacim\Util\Utils\Html\Charts;

use Efaturacim\Util\Utils\Html\Charts\ChartsBase;
use Efaturacim\Util\Utils\Json\JsonUtil;

/**
 * D3.js stacked bar chart component
 * 
 * This class provides D3.js integration for creating stacked bar charts.
 * D3.js is the JavaScript library for bespoke data visualization that provides
 * unparalleled flexibility for creating custom dynamic visualizations.
 * 
 * Features include:
 * - Interactive stacked bar charts
 * - Responsive design
 * - Tooltips and legends
 * - Customizable colors and styling
 * - Smooth animations and transitions
 * 
 * @see https://d3js.org/
 */
class D3Charts extends ChartsBase
{
    /**
     * @var string D3.js version to use
     */
    protected $d3Version = '5.16.0';
    
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
    protected $d3CdnUrl = 'https://d3js.org/d3.v5.min.js';
    
    /**
     * @var array Processed chart data
     */
    protected $processedData = [];
    
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
            $this->chartType = 'stacked-bar';
        }
        
        // Initialize processedData if not set
        if (!isset($this->processedData)) {
            $this->processedData = [];
        }
        
        // Ensure data is set from options if not already set
        if (empty($this->data) && !empty($this->options['data'])) {
            $this->data = $this->options['data'];
        }
        
        // Process data if not already processed
        if (!empty($this->data) && empty($this->processedData)) {
            $this->processedData = $this->prepareData($this->data);
        }
        
        // Ensure chartType is set in options if not already set
        if (empty($this->options['chartType'])) {
            $this->options['chartType'] = $this->chartType;
        }
        
        // Debug: Log data after initialization
        error_log('D3Charts initMe - Data after init: ' . print_r($this->data, true));
        error_log('D3Charts initMe - Processed data after init: ' . print_r($this->processedData, true));
    }
    
    /**
     * Get default options for the D3 chart component
     * 
     * @return array Array of default options
     */
    public function getDefaultOptions()
    {
        return array_merge(parent::getDefaultOptions(), [
            'd3Version' => '5.16.0',
            'useCdn' => true,
            'd3CdnUrl' => 'https://d3js.org/d3.v5.min.js',
            'd3Options' => [
                'type' => 'stacked-bar'
            ],
            'margin' => [
                'top' => 10,
                'right' => 10,
                'bottom' => 40,
                'left' => 50
            ],
            'responsive' => true,
            'animate' => true,
            'duration' => 750,
            // Yeni özellikler
            'showTooltip' => true,
            'showLegend' => true,
            'legendPosition' => 'top-right', // top-right, top-left, bottom-right, bottom-left
            'tooltipFormat' => 'value', // value, percentage, custom
            'customTooltip' => null, // Custom tooltip function
            'axisLabels' => [
                'x' => '',
                'y' => ''
            ],
            'tickFormat' => [
                'x' => null, // Custom x-axis tick format function
                'y' => null  // Custom y-axis tick format function
            ],
            'interactive' => true,
            'hoverEffects' => true,
            'transitionDuration' => 300,
            'legendStyle' => [
                'fontSize' => '12px',
                'fontFamily' => 'Arial, sans-serif'
            ],
            'tooltipStyle' => [
                'backgroundColor' => 'rgba(0,0,0,0.8)',
                'color' => 'white',
                'borderRadius' => '4px',
                'padding' => '8px',
                'fontSize' => '12px'
            ]
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
        $html = '';
        
        // Add CSS files
        $cssFiles = $this->getCssFiles();
        if ($cssFiles) {
            foreach ($cssFiles as $cssFile) {
                $html .= '<link rel="stylesheet" href="' . $cssFile . '">' . "\n";
            }
        }
        
        // Add minimal CSS
        $html .= '<style>' . "\n";
        $html .= '.d3-chart { width: 100%; }' . "\n";
        $html .= '.d3-chart svg { width: 100%; }' . "\n";
        $html .= '.d3-chart .chart-legend { text-align: center; margin-bottom: 10px; padding: 8px; background: #f8f9fa; border-radius: 6px; }' . "\n";
        $html .= '.d3-chart .legend-item { display: inline-block; margin: 5px 10px; cursor: pointer; transition: all 0.3s ease; }' . "\n";
        $html .= '.d3-chart .legend-item:hover { transform: scale(1.05); }' . "\n";
        $html .= '.d3-chart .legend-color { display: inline-block; width: 12px; height: 12px; margin-right: 5px; border-radius: 2px; }' . "\n";
        $html .= '.d3-chart .legend-text { font-size: 12px; color: #666; }' . "\n";
        $html .= '.d3-tooltip { position: absolute; background: white; color: #333; padding: 10px 15px; border-radius: 6px; font-size: 12px; pointer-events: none; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.15); border: 1px solid #e0e0e0; }' . "\n";
        $html .= '</style>' . "\n";
        
        // Add JavaScript files
        $jsFiles = $this->getJsFiles();
        if ($jsFiles) {
            foreach ($jsFiles as $jsFile) {
                $html .= '<script src="' . $jsFile . '"></script>' . "\n";
            }
        }
        
        // Add chart container with responsive styling
        $width = $this->dimensions['width'];
        $height = $this->dimensions['height'];
        
        // Handle percentage width for responsive design
        $widthStyle = is_numeric($width) ? $width . 'px' : $width;
        
        $html .= '<div id="' . $this->chartId . '" class="' . $this->containerClass . ' d3-chart">';
        
        if ($this->showTitle && !empty($this->title)) {
            $html .= '<h3 class="chart-title">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        if ($this->showLegend) {
            $html .= '<div class="chart-legend" id="' . $this->chartId . '_legend"></div>';
        }
        
        $html .= '<svg id="' . $this->chartId . '_svg" class="d3-svg" width="100%" height="' . $height . '"></svg>';
        
        $html .= '</div>';
        
        // Add JavaScript code
        $jsLines = $this->getJsLines();
        if ($jsLines) {
            $html .= '<script>' . "\n";
            foreach ($jsLines as $line) {
                $html .= $line . "\n";
            }
            $html .= '</script>';
        }
        
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
        
        // Ensure data is processed
        if (empty($this->processedData) && !empty($this->data)) {
            $this->processedData = $this->prepareData($this->data);
        }
        
        // Use processed data if available, otherwise use original data
        $dataToUse = !empty($this->processedData) ? $this->processedData : $this->data;
        
        // Debug: Log data for troubleshooting
        error_log('D3Charts Debug - Original Data: ' . print_r($this->data, true));
        error_log('D3Charts Debug - Processed Data: ' . print_r($this->processedData ?? [], true));
        error_log('D3Charts Debug - Data To Use: ' . print_r($dataToUse, true));
        
        $data = JsonUtil::toJsonStringWithOptions($dataToUse);
        $options = JsonUtil::toJsonStringWithOptions($this->d3Options);
        
        return [
            '// D3 Chart Configuration for ' . $this->chartId,
            'var chartConfig_' . $this->chartId . ' = ' . JsonUtil::toJsonStringWithOptions($config, ['pretty'=>$this->prettyPrint,'js_function'=>true,'jquery_selector'=>true]) . ';',
            'var chartData_' . $this->chartId . ' = ' . $data . ';',
            'var d3Options_' . $this->chartId . ' = ' . $options . ';',
            '',
            '// Initialize D3 Chart for ' . $this->chartId,
            'try {',
            '    if (typeof d3 !== "undefined") {',
            '        createD3Chart_' . $this->chartId . '("' . $this->chartId . '", chartData_' . $this->chartId . ', chartConfig_' . $this->chartId . ', d3Options_' . $this->chartId . ');',
            '    } else {',
            '        console.error("D3.js is not loaded");',
            '    }',
            '} catch (error) {',
            '    console.error("Error initializing chart:", error);',
            '}',
            '',
            '// Add resize event listener for responsive behavior',
            'window.addEventListener("resize", function() {',
            '    if (typeof d3 !== "undefined") {',
            '        createD3Chart_' . $this->chartId . '("' . $this->chartId . '", chartData_' . $this->chartId . ', chartConfig_' . $this->chartId . ', d3Options_' . $this->chartId . ');',
            '    }',
            '});',
            '',
            '// D3 Chart Functions for ' . $this->chartId,
            'function createD3Chart_' . $this->chartId . '(containerId, data, config, options) {',
            '    try {',
            '        var container = d3.select("#" + containerId);',
            '        var svg = container.select("svg");',
            '        ',
            '        // Clear previous content',
            '        svg.selectAll("*").remove();',
            '        ',
            '        // Set SVG dimensions dynamically',
            '        var containerWidth = container.node().getBoundingClientRect().width;',
            '        var actualWidth = typeof config.width === "string" && config.width.includes("%") ? containerWidth : config.width;',
            '        svg.attr("width", actualWidth)',
            '           .attr("height", config.height);',
            '        ',
            '        // Create stacked bar chart',
            '        createStackedBarChart_' . $this->chartId . '(svg, data, config, options, container);',
            '    } catch (error) {',
            '        console.error("Error in createD3Chart:", error);',
            '    }',
            '}',
            '',
            '// Stacked Bar Chart function for ' . $this->chartId,
            'function createStackedBarChart_' . $this->chartId . '(svg, data, config, options, container) {',
            '    try {',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var containerWidth = svg.node().getBoundingClientRect().width;',
            '        var actualWidth = typeof config.width === "string" && config.width.includes("%") ? containerWidth : config.width;',
            '        var width = actualWidth;',
            '        var height = config.height;',
            '        ',
            '        var g = svg.append("g");',
            '        ',
            '        // Create scales',
            '        var x = d3.scaleBand()',
            '            .range([0, width - 30])',
            '            .padding(0.2);',
            '        ',
            '        var y = d3.scaleLinear()',
            '            .range([height - 20, 0]);',
            '        ',
            '        var z = d3.scaleOrdinal()',
            '            .range(config.colors || ["#dc3545", "#fd7e14", "#20c997", "#0d6efd", "#6f42c1"]);',
            '        ',
            '        // Process data for stacked bar chart',
            '        var keys = Object.keys(data[0]).filter(function(key) { return key !== "category"; });',
            '        var stack = d3.stack().keys(keys);',
            '        var series = stack(data);',
            '        ',
            '        // Set domains',
            '        x.domain(data.map(function(d) { return d.category; }));',
            '        y.domain([0, d3.max(series, function(d) { return d3.max(d, function(d) { return d[1]; }); })]).nice();',
            '        z.domain(keys);',
            '        ',
            '        // Create legend',
            '        var legend = container.select(".chart-legend");',
            '        var legendItems = legend.selectAll(".legend-item")',
            '            .data(keys);',
            '        ',
            '        var legendEnter = legendItems.enter()',
            '            .append("div")',
            '            .attr("class", "legend-item")',
            '            .style("opacity", 1);',
            '        ',
            '        legendEnter.append("span")',
            '            .attr("class", "legend-color")',
            '            .style("background-color", function(d) { return z(d); });',
            '        ',
            '        legendEnter.append("span")',
            '            .attr("class", "legend-text")',
            '            .text(function(d) { return d; });',
            '        ',
            '        // Legend click handler for filtering',
            '        legendItems.merge(legendEnter)',
            '            .on("click", function(event, d) {',
            '                var legendItem = d3.select(this);',
            '                var isActive = legendItem.style("opacity") == "1";',
            '                ',
            '                console.log("Toggling series:", d, "isActive:", isActive);',
            '                ',
            '                // Toggle legend item visual state',
            '                legendItem.style("opacity", isActive ? 0.3 : 1);',
            '                legendItem.style("text-decoration", isActive ? "line-through" : "none");',
            '                ',
            '                // Toggle corresponding bars - completely hide/show',
            '                var seriesGroup = g.selectAll("g")',
            '                    .filter(function(seriesData) { return seriesData.key === d; });',
            '                ',
            '                console.log("Found series groups:", seriesGroup.size());',
            '                ',
            '                if (isActive) {',
            '                    // Hide the series completely',
            '                    seriesGroup.style("display", "none");',
            '                } else {',
            '                    // Show the series',
            '                    seriesGroup.style("display", null);',
            '                }',
            '            });',
            '        ',
            '        // Add bars with professional styling',
            '        g.append("g")',
            '            .selectAll("g")',
            '            .data(series)',
            '            .enter().append("g")',
            '            .attr("fill", function(d) { return z(d.key); })',
            '            .selectAll("rect")',
            '            .data(function(d) { return d; })',
            '            .enter().append("rect")',
            '            .attr("x", function(d) { return x(d.data.category) + 30; })',
            '            .attr("y", function(d) { return y(d[1]); })',
            '            .attr("height", function(d) { return y(d[0]) - y(d[1]); })',
            '            .attr("width", x.bandwidth())',
            '            .style("stroke", "white")',
            '            .style("stroke-width", "1px")',
            '            .style("opacity", 0.9)',
            '            .on("mouseover", function(event, d) {',
            '                console.log("Mouseover event triggered for:", d);',
            '                console.log("this element:", this);',
            '                console.log("d3.select(this).datum():", d3.select(this).datum());',
            '                d3.select(this)',
            '                    .style("opacity", 1)',
            '                    .style("stroke-width", "2px");',
            '                ',
            '                // Show tooltip - completely new approach',
            '                d3.selectAll(".d3-tooltip").remove(); // Remove existing tooltips',
            '                ',
            '                // Create tooltip with inline styles',
            '                var tooltip = d3.select("body").append("div")',
            '                    .attr("class", "d3-tooltip")',
            '                    .style("position", "absolute")',
            '                    .style("background", "white")',
            '                    .style("color", "#333")',
            '                    .style("padding", "10px 15px")',
            '                    .style("border-radius", "6px")',
            '                    .style("font-size", "12px")',
            '                    .style("pointer-events", "none")',
            '                    .style("z-index", "9999")',
            '                    .style("box-shadow", "0 2px 10px rgba(0,0,0,0.15)")',
            '                    .style("border", "1px solid #e0e0e0")',
            '                    .style("opacity", 0);',
            '                ',
            '                // Get the actual data from the element',
            '                var actualData = d3.select(this).datum();',
            '                console.log("Actual data from datum():", actualData);',
            '                ',
            '                // Get series name from parent element',
            '                var parentData = d3.select(this.parentNode).datum();',
            '                console.log("Parent data:", parentData);',
            '                ',
            '                var value = actualData ? (actualData[1] - actualData[0]).toFixed(0) : "0";',
            '                var seriesName = parentData ? parentData.key : "Unknown";',
            '                ',
            '                // Get category name from the data array index',
            '                var dataIndex = parentData ? parentData.index : 0;',
            '                var categoryName = data[dataIndex] ? data[dataIndex].category : "Unknown";',
            '                ',
            '                console.log("Creating tooltip for:", categoryName, seriesName, value);',
            '                ',
            '                // Get mouse coordinates using D3.js v5 method',
            '                var mouseCoords = d3.mouse(d3.select("body").node());',
            '                console.log("D3 mouse coordinates:", mouseCoords);',
            '                ',
            '                tooltip.html("<div style=\'text-align: center; font-weight: bold; margin-bottom: 5px;\'>" + categoryName + "</div><div style=\'display: flex; align-items: center; justify-content: center; gap: 5px;\'><span style=\'display: inline-block; width: 10px; height: 10px; background-color: " + z(seriesName) + "; border-radius: 2px;\'></span><span>" + seriesName + "</span><span style=\'font-weight: bold; margin-left: 5px;\'>" + value + "</span></div>")',
            '                    .style("left", (mouseCoords[0] + 10) + "px")',
            '                    .style("top", (mouseCoords[1] - 10) + "px")',
            '                    .style("opacity", 1);',
            '                ',
            '                console.log("Tooltip final state:", tooltip.node().outerHTML);',
            '            })',
            '            .on("mouseout", function(event, d) {',
            '                d3.select(this)',
            '                    .style("opacity", 0.9)',
            '                    .style("stroke-width", "1px");',
            '                ',
            '                // Hide tooltip',
            '                d3.selectAll(".d3-tooltip").remove();',
            '            })',
            '            .transition()',
            '            .duration(750)',
            '            .attr("y", function(d) { return y(d[1]); })',
            '            .attr("height", function(d) { return y(d[0]) - y(d[1]); });',
            '        ',
            '        // Add axes with custom tick format for multi-line labels',
            '        g.append("g")',
            '            .attr("transform", "translate(30," + (height - 20) + ")")',
            '            .call(d3.axisBottom(x).tickFormat(function(d) {',
            '                var parts = d.split(" ");',
            '                if (parts.length >= 2) {',
            '                    return parts[0] + "\\n" + parts[1];',
            '                }',
            '                return d;',
            '            }))',
            '            .style("font-size", "12px")',
            '            .style("color", "#666");',
            '        ',
            '        // Format tick labels to display on multiple lines',
            '        g.selectAll(".tick text")',
            '            .each(function(d) {',
            '                var text = d3.select(this);',
            '                var words = text.text().split("\\n");',
            '                text.text(null);',
            '                words.forEach(function(word, i) {',
            '                    text.append("tspan")',
            '                        .attr("x", 0)',
            '                        .attr("dy", i === 0 ? "-0.3em" : "1em")',
            '                        .text(word);',
            '                });',
            '            });',
            '        ',
            '        g.append("g")',
            '            .attr("transform", "translate(30, 0)")',
            '            .call(d3.axisLeft(y))',
            '            .style("font-size", "12px")',
            '            .style("color", "#666");',
            '        ',
            '        // Add grid lines',
            '        g.append("g")',
            '            .attr("class", "grid")',
            '            .attr("transform", "translate(30," + (height - 20) + ")")',
            '            .call(d3.axisBottom(x)',
            '                .tickSize(-(height - 20))',
            '                .tickFormat("")',
            '            )',
            '            .style("stroke-dasharray", "3,3")',
            '            .style("opacity", 0.3);',
            '        ',
            '        g.append("g")',
            '            .attr("class", "grid")',
            '            .attr("transform", "translate(30, 0)")',
            '            .call(d3.axisLeft(y)',
            '                .tickSize(-(width - 30))',
            '                .tickFormat("")',
            '            )',
            '            .style("stroke-dasharray", "3,3")',
            '            .style("opacity", 0.3);',
            '    } catch (error) {',
            '        console.error("Error creating stacked bar chart:", error);',
            '    }',
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
        return [
            'd3' => 'https://d3js.org/d3.v5.min.js'
        ];
    }
    
    /**
     * Get CSS files required by the D3 chart
     * 
     * @return array|null Array of CSS file paths or null
     */
    public function getCssFiles()
    {
        return null; // D3.js doesn't have a CSS file
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
        echo "<pre>";
        /*print_r($data);
        echo "</pre>";
        exit;*/
        $defaultOptions = [
            'chartType' => 'stacked-bar',
            'title' => 'Stacked Bar Chart',
            'width' => '100%',
            'height' => 500,
            'colors' => [
                '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
                '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
            ],
            'margin' => [
                'top' => 10,
                'right' => 10,
                'bottom' => 40,
                'left' => 50
            ],
            'showLegend' => true,
            'showTitle' => true
        ];
        
        $mergedOptions = array_merge($defaultOptions, $options);
        
        $chart = new self([
            'data' => $data
        ], $mergedOptions);
        
        // Set chart type explicitly
        $chart->chartType = 'stacked-bar';
        
        // Ensure data is set
        $chart->data = $data;
        
        // Initialize the chart to process data
        $chart->initMe();
        
        // Debug: Log final data state
        error_log('D3Charts createStackedBarChart - Final data: ' . print_r($chart->data, true));
        error_log('D3Charts createStackedBarChart - Final processed data: ' . print_r($chart->processedData, true));
        
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
            'type' => $this->chartType ?: 'stacked-bar',
            'd3Version' => $this->d3Version,
            'width' => $this->options['width'] ?? '100%',
            'height' => $this->options['height'] ?? 500,
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
            'duration' => $this->options['duration'] ?? 750,
            // Yeni özellikler
            'showTooltip' => $this->options['showTooltip'] ?? true,
            'showLegend' => $this->options['showLegend'] ?? true,
            'legendPosition' => $this->options['legendPosition'] ?? 'top-right', // top-right, top-left, bottom-right, bottom-left
            'tooltipFormat' => $this->options['tooltipFormat'] ?? 'value', // value, percentage, custom
            'customTooltip' => $this->options['customTooltip'] ?? null, // Custom tooltip function
            'axisLabels' => $this->options['axisLabels'] ?? [
                'x' => '',
                'y' => ''
            ],
            'tickFormat' => $this->options['tickFormat'] ?? [
                'x' => null, // Custom x-axis tick format function
                'y' => null  // Custom y-axis tick format function
            ],
            'interactive' => $this->options['interactive'] ?? true,
            'hoverEffects' => $this->options['hoverEffects'] ?? true,
            'transitionDuration' => $this->options['transitionDuration'] ?? 300,
            'legendStyle' => $this->options['legendStyle'] ?? [
                'fontSize' => '12px',
                'fontFamily' => 'Arial, sans-serif'
            ],
            'tooltipStyle' => $this->options['tooltipStyle'] ?? [
                'backgroundColor' => 'rgba(0,0,0,0.8)',
                'color' => 'white',
                'borderRadius' => '4px',
                'padding' => '8px',
                'fontSize' => '12px'
            ]
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
}
?>
