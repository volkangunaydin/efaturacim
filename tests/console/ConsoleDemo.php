<?php

/**
 * Console Class Demo Script
 * 
 * This script demonstrates all features of the Console class including:
 * - Basic printing with colors
 * - Title formatting with different styles
 * - Progress bars
 * - Alert boxes
 * - Window-style content
 * - Color integration with Color class
 * - Parameter handling
 * - Input/output operations
 */

require_once __DIR__ . '/../../src/autoload.php';

use Efaturacim\Util\Utils\Console\Console;

class ConsoleDemo
{
    public function run(): void
    {
        Console::clear();
        
        // Welcome message
        Console::title('Console Class Demo', 'cyan', 'box', 80);
        Console::print('Welcome to the Console class demonstration!', 'green');
        Console::print('This script showcases all the features of the Console utility class.', 'yellow');
        Console::print('');
        
        // Wait for user input to continue
        Console::waitForInput('Press Enter to continue...');
        
        // Demo sections
        $this->demoBasicPrinting();
        $this->demoTitleStyles();
        $this->demoProgressBars();
        $this->demoAlertBoxes();
        $this->demoWindowStyle();
        $this->demoColorIntegration();
        $this->demoParameterHandling();
        $this->demoInputOutput();
        
        // Final message
        Console::print('');
        Console::title('Demo Complete!', 'green', 'stars', 80);
        Console::successMessage('All Console class features have been demonstrated successfully!');
        Console::print('Thank you for using the Console utility class!', 'cyan');
    }
    
    private function demoBasicPrinting(): void
    {
        Console::title('Basic Printing Demo', 'blue', 'line', 60);
        
        Console::print('Standard text output');
        Console::print('Colored text output', 'red');
        Console::print('Another colored text', 'green');
        Console::print('Yellow warning text', 'yellow');
        Console::print('Cyan info text', 'cyan');
        Console::print('Magenta highlight text', 'magenta');
        
        Console::printInline('Inline text without newline... ');
        Console::printInline('continued on same line', 'green');
        Console::print(''); // Add newline
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoTitleStyles(): void
    {
        Console::title('Title Styles Demo', 'magenta', 'equals', 60);
        
        $styles = ['simple', 'line', 'double', 'stars', 'equals', 'dashes', 'box'];
        $colors = ['red', 'green', 'yellow', 'blue', 'magenta', 'cyan'];
        
        foreach ($styles as $index => $style) {
            $color = $colors[$index % count($colors)];
            Console::title("Title Style: {$style}", $color, $style, 50);
            Console::print("This is the '{$style}' style with {$color} color", $color);
            Console::print('');
        }
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoProgressBars(): void
    {
        Console::title('Progress Bars Demo', 'green', 'line', 60);
        
        Console::print('Demonstrating progress bars...', 'cyan');
        Console::print('');
        
        // Simulate progress
        for ($i = 0; $i <= 100; $i += 10) {
            Console::printProgress($i, 'green');
            usleep(200000); // 0.2 seconds
        }
        Console::print(''); // Add newline after progress
        
        Console::print('Progress with different colors:');
        Console::printProgressLine(25, 'red');
        Console::printProgressLine(50, 'yellow');
        Console::printProgressLine(75, 'blue');
        Console::printProgressLine(100, 'green');
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoAlertBoxes(): void
    {
        Console::title('Alert Boxes Demo', 'yellow', 'line', 60);
        
        Console::print('Demonstrating different alert types...', 'cyan');
        Console::print('');
        
        // Success alert
        Console::success('Operation completed successfully! This is a longer message to demonstrate text wrapping in alert boxes.');
        Console::print('');
        
        // Error alert
        Console::error('An error occurred while processing the request. Please check the logs for more details.');
        Console::print('');
        
        // Warning alert
        Console::warning('This is a warning message. Please review your input before proceeding.');
        Console::print('');
        
        // Info alert
        Console::info('This is an informational message. The system is running normally.');
        Console::print('');
        
        // Simple messages
        Console::print('Simple message variants:');
        Console::successMessage('Success message without box');
        Console::errorMessage('Error message without box');
        Console::warningMessage('Warning message without box');
        Console::infoMessage('Info message without box');
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoWindowStyle(): void
    {
        Console::title('Window Style Demo', 'cyan', 'line', 60);
        
        Console::print('Demonstrating window-style content...', 'cyan');
        Console::print('');
        
        Console::window('System Information', function() {
            $this->printWindowLine('Operating System: ' . PHP_OS, 'green', 58);
            $this->printWindowLine('PHP Version: ' . PHP_VERSION, 'blue', 58);
            $this->printWindowLine('Memory Limit: ' . ini_get('memory_limit'), 'yellow', 58);
            $this->printWindowLine('Max Execution Time: ' . ini_get('max_execution_time') . ' seconds', 'magenta', 58);
        }, 'cyan', 60);
        
        Console::print('');
        
        Console::window('File System Info', function() {
            $this->printWindowLine('Current Directory: ' . getcwd(), 'green', 58);
            $this->printWindowLine('Available Space: ' . $this->formatBytes(disk_free_space('.'), 2), 'blue', 58);
            $this->printWindowLine('Total Space: ' . $this->formatBytes(disk_total_space('.'), 2), 'yellow', 58);
        }, 'green', 60);
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoColorIntegration(): void
    {
        Console::title('Color Class Integration Demo', 'magenta', 'line', 60);
        
        Console::print('Demonstrating Color class integration...', 'cyan');
        Console::print('');
        
        // Test with Color class named colors
        $colorNames = [
            'red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink',
            'turquoise', 'lime', 'brown', 'gray', 'black', 'white',
            'crimson', 'navy', 'forest_green', 'gold', 'violet', 'coral',
            'kirmizi', 'mavi', 'yesil', 'sari', 'turuncu', 'mor', 'pembe'
        ];
        
        Console::print('Testing Color class named colors:');
        foreach ($colorNames as $colorName) {
            Console::print("Testing color: {$colorName}", $colorName);
        }
        
        Console::print('');
        Console::print('Testing Turkish color names:');
        $turkishColors = ['kirmizi', 'mavi', 'yesil', 'sari', 'turuncu', 'mor', 'pembe', 'kahverengi', 'gri'];
        foreach ($turkishColors as $colorName) {
            Console::print("Türkçe renk: {$colorName}", $colorName);
        }
        
        Console::print('');
        Console::print('Testing modern web colors:');
        $modernColors = ['crimson', 'navy', 'forest_green', 'gold', 'violet', 'coral', 'teal', 'indigo'];
        foreach ($modernColors as $colorName) {
            Console::print("Modern color: {$colorName}", $colorName);
        }
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoParameterHandling(): void
    {
        Console::title('Parameter Handling Demo', 'blue', 'line', 60);
        
        Console::print('Demonstrating command line parameter handling...', 'cyan');
        Console::print('');
        
        // Get all parameters
        $params = Console::getParameters();
        Console::print('All parameters: ' . implode(', ', $params), 'green');
        
        // Get specific parameter
        $firstParam = Console::getParameter(1);
        if ($firstParam) {
            Console::print("First parameter: {$firstParam}", 'blue');
        }
        
        // Get named parameters
        $namedParams = Console::getNamedParameters();
        if (!empty($namedParams)) {
            Console::print('Named parameters:', 'yellow');
            foreach ($namedParams as $key => $value) {
                Console::print("  {$key} = {$value}", 'cyan');
            }
        } else {
            Console::print('No named parameters found (use key=value format)', 'yellow');
        }
        
        // Check for specific parameter
        if (Console::hasParameter('--help')) {
            Console::print('Help parameter detected!', 'green');
        }
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function demoInputOutput(): void
    {
        Console::title('Input/Output Demo', 'green', 'line', 60);
        
        Console::print('Demonstrating input/output operations...', 'cyan');
        Console::print('');
        
        // Get user input
        $name = Console::waitForInput('Please enter your name: ');
        Console::print("Hello, {$name}! Welcome to the Console demo!", 'green');
        
        $age = Console::waitForInput('Please enter your age: ');
        Console::print("You are {$age} years old.", 'blue');
        
        // Create messages
        $systemMsg = Console::createSystemMessage('This is a system message');
        $userMsg = Console::createUserMessage('This is a user message');
        $assistantMsg = Console::createAssistantMessage('This is an assistant message');
        
        Console::print('');
        Console::print('Message objects created:', 'yellow');
        Console::print('System: ' . json_encode($systemMsg), 'cyan');
        Console::print('User: ' . json_encode($userMsg), 'green');
        Console::print('Assistant: ' . json_encode($assistantMsg), 'blue');
        
        Console::waitForInput('Press Enter to continue...');
    }
    
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    private function printWindowLine(string $text, string $color, int $width): void
    {
        $paddedText = str_pad($text, $width, ' ');
        Console::printInline('│  ' . $paddedText . '  │', $color);
        Console::print(''); // Add newline
    }
}

// Run the demo
if (php_sapi_name() === 'cli') {
    $demo = new ConsoleDemo();
    $demo->run();
} else {
    echo "This demo should be run from the command line.\n";
    echo "Usage: php ConsoleDemo.php [options]\n";
    echo "Example: php ConsoleDemo.php --help color=red\n";
}
