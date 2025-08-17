# Console Class Demo and Test Scripts

This folder contains demonstration and test scripts for the Console utility class.

## Files

- **`ConsoleDemo.php`** - Comprehensive demonstration of all Console class features
- **`ConsoleTest.php`** - Simple test script to verify basic functionality
- **`README.md`** - This documentation file

## Usage

### Running the Demo Script

The demo script showcases all features of the Console class:

```bash
# Run the full demo
php ConsoleDemo.php

# Run with parameters
php ConsoleDemo.php --help color=red

# Run with named parameters
php ConsoleDemo.php name=test color=blue
```

### Running the Test Script

The test script verifies basic Console class functionality:

```bash
# Run basic tests
php ConsoleTest.php

# Run with parameters
php ConsoleTest.php --verbose color=green
```

## Features Demonstrated

### ConsoleDemo.php

1. **Basic Printing**
   - Standard text output
   - Colored text output
   - Inline printing without newlines

2. **Title Styles**
   - Simple, line, double, stars, equals, dashes, box styles
   - Different colors for each style

3. **Progress Bars**
   - Animated progress bars
   - Different colors for progress indicators

4. **Alert Boxes**
   - Success, error, warning, info alerts
   - Simple message variants
   - Text wrapping in alert boxes

5. **Window Style**
   - System information display
   - File system information
   - Custom content windows

6. **Color Integration**
   - Color class named colors
   - Turkish color names
   - Modern web colors
   - Hex color support

7. **Parameter Handling**
   - Command line parameters
   - Named parameters (key=value)
   - Parameter validation

8. **Input/Output**
   - User input collection
   - Message object creation
   - Interactive features

### ConsoleTest.php

1. **Basic Functionality**
   - Print methods
   - Color support
   - Title formatting
   - Progress bars
   - Alert boxes
   - Window content

2. **Parameter Handling**
   - Parameter retrieval
   - Named parameter parsing

## Color Support

The Console class supports:

### Built-in Colors
- `black`, `red`, `green`, `yellow`, `blue`, `magenta`, `cyan`, `white`, `default`

### Color Class Integration
- Modern web colors: `crimson`, `navy`, `forest_green`, `gold`, `violet`, `coral`, etc.
- Turkish colors: `kirmizi`, `mavi`, `yesil`, `sari`, `turuncu`, `mor`, `pembe`, etc.
- Any color from the Color class's `$NAMED_COLORS` array

## Title Styles

Available title styles:
- `simple` - Centered text
- `line` - Text with line borders
- `double` - Text with double line borders
- `stars` - Text with star borders
- `equals` - Text with equals borders
- `dashes` - Text with dash borders
- `box` - Text in a box

## Alert Types

Available alert types:
- `success` - Green success alert
- `error` - Red error alert
- `warning` - Yellow warning alert
- `info` - Cyan info alert

## Requirements

- PHP 7.4 or higher
- Command line interface (CLI)
- ANSI color support in terminal
- The Console and Color classes from the project

## Examples

### Basic Usage
```php
use Efaturacim\Util\Utils\Console\Console;

Console::print('Hello World!', 'green');
Console::title('My Title', 'blue', 'box', 60);
Console::success('Operation completed!');
```

### Progress Bar
```php
for ($i = 0; $i <= 100; $i += 10) {
    Console::printProgress($i, 'green');
    usleep(100000); // 0.1 seconds
}
```

### Window Content
```php
Console::window('System Info', function() {
    Console::print('OS: ' . PHP_OS, 'green');
    Console::print('PHP: ' . PHP_VERSION, 'blue');
}, 'cyan', 50);
```

### Color Integration
```php
Console::print('Turkish red', 'kirmizi');
Console::print('Modern blue', 'navy');
Console::print('Web color', 'crimson');
```

## Notes

- These scripts must be run from the command line (CLI)
- Color support depends on your terminal's ANSI color capabilities
- The demo script is interactive and will wait for user input between sections
- The test script runs automatically without user interaction
