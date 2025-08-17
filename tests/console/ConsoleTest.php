<?php

/**
 * Console Class Test Script
 * 
 * Simple test script to verify Console class functionality
 */

require_once __DIR__ . '/../../src/autoload.php';

use Efaturacim\Util\Utils\Console\Console;

class ConsoleTest
{
    public function testBasicFunctionality(): void
    {
        Console::print('=== Console Class Test ===', 'cyan');
        Console::print('');
        
        // Test basic printing
        Console::print('Testing basic print functionality...', 'green');
        Console::print('This should be red text', 'red');
        Console::print('This should be blue text', 'blue');
        Console::print('This should be yellow text', 'yellow');
        Console::print('');
        
        // Test inline printing
        Console::printInline('Testing inline print... ', 'green');
        Console::printInline('continued on same line', 'red');
        Console::print(''); // Add newline
        Console::print('');
        
        // Test title
        Console::title('Test Title', 'magenta', 'line', 50);
        Console::print('');
        
        // Test progress
        Console::print('Testing progress bar:');
        for ($i = 0; $i <= 100; $i += 20) {
            Console::printProgress($i, 'green');
            usleep(100000); // 0.1 seconds
        }
        Console::print(''); // Add newline
        Console::print('');
        
        // Test alerts
        Console::success('Success test message');
        Console::error('Error test message');
        Console::warning('Warning test message');
        Console::info('Info test message');
        Console::print('');
        
        // Test simple messages
        Console::successMessage('Simple success message');
        Console::errorMessage('Simple error message');
        Console::warningMessage('Simple warning message');
        Console::infoMessage('Simple info message');
        Console::print('');
        
        // Test window
        Console::window('Test Window', function() {
            Console::print('This is content inside a window', 'green');
            Console::print('Multiple lines are supported', 'blue');
        }, 'cyan', 50);
        Console::print('');
        
        // Test Color class integration
        Console::print('Testing Color class integration:');
        Console::print('Testing crimson color', 'crimson');
        Console::print('Testing navy color', 'navy');
        Console::print('Testing kirmizi (Turkish red)', 'kirmizi');
        Console::print('Testing mavi (Turkish blue)', 'mavi');
        Console::print('');
        
        Console::print('=== Test Complete ===', 'green');
    }
    
    public function testParameterHandling(): void
    {
        Console::print('=== Parameter Handling Test ===', 'cyan');
        Console::print('');
        
        $params = Console::getParameters();
        Console::print('All parameters: ' . implode(', ', $params), 'green');
        
        $namedParams = Console::getNamedParameters();
        if (!empty($namedParams)) {
            Console::print('Named parameters found:', 'yellow');
            foreach ($namedParams as $key => $value) {
                Console::print("  {$key} = {$value}", 'cyan');
            }
        } else {
            Console::print('No named parameters found', 'yellow');
        }
        
        Console::print('');
    }
}

// Run tests
if (php_sapi_name() === 'cli') {
    $test = new ConsoleTest();
    $test->testBasicFunctionality();
    $test->testParameterHandling();
} else {
    echo "This test should be run from the command line.\n";
    echo "Usage: php ConsoleTest.php [options]\n";
}
?>
