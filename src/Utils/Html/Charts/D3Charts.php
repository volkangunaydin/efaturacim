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
                'top' => 20,
                'right' => 20,
                'bottom' => 30,
                'left' => 40
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
        
        // Add JavaScript files
        $jsFiles = $this->getJsFiles();
        if ($jsFiles) {
            foreach ($jsFiles as $jsFile) {
                $html .= '<script src="' . $jsFile . '"></script>' . "\n";
            }
        }
        
        // Add chart container
        $html .= '<div id="' . $this->chartId . '" class="' . $this->containerClass . ' d3-chart" ';
        $html .= 'style="width: ' . $this->dimensions['width'] . 'px; height: ' . $this->dimensions['height'] . 'px;">';
        
        if ($this->showTitle && !empty($this->title)) {
            $html .= '<h3 class="chart-title">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        $html .= '<svg id="' . $this->chartId . '_svg" class="d3-svg" width="' . $this->dimensions['width'] . '" height="' . $this->dimensions['height'] . '"></svg>';
        
        if ($this->showLegend) {
            $html .= '<div class="chart-legend" id="' . $this->chartId . '_legend"></div>';
        }
        
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
            '// Debug: Log data for troubleshooting',
            'console.log("Chart Data:", chartData_' . $this->chartId . ');',
            'console.log("Chart Config:", chartConfig_' . $this->chartId . ');',
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
            '// D3 Chart Functions for ' . $this->chartId,
            'function createD3Chart_' . $this->chartId . '(containerId, data, config, options) {',
            '    try {',
            '        var container = d3.select("#" + containerId);',
            '        var svg = container.select("svg");',
            '        ',
            '        // Clear previous content',
            '        svg.selectAll("*").remove();',
            '        ',
            '        // Set SVG dimensions',
            '        svg.attr("width", config.width)',
            '           .attr("height", config.height);',
            '        ',
            '        // Create chart based on type',
            '        switch(config.type) {',
            '            case "stacked-bar":',
            '                createStackedBarChart_' . $this->chartId . '(svg, data, config, options);',
            '                break;',
            '            case "line":',
            '                createLineChart_' . $this->chartId . '(svg, data, config, options);',
            '                break;',
            '            case "bar":',
            '                createBarChart_' . $this->chartId . '(svg, data, config, options);',
            '                break;',
            '            case "pie":',
            '                createPieChart_' . $this->chartId . '(svg, data, config, options);',
            '                break;',
            '            case "area":',
            '                createAreaChart_' . $this->chartId . '(svg, data, config, options);',
            '                break;',
            '            default:',
            '                console.log("Chart type not implemented:", config.type);',
            '        }',
            '    } catch (error) {',
            '        console.error("Error in createD3Chart:", error);',
            '    }',
            '}',
            '',
            '// Stacked Bar Chart function for ' . $this->chartId,
            'function createStackedBarChart_' . $this->chartId . '(svg, data, config, options) {',
            '    try {',
            '        console.log("Creating stacked bar chart with data:", data);',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '        var width = config.width - margin.left - margin.right;',
            '        var height = config.height - margin.top - margin.bottom;',
            '        ',
            '        var g = svg.append("g")',
            '            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");',
            '        ',
            '        // Create scales',
            '        var x = d3.scaleBand()',
            '            .range([0, width])',
            '            .padding(0.1);',
            '        ',
            '        var y = d3.scaleLinear()',
            '            .range([height, 0]);',
            '        ',
            '        var z = d3.scaleOrdinal()',
            '            .range(config.colors || d3.schemeCategory10);',
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
            '        // Add bars',
            '        g.append("g")',
            '            .selectAll("g")',
            '            .data(series)',
            '            .enter().append("g")',
            '            .attr("fill", function(d) { return z(d.key); })',
            '            .selectAll("rect")',
            '            .data(function(d) { return d; })',
            '            .enter().append("rect")',
            '            .attr("x", function(d) { return x(d.data.category); })',
            '            .attr("y", function(d) { return y(d[1]); })',
            '            .attr("height", function(d) { return y(d[0]) - y(d[1]); })',
            '            .attr("width", x.bandwidth());',
            '        ',
            '        // Add axes',
            '        g.append("g")',
            '            .attr("transform", "translate(0," + height + ")")',
            '            .call(d3.axisBottom(x));',
            '        ',
            '        g.append("g")',
            '            .call(d3.axisLeft(y));',
            '    } catch (error) {',
            '        console.error("Error creating stacked bar chart:", error);',
            '    }',
            '}',
            '',
            '// Line Chart function for ' . $this->chartId,
            'function createLineChart_' . $this->chartId . '(svg, data, config, options) {',
            '    try {',
            '        console.log("Creating line chart with data:", data);',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '        var width = config.width - margin.left - margin.right;',
            '        var height = config.height - margin.top - margin.bottom;',
            '        ',
            '        var g = svg.append("g")',
            '            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");',
            '        ',
            '        // Create scales',
            '        var x = d3.scalePoint()',
            '            .range([0, width])',
            '            .padding(0.5);',
            '        ',
            '        var y = d3.scaleLinear()',
            '            .range([height, 0]);',
            '        ',
            '        var z = d3.scaleOrdinal()',
            '            .range(config.colors || d3.schemeCategory10);',
            '        ',
            '        // Process data for line chart',
            '        var keys = Object.keys(data[0]).filter(function(key) { return key !== "category"; });',
            '        ',
            '        // Set domains',
            '        x.domain(data.map(function(d) { return d.category; }));',
            '        y.domain([0, d3.max(data, function(d) { return d3.max(keys, function(key) { return d[key]; }); })]).nice();',
            '        z.domain(keys);',
            '        ',
            '        // Create line generator',
            '        var line = d3.line()',
            '            .x(function(d) { return x(d.category); })',
            '            .y(function(d) { return y(d.value); })',
            '            .curve(d3.curveMonotoneX);',
            '        ',
            '        // Add lines',
            '        keys.forEach(function(key) {',
            '            var lineData = data.map(function(d) { return {category: d.category, value: d[key]}; });',
            '            ',
            '            g.append("path")',
            '                .datum(lineData)',
            '                .attr("fill", "none")',
            '                .attr("stroke", z(key))',
            '                .attr("stroke-width", 2)',
            '                .attr("d", line);',
            '            ',
            '            // Add dots',
            '            g.selectAll(".dot-" + key)',
            '                .data(lineData)',
            '                .enter().append("circle")',
            '                .attr("class", "dot-" + key)',
            '                .attr("cx", function(d) { return x(d.category); })',
            '                .attr("cy", function(d) { return y(d.value); })',
            '                .attr("r", 4)',
            '                .attr("fill", z(key));',
            '        });',
            '        ',
            '        // Add axes',
            '        g.append("g")',
            '            .attr("transform", "translate(0," + height + ")")',
            '            .call(d3.axisBottom(x));',
            '        ',
            '        g.append("g")',
            '            .call(d3.axisLeft(y));',
            '    } catch (error) {',
            '        console.error("Error creating line chart:", error);',
            '    }',
            '}',
            '',
            '// Bar Chart function for ' . $this->chartId,
            'function createBarChart_' . $this->chartId . '(svg, data, config, options) {',
            '    try {',
            '        console.log("Creating bar chart with data:", data);',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '        var width = config.width - margin.left - margin.right;',
            '        var height = config.height - margin.top - margin.bottom;',
            '        ',
            '        var g = svg.append("g")',
            '            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");',
            '        ',
            '        // Create scales',
            '        var x = d3.scaleBand()',
            '            .range([0, width])',
            '            .padding(0.1);',
            '        ',
            '        var y = d3.scaleLinear()',
            '            .range([height, 0]);',
            '        ',
            '        var z = d3.scaleOrdinal()',
            '            .range(config.colors || d3.schemeCategory10);',
            '        ',
            '        // Process data for bar chart',
            '        var keys = Object.keys(data[0]).filter(function(key) { return key !== "category"; });',
            '        ',
            '        // Set domains',
            '        x.domain(data.map(function(d) { return d.category; }));',
            '        y.domain([0, d3.max(data, function(d) { return d3.max(keys, function(key) { return d[key]; }); })]).nice();',
            '        z.domain(keys);',
            '        ',
            '        // Add bars',
            '        keys.forEach(function(key, index) {',
            '            g.selectAll(".bar-" + key)',
            '                .data(data)',
            '                .enter().append("rect")',
            '                .attr("class", "bar-" + key)',
            '                .attr("x", function(d) { return x(d.category) + (x.bandwidth() / keys.length) * index; })',
            '                .attr("y", function(d) { return y(d[key]); })',
            '                .attr("width", x.bandwidth() / keys.length)',
            '                .attr("height", function(d) { return height - y(d[key]); })',
            '                .attr("fill", z(key));',
            '        });',
            '        ',
            '        // Add axes',
            '        g.append("g")',
            '            .attr("transform", "translate(0," + height + ")")',
            '            .call(d3.axisBottom(x));',
            '        ',
            '        g.append("g")',
            '            .call(d3.axisLeft(y));',
            '    } catch (error) {',
            '        console.error("Error creating bar chart:", error);',
            '    }',
            '}',
            '',
            '// Pie Chart function for ' . $this->chartId,
            'function createPieChart_' . $this->chartId . '(svg, data, config, options) {',
            '    try {',
            '        console.log("Creating pie chart with data:", data);',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '        var width = config.width - margin.left - margin.right;',
            '        var height = config.height - margin.top - margin.bottom;',
            '        var radius = Math.min(width, height) / 2;',
            '        ',
            '        var g = svg.append("g")',
            '            .attr("transform", "translate(" + (width / 2 + margin.left) + "," + (height / 2 + margin.top) + ")");',
            '        ',
            '        // Create color scale',
            '        var color = d3.scaleOrdinal()',
            '            .range(config.colors || d3.schemeCategory10);',
            '        ',
            '        // Create pie generator',
            '        var pie = d3.pie()',
            '            .value(function(d) { return d.value; })',
            '            .sort(null);',
            '        ',
            '        // Create arc generator',
            '        var arc = d3.arc()',
            '            .innerRadius(0)',
            '            .outerRadius(radius);',
            '        ',
            '        // Process data for pie chart',
            '        var pieData = data.map(function(d) {',
            '            return {',
            '                label: d.category,',
            '                value: d.value || d.Sales || d.Marketing || d.Development || 0',
            '            };',
            '        });',
            '        ',
            '        // Set color domain',
            '        color.domain(pieData.map(function(d) { return d.label; }));',
            '        ',
            '        // Add pie slices',
            '        var path = g.selectAll("path")',
            '            .data(pie(pieData))',
            '            .enter().append("path")',
            '            .attr("d", arc)',
            '            .attr("fill", function(d) { return color(d.data.label); })',
            '            .attr("stroke", "white")',
            '            .style("stroke-width", "2px");',
            '        ',
            '        // Add labels',
            '        g.selectAll("text")',
            '            .data(pie(pieData))',
            '            .enter().append("text")',
            '            .text(function(d) { return d.data.label; })',
            '            .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })',
            '            .style("text-anchor", "middle")',
            '            .style("font-size", "12px")',
            '            .style("fill", "white");',
            '    } catch (error) {',
            '        console.error("Error creating pie chart:", error);',
            '    }',
            '}',
            '',
            '// Area Chart function for ' . $this->chartId,
            'function createAreaChart_' . $this->chartId . '(svg, data, config, options) {',
            '    try {',
            '        console.log("Creating area chart with data:", data);',
            '        if (!data || data.length === 0) {',
            '            console.error("No data provided for chart");',
            '            return;',
            '        }',
            '        ',
            '        var margin = config.margin || {top: 20, right: 20, bottom: 30, left: 40};',
            '        var width = config.width - margin.left - margin.right;',
            '        var height = config.height - margin.top - margin.bottom;',
            '        ',
            '        var g = svg.append("g")',
            '            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");',
            '        ',
            '        // Create scales',
            '        var x = d3.scalePoint()',
            '            .range([0, width])',
            '            .padding(0.5);',
            '        ',
            '        var y = d3.scaleLinear()',
            '            .range([height, 0]);',
            '        ',
            '        var z = d3.scaleOrdinal()',
            '            .range(config.colors || d3.schemeCategory10);',
            '        ',
            '        // Process data for area chart',
            '        var keys = Object.keys(data[0]).filter(function(key) { return key !== "category"; });',
            '        ',
            '        // Set domains',
            '        x.domain(data.map(function(d) { return d.category; }));',
            '        y.domain([0, d3.max(data, function(d) { return d3.max(keys, function(key) { return d[key]; }); })]).nice();',
            '        z.domain(keys);',
            '        ',
            '        // Create area generator',
            '        var area = d3.area()',
            '            .x(function(d) { return x(d.category); })',
            '            .y0(height)',
            '            .y1(function(d) { return y(d.value); })',
            '            .curve(d3.curveMonotoneX);',
            '        ',
            '        // Add areas',
            '        keys.forEach(function(key) {',
            '            var areaData = data.map(function(d) { return {category: d.category, value: d[key]}; });',
            '            ',
            '            g.append("path")',
            '                .datum(areaData)',
            '                .attr("fill", z(key))',
            '                .attr("opacity", 0.7)',
            '                .attr("d", area);',
            '        });',
            '        ',
            '        // Add axes',
            '        g.append("g")',
            '            .attr("transform", "translate(0," + height + ")")',
            '            .call(d3.axisBottom(x));',
            '        ',
            '        g.append("g")',
            '            .call(d3.axisLeft(y));',
            '    } catch (error) {',
            '        console.error("Error creating area chart:", error);',
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
     * Create a line chart
     * 
     * @param array $data Chart data
     * @param array $options Chart options
     * @return D3Charts Chart instance
     */
    public static function createLineChart($data, $options = [])
    {
        $defaultOptions = [
            'chartType' => 'line',
            'title' => 'Line Chart',
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
        
        $chart->chartType = 'line';
        $chart->data = $data;
        $chart->initMe();
        
        return $chart;
    }
    
    /**
     * Create a bar chart
     * 
     * @param array $data Chart data
     * @param array $options Chart options
     * @return D3Charts Chart instance
     */
    public static function createBarChart($data, $options = [])
    {
        $defaultOptions = [
            'chartType' => 'bar',
            'title' => 'Bar Chart',
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
        
        $chart->chartType = 'bar';
        $chart->data = $data;
        $chart->initMe();
        
        return $chart;
    }
    
    /**
     * Create a pie chart
     * 
     * @param array $data Chart data in format: [
     *     ['category' => 'A', 'value' => 30],
     *     ['category' => 'B', 'value' => 50],
     *     ['category' => 'C', 'value' => 20],
     *     ...
     * ]
     * @param array $options Chart options
     * @return D3Charts Chart instance
     */
    public static function createPieChart($data, $options = [])
    {
        $defaultOptions = [
            'chartType' => 'pie',
            'title' => 'Pie Chart',
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
        
        $chart->chartType = 'pie';
        $chart->data = $data;
        $chart->initMe();
        
        return $chart;
    }
    
    /**
     * Create an area chart
     * 
     * @param array $data Chart data
     * @param array $options Chart options
     * @return D3Charts Chart instance
     */
    public static function createAreaChart($data, $options = [])
    {
        $defaultOptions = [
            'chartType' => 'area',
            'title' => 'Area Chart',
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
        
        $chart->chartType = 'area';
        $chart->data = $data;
        $chart->initMe();
        
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
