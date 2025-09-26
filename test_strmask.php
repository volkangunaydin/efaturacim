<?php

require_once 'src/autoload.php';

use Efaturacim\Util\Utils\String\StrMask;
use Efaturacim\Util\Utils\Options;

echo "Testing StrMask::smart method\n";
echo "============================\n\n";

// Test cases
$testCases = [
    // Basic tests
    ['input' => 'Hello World', 'options' => [], 'expected' => 'Hel***orld'],
    ['input' => 'John Doe', 'options' => [], 'expected' => 'Jo***oe'],
    ['input' => 'Test', 'options' => [], 'expected' => 'T**t'],
    ['input' => 'AB', 'options' => [], 'expected' => '**'],
    ['input' => 'A', 'options' => [], 'expected' => '*'],
    
    // Custom char tests
    ['input' => 'Hello World', 'options' => ['char' => 'X'], 'expected' => 'HelXXXorld'],
    ['input' => 'Test', 'options' => ['char' => '#'], 'expected' => 'T##t'],
    
    // Custom maxChars tests
    ['input' => 'Hello World', 'options' => ['maxChars' => 5], 'expected' => 'Hel*****orld'],
    ['input' => 'Hello World', 'options' => ['maxChars' => 1], 'expected' => 'Hel*orld'],
    
    // Custom maxPercent tests
    ['input' => 'Hello World', 'options' => ['maxPercent' => 0.3], 'expected' => 'Hel***orld'],
    ['input' => 'Hello World', 'options' => ['maxPercent' => 0.8], 'expected' => 'He*******ld'],
    
    // Edge cases
    ['input' => '', 'options' => [], 'expected' => ''],
    ['input' => null, 'options' => [], 'expected' => ''],
    ['input' => '1234567890', 'options' => [], 'expected' => '123***890'],
];

foreach ($testCases as $i => $test) {
    $result = StrMask::smart($test['input'], $test['options']);
    $status = ($result === $test['expected']) ? '✓' : '✗';
    
    echo "Test " . ($i + 1) . ": $status\n";
    echo "Input: '" . $test['input'] . "'\n";
    echo "Options: " . json_encode($test['options']) . "\n";
    echo "Expected: '" . $test['expected'] . "'\n";
    echo "Got: '" . $result . "'\n";
    echo "---\n";
}

echo "\nTesting with Turkish characters:\n";
echo "================================\n";

$turkishTests = [
    ['input' => 'Ahmet Yılmaz', 'options' => [], 'expected' => 'Ahm***lmaz'],
    ['input' => 'Özlem Şahin', 'options' => [], 'expected' => 'Özl***hin'],
    ['input' => 'Çağlar Ünal', 'options' => [], 'expected' => 'Çağ***nal'],
];

foreach ($turkishTests as $i => $test) {
    $result = StrMask::smart($test['input'], $test['options']);
    $status = ($result === $test['expected']) ? '✓' : '✗';
    
    echo "Turkish Test " . ($i + 1) . ": $status\n";
    echo "Input: '" . $test['input'] . "'\n";
    echo "Expected: '" . $test['expected'] . "'\n";
    echo "Got: '" . $result . "'\n";
    echo "---\n";
}

echo "\nAll tests completed!\n";
