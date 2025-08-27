<?php

declare(strict_types=1);

namespace Efaturacim\Util\Utils\Console;

use Efaturacim\Util\Utils\Array\AssocArray;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StringSplitter;
use Efaturacim\Util\Utils\String\StrUtil;
use Vulcan\Base\Util\StringUtil\StrPercent;
use Vulcan\VResult;

/**
 * Console utilities 
 */
class Console
{
    /**
     * @var Options|null
     */
    protected $calculatedVars = null; 
    /**
     * Console colors constants.
     */
    public const COLOR_BLACK = 'black';
    public const COLOR_RED = 'red';
    public const COLOR_GREEN = 'green';
    public const COLOR_YELLOW = 'yellow';
    public const COLOR_BLUE = 'blue';
    public const COLOR_MAGENTA = 'magenta';
    public const COLOR_CYAN = 'cyan';
    public const COLOR_WHITE = 'white';
    public const COLOR_DEFAULT = 'default';

    /**
     * Color codes for ANSI escape sequences.
     */
    private const COLOR_CODES = [
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'yellow' => '0;33',
        'blue' => '0;34',
        'magenta' => '0;35',
        'cyan' => '0;36',
        'white' => '0;37',
        'default' => '0',
    ];
    public static $defaultBarLength = 50;
    /**
     * Print a message to the console with optional color.
     */
    public static function print(string $message, ?string $color = null): void
    {
        $output = self::colorize($message, $color);
        echo $output . PHP_EOL;
    }
    public static function getIconStr($icon){
        if($icon=='ok' || $icon=='success' || $icon=="OK"){
            return '✓ ';
        }else if($icon=='error' || $icon=='failed' || $icon=='danger' || $icon=='err'){
            return '✗ ';
        }else if($icon=='warning' || $icon=='warn'){
            return '⚠ ';
        }else if($icon=='info' || $icon=='info'){
            return 'ℹ ';
        }else if($icon=='question' || $icon=='q'){
            return '? ';
        }
        return ''.$icon;
    }
    public static function printSuccess(string $message, ?string $icon = null): void    
    {
        $iconStr = self::getIconStr($icon);
        self::print($iconStr.$message, self::COLOR_GREEN);
    }
    public static function printError(string $message, ?string $icon = null): void
    {
        $iconStr = self::getIconStr($icon);
        self::print($iconStr.$message, self::COLOR_RED);
    }

    /**
     * Print a message without newline with optional color.
     */
    public static function printInline(string $message, ?string $color = null): void
    {
        $output = self::colorize($message, $color);
        echo $output;
    }

    /**
     * Print a title with optional styling and borders.
     */
    public static function title(
        $title,
        ?string $color = null,
        string $style = 'simple',
        int $width = 80
    ): void {
        if(is_array($title)){
            $title = implode("\n", $title);
        }
        $title = trim($title);
        $titleLength = mb_strlen($title);
        
        switch ($style) {
            case 'box':
                self::printTitleBox($title, $color, $width);
                break;
            case 'line':
                self::printTitleLine($title, $color, $width);
                break;
            case 'double':
                self::printTitleDouble($title, $color, $width);
                break;
            case 'stars':
                self::printTitleStars($title, $color, $width);
                break;
            case 'equals':
                self::printTitleEquals($title, $color, $width);
                break;
            case 'dashes':
                self::printTitleDashes($title, $color, $width);
                break;
            case 'simple':
            default:
                self::printTitleSimple($title, $color, $width);
                break;
        }
    }

    /**
     * Print a simple title with centered text.
     */
    private static function printTitleSimple(string $title, ?string $color, int $width): void
    {
        $titleLength = mb_strlen($title);
        
        // Handle titles that are too long
        if ($titleLength > $width) {
            // Truncate the title to fit
            $title = mb_substr($title, 0, $width - 3) . '...';
            $titleLength = mb_strlen($title);
        }
        
        $padding = max(0, ($width - $titleLength) / 2);
        $leftPadding = (int) $padding;
        $rightPadding = max(0, $width - $titleLength - $leftPadding);
        
        $formattedTitle = str_repeat(' ', $leftPadding) . $title . str_repeat(' ', $rightPadding);
        self::print($formattedTitle, $color);
    }

    /**
     * Print a title with line borders.
     */
    private static function printTitleLine(string $title, ?string $color, int $width): void
    {
        $line = str_repeat('─', $width);
        self::print($line, $color);
        self::printTitleSimple($title, $color, $width);
        self::print($line, $color);
    }

    /**
     * Print a title with double line borders.
     */
    private static function printTitleDouble(string $title, ?string $color, int $width): void
    {
        $line = str_repeat('═', $width);
        self::print($line, $color);
        self::printTitleSimple($title, $color, $width);
        self::print($line, $color);
    }

    /**
     * Print a title with star borders.
     */
    private static function printTitleStars(string $title, ?string $color, int $width): void
    {
        $line = str_repeat('*', $width);
        self::print($line, $color);
        self::printTitleSimple($title, $color, $width);
        self::print($line, $color);
    }

    /**
     * Print a title with equals borders.
     */
    private static function printTitleEquals(string $title, ?string $color, int $width): void
    {
        $line = str_repeat('=', $width);
        self::print($line, $color);
        self::printTitleSimple($title, $color, $width);
        self::print($line, $color);
    }

    /**
     * Print a title with dash borders.
     */
    private static function printTitleDashes(string $title, ?string $color, int $width): void
    {
        $line = str_repeat('-', $width);
        self::print($line, $color);
        self::printTitleSimple($title, $color, $width);
        self::print($line, $color);
    }

    /**
     * Print a title with box borders.
     */
    private static function printTitleBox(string $title, ?string $color, int $width): void
    {
        $titleLength = mb_strlen($title);
        
        // Handle titles that are too long for the box
        if ($titleLength > $width - 4) {
            // Truncate the title to fit
            $maxTitleLength = $width - 4;
            $title = mb_substr($title, 0, $maxTitleLength - 3) . '...';
            $titleLength = mb_strlen($title);
        }
        
        $padding = max(0, ($width - $titleLength - 4) / 2);
        $leftPadding = (int) $padding;
        $rightPadding = max(0, $width - $titleLength - 4 - $leftPadding);
        
        // Calculate the actual width needed for the box
        $boxWidth = $leftPadding + $titleLength + $rightPadding + 4; // +4 for borders and padding
        
        $topLine = '┌' . str_repeat('─', $boxWidth - 2) . '┐';
        $titleLine = '│' . str_repeat(' ', $leftPadding+2) . $title . str_repeat(' ', $rightPadding) . '│';
        $bottomLine = '└' . str_repeat('─', $boxWidth - 2) . '┘';
        
        self::print($topLine, $color);
        self::print($titleLine, $color);
        self::print($bottomLine, $color);
    }

    /**
     * Print a window-style title with borders.
     */
    public static function window(
        string $title,
        callable $content,
        ?string $color = null,
        int $width = 80
    ): void {
        $titleLength = mb_strlen($title);
        
        // Handle titles that are too long for the window
        if ($titleLength > $width - 4) {
            // Truncate the title to fit
            $maxTitleLength = $width - 4;
            $title = mb_substr($title, 0, $maxTitleLength - 3) . '...';
            $titleLength = mb_strlen($title);
        }
        
        $padding = max(0, ($width - $titleLength - 4) / 2);
        $leftPadding = (int) $padding;
        $rightPadding = max(0, $width - $titleLength - 4 - $leftPadding);
        
        // Calculate the total width of the window
        $windowWidth = $leftPadding + $titleLength + 2 + $rightPadding; // +2 for spaces around title
        
        // Top border
        $topLine = '┌' . str_repeat('─', $leftPadding) . ' ' . $title . ' ' . str_repeat('─', $rightPadding) . '┐';
        self::print($topLine, $color);
        
        // Content with side borders
        $content();
        
        // Bottom border - should match the width of the top border
        $bottomLine = '└' . str_repeat('─', $windowWidth) . '┘';
        self::print($bottomLine, $color);
    }

    /**
     * Print a centered title with custom border character.
     */
    public static function titleWithBorder(
        string $title,
        string $borderChar = '=',
        ?string $color = null,
        int $width = 80
    ): void {
        $titleLength = mb_strlen($title);
        
        // Handle titles that are too long
        if ($titleLength > $width - 4) {
            // Truncate the title to fit
            $maxTitleLength = $width - 4;
            $title = mb_substr($title, 0, $maxTitleLength - 3) . '...';
            $titleLength = mb_strlen($title);
        }
        
        $padding = max(0, ($width - $titleLength - 4) / 2);
        $leftPadding = (int) $padding;
        $rightPadding = max(0, $width - $titleLength - 4 - $leftPadding);
        
        $line = str_repeat($borderChar, $width);
        self::print($line, $color);
        self::print(str_repeat($borderChar, $leftPadding) . ' ' . $title . ' ' . str_repeat($borderChar, $rightPadding), $color);
        self::print($line, $color);
    }

    /**
     * Print progress percentage with optional color.
     */
    public static function printProgress($percent, ?string $color = null,$strExtra=""): void
    {
        $percent = (int)round($percent);
        $bar = self::createProgressBar($percent);
        $message = sprintf('Progress: %s %d%%', $bar, $percent);
        self::printInline($message.$strExtra, $color);
        echo "\r";
    }

    /**
     * Print progress percentage with newline.
     */
    public static function printProgressLine(int $percent, ?string $color = null): void
    {
        $bar = self::createProgressBar($percent);
        $message = sprintf('Progress: %s %d%%', $bar, $percent);
        self::print($message, $color);
    }

    /**
     * Get console parameters (command line arguments).
     */
    public static function getParameters(): array
    {
        return $_SERVER['argv'] ?? [];
    }

    /**
     * Get a specific parameter by index.
     */
    public static function getParameter(int $index): ?string
    {
        $params = self::getParameters();
        return $params[$index] ?? null;
    }

    /**
     * Get named parameters (key=value format).
     */
    public static function getNamedParameters(): array
    {
        $params = self::getParameters();
        $named = [];

        foreach ($params as $param) {
            if (strpos($param, '=') !== false) {
                [$key, $value] = explode('=', $param, 2);
                $named[$key] = $value;
            }
        }

        return $named;
    }

    /**
     * Get a specific named parameter.
     */
    public static function getNamedParameter(string $key): ?string
    {
        $named = self::getNamedParameters();
        return $named[$key] ?? null;
    }

    /**
     * Check if a parameter exists.
     */
    public static function hasParameter(string $key): bool
    {
        $params = self::getParameters();
        return in_array($key, $params, true);
    }

    /**
     * Clear the console screen.
     */
    public static function clear(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        } else {
            system('clear');
        }
    }

    /**
     * Wait for user input.
     */
    public static function waitForInput(string $prompt = 'Press Enter to continue...'): string
    {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    /**
     * Colorize text with ANSI escape codes.
     */
    private static function colorize(string $text, ?string $color): string
    {
        if ($color === null) {
            return $text;
        }

        // First check console colors
        if (isset(self::COLOR_CODES[$color])) {
            $code = self::COLOR_CODES[$color];
            return "\033[{$code}m{$text}\033[0m";
        }

        // If not found in console colors, try to find in Color class
        try {
            $colorClass = '\\Efaturacim\\Util\\Utils\\Color\\Color';
            if (class_exists($colorClass)) {
                $reflection = new \ReflectionClass($colorClass);
                $namedColors = $reflection->getStaticPropertyValue('NAMED_COLORS');
                
                $normalizedName = strtolower(trim($color));
                
                // Check exact match
                if (isset($namedColors[$normalizedName])) {
                    return self::colorizeWithHex($text, $namedColors[$normalizedName]);
                }
                
                // Check with underscores removed
                $noUnderscoreName = str_replace('_', '', $normalizedName);
                if (isset($namedColors[$noUnderscoreName])) {
                    return self::colorizeWithHex($text, $namedColors[$noUnderscoreName]);
                }
                
                // Check with spaces removed
                $noSpaceName = str_replace(' ', '', $normalizedName);
                if (isset($namedColors[$noSpaceName])) {
                    return self::colorizeWithHex($text, $namedColors[$noSpaceName]);
                }
            }
        } catch (\Exception $e) {
            // If Color class is not available, just return text without color
        }

        // If color not found anywhere, return text without color
        return $text;
    }

    /**
     * Colorize text with hex color using ANSI 256 color codes.
     * 
     * @param string $text Text to colorize
     * @param string $hexColor Hex color code (without #)
     * @return string Colorized text
     */
    private static function colorizeWithHex(string $text, string $hexColor): string
    {
        // Convert hex to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        
        // Convert RGB to ANSI 256 color code
        $ansiCode = self::rgbToAnsi256($r, $g, $b);
        
        return "\033[38;5;{$ansiCode}m{$text}\033[0m";
    }

    /**
     * Convert RGB values to ANSI 256 color code.
     * 
     * @param int $r Red value (0-255)
     * @param int $g Green value (0-255)
     * @param int $b Blue value (0-255)
     * @return int ANSI 256 color code
     */
    private static function rgbToAnsi256(int $r, int $g, int $b): int
    {
        // Scale RGB values to 0-5 range
        $r = (int)round($r / 51);
        $g = (int)round($g / 51);
        $b = (int)round($b / 51);
        
        // Calculate ANSI 256 color code
        return 16 + ($r * 36) + ($g * 6) + $b;
    }

    /**
     * Create a progress bar string.
     */
    private static function createProgressBar(int $percent,$barLength=null): string
    {
        if(is_null($barLength)){
            $barLength = self::$defaultBarLength;
        }
        $percent = max(0, min(100, $percent));
        $filledLength = (int) round(($percent / 100) * $barLength);
        $emptyLength = $barLength - $filledLength;

        $bar = str_repeat('█', $filledLength);
        $bar .= str_repeat('░', $emptyLength);

        return "[{$bar}]";
    }

    /**
     * Create a message for chat.
     */
    public static function createMessage(string $role, string $content): array
    {
        return [
            'role' => $role,
            'content' => $content,
        ];
    }

    /**
     * Create a system message.
     */
    public static function createSystemMessage(string $content): array
    {
        return self::createMessage('system', $content);
    }

    /**
     * Create a user message.
     */
    public static function createUserMessage(string $content): array
    {
        return self::createMessage('user', $content);
    }

    /**
     * Create an assistant message.
     */
    public static function createAssistantMessage(string $content): array
    {
        return self::createMessage('assistant', $content);
    }

    /**
     * Print a success alert box.
     */
    public static function success($message, string $title = null, string $icon = null, int $width = 80): void
    {
        self::alert($message, 'success', $width, $title, $icon);
    }

    /**
     * Print an error alert box.
     */
    public static function error($message, string $title = null, string $icon = null, int $width = 80): void
    {
        self::alert($message, 'error', $width, $title, $icon);
    }

    /**
     * Print a warning alert box.
     */
    public static function warning($message, string $title = null, string $icon = null, int $width = 80): void
    {
        self::alert($message, 'warning', $width, $title, $icon);
    }

    /**
     * Print an info alert box.
     */
    public static function info($message, string $title = null, string $icon = null, int $width = 80): void
    {
        self::alert($message, 'info', $width, $title, $icon);
    }

    /**
     * Print an alert box with specified type.
     */
    private static function alert($message, string $type, int $width, string $title = null, string $icon = null): void
    {
        $config = self::getAlertConfig($type);
        $defaultIcon = $config['icon'];
        $color = $config['color'];
        $defaultTitle = $config['title'];

        // Use custom parameters if provided, otherwise use defaults
        $icon = $icon ?? $defaultIcon;
        $title = $title ?? $defaultTitle;
        if(!is_null($icon)){
            $icon = self::getIconStr($icon);
        }
        // Handle message as string or array
        $messageArray = [];
        if (is_array($message)) {
            $messageArray = $message;
        }else if (is_string($message)){
            $messageArray = StringSplitter::splitWithNewLine($message);
        }

        // Calculate available width for message (accounting for borders and padding)
        $availableWidth = $width - 6; // 2 borders + 2 spaces + 2 padding
        

        
        // Top border
        $topLine = '┌' . str_repeat('─', $width - 2) . '┐';
        self::print($topLine, $color);
        
        // Title line
        $titleLine = '│ ' . $icon . ' ' . $title . str_repeat(' ', $width - mb_strlen($title) - 6) . ' │';
        self::print($titleLine, $color);
        
        // Separator line
        $separatorLine = '│' . str_repeat('─', $width - 2) . '│';
        self::print($separatorLine, $color);
        
        foreach($messageArray as $message){
            $wrappedMessage = self::wrapText($message, $availableWidth);
            // Message lines
            foreach ($wrappedMessage as $line) {
                $paddedLine = str_pad($line, $availableWidth+2, ' ');
                $messageLine = '│ ' . $paddedLine . ' │';
                self::print($messageLine, $color);
            }    
        }
            // Wrap message to fit width
        
        // Bottom border
        $bottomLine = '└' . str_repeat('─', $width - 2) . '┘';
        self::print($bottomLine, $color);
    }

    /**
     * Get alert configuration based on type.
     */
    private static function getAlertConfig(string $type): array
    {
        $configs = [
            'success' => [
                'icon' => '✓',
                'color' => self::COLOR_GREEN,
                'title' => 'SUCCESS'
            ],
            'error' => [
                'icon' => '✗',
                'color' => self::COLOR_RED,
                'title' => 'ERROR'
            ],
            'warning' => [
                'icon' => '⚠',
                'color' => self::COLOR_YELLOW,
                'title' => 'WARNING'
            ],
            'info' => [
                'icon' => 'ℹ',
                'color' => self::COLOR_CYAN,
                'title' => 'INFO'
            ]
        ];

        return $configs[$type] ?? $configs['info'];
    }

    /**
     * Wrap text to fit within specified width.
     */
    private static function wrapText(string $text, int $width): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine ? ' ' : '') . $word;
            
            if (mb_strlen($testLine) <= $width) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
        }

        // If no lines were created, return the original text
        if (empty($lines)) {
            $lines[] = mb_substr($text, 0, $width);
        }

        return $lines;
    }

    /**
     * Print a simple success message.
     */
    public static function successMessage(string $message): void
    {
        self::print('✓ ' . $message, self::COLOR_GREEN);
    }

    /**
     * Print a simple error message.
     */
    public static function errorMessage(string $message): void
    {
        self::print('✗ ' . $message, self::COLOR_RED);
    }

    /**
     * Print a simple warning message.
     */
    public static function warningMessage(string $message): void
    {
        self::print('⚠ ' . $message, self::COLOR_YELLOW);
    }

    /**
     * Print a simple info message.
     */
    public static function infoMessage(string $message): void
    {
        self::print('ℹ ' . $message, self::COLOR_CYAN);
    }
    public static function printResult($result,$title=null,$icon=null,$printAttributes=false,$width=80){
        if($result instanceof SimpleResult || $result instanceof VResult){
            
            if($printAttributes && count($result->attributes)>0){
                self::alert(AssocArray::printKeyValue($result->attributes),"warning",$width,$title);
            }
            if($result->isOK()){
                self::printSuccess($title,$icon??"ok");
            }else{
                self::printError($title,$icon??"error");
            }
            foreach($result->messages  as $k=>$v){                
                $v["text"]  = StrUtil::toEng(@$v["text"]);
                if(in_array(@$v["type"],array("error","danger"))){
                    self::error(@$v["text"],$icon);
                }else if(in_array(@$v["type"],array("succ","success"))){
                    self::success(@$v["text"],$icon);
                }else if(in_array(@$v["type"],array("warn","warning"))){
                    self::warning(@$v["text"],$icon);
                }else{
                    self::info(@$v["text"],$icon);
                }
            }
        }
    }
    public static function getHostName(){
        return gethostname();
    }
    public static function clearScreen($rowSize=60){
        for($i=1;$i<=$rowSize;$i++){
            self::print('');
        }
    }    
    public static function printProgressFor($index,$totalCount,$mod=0,$decimal=2){
        if($mod<=1 || ($index%$mod)==0){
            if($totalCount>0){
                $percent = NumberUtil::asCleanNumber((100*$index)/$totalCount,$decimal,true);
                self::printProgress($percent,"white"," => ".$percent." => [ ".$index." / ".number_format($totalCount,0," "," ")." ]");
            }
            
        }    
    }
}
?>