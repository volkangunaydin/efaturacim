<?php

declare(strict_types=1);

namespace Efaturacim\Util\Utils\String;

/**
 * String case conversion utilities with Turkish character support
 * 
 * Provides various case conversion methods including:
 * - Standard case conversions (upper, lower, camelCase, etc.)
 * - Turkish character support
 * - Case detection and validation
 * - String normalization
 */
class StrCase
{
    /**
     * Turkish character mappings for case conversion
     */
    private const TURKISH_CHARS = [
        'lower' => ['ç', 'ğ', 'ı', 'i', 'ö', 'ş', 'ü', 'â', 'î', 'û'],
        'upper' => ['Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü', 'Â', 'Î', 'Û']
    ];

    /**
     * Extended Turkish character mappings for removal
     */
    private const TURKISH_TO_ENGLISH = [
        'Ç' => 'C', 'Ğ' => 'G', 'I' => 'I', 'İ' => 'I', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U',
        'ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'i' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
        'Â' => 'A', 'Î' => 'I', 'Û' => 'U', 'â' => 'a', 'î' => 'i', 'û' => 'u'
    ];

    // BASIC CASE CONVERSIONS - TEMEL BÜYÜK/KÜÇÜK HARF DÖNÜŞÜMLERİ

    /**
     * Convert string to uppercase with Turkish character support
     * 
     * @param string|array $input String or array of strings
     * @return string|array Uppercase string(s)
     */
    public static function upper($input)
    {
        return self::toUpperTurkish($input);
    }

    /**
     * Convert string to lowercase with Turkish character support
     * 
     * @param string|array $input String or array of strings
     * @return string|array Lowercase string(s)
     */
    public static function lower($input)
    {
        return self::toLowerTurkish($input);
    }

    /**
     * Convert to camelCase (first letter lowercase, subsequent words capitalized)
     * 
     * @param string $text Input text
     * @return string camelCase string
     */
    public static function camelCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/[^a-zA-Z0-9\s_\-]/', ' ', $text);
        $text = preg_replace('/[\s_\-]+/', ' ', trim($text));
        
        $words = explode(' ', $text);
        $camelCase = '';
        
        foreach ($words as $index => $word) {
            if ($index === 0) {
                $camelCase .= self::toLowerTurkish($word);
            } else {
                $camelCase .= self::toUpperTurkish(substr($word, 0, 1)) . self::toLowerTurkish(substr($word, 1));
            }
        }
        
        return $camelCase;
    }

    /**
     * Convert to PascalCase (first letter of each word capitalized)
     * 
     * @param string $text Input text
     * @return string PascalCase string
     */
    public static function pascalCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/[^a-zA-Z0-9\s_\-]/', ' ', $text);
        $text = preg_replace('/[\s_\-]+/', ' ', trim($text));
        
        $words = explode(' ', $text);
        $pascalCase = '';
        
        foreach ($words as $word) {
            $pascalCase .= self::toUpperTurkish(substr($word, 0, 1)) . self::toLowerTurkish(substr($word, 1));
        }
        
        return $pascalCase;
    }

    /**
     * Convert to snake_case (words separated by underscores)
     * 
     * @param string $text Input text
     * @return string snake_case string
     */
    public static function snakeCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/[^a-zA-Z0-9\s_\-]/', ' ', $text);
        $text = preg_replace('/[\s\-]+/', ' ', trim($text));
        $text = preg_replace('/\s+/', '_', $text);
        
        return $text;
    }

    /**
     * Convert to UPPER_SNAKE_CASE (words separated by underscores, all uppercase)
     * 
     * @param string $text Input text
     * @return string UPPER_SNAKE_CASE string
     */
    public static function upperSnakeCase(string $text): string
    {
        return self::toUpperTurkish(self::snakeCase($text));
    }

    /**
     * Convert to kebab-case (words separated by hyphens)
     * 
     * @param string $text Input text
     * @return string kebab-case string
     */
    public static function kebabCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/[^a-zA-Z0-9\s_\-]/', ' ', $text);
        $text = preg_replace('/[\s_]+/', ' ', trim($text));
        $text = preg_replace('/\s+/', '-', $text);
        
        return $text;
    }

    /**
     * Convert to Title Case (first letter of each word capitalized)
     * 
     * @param string $text Input text
     * @return string Title Case string
     */
    public static function titleCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        $words = explode(' ', $text);
        $titleCase = '';
        
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $titleCase .= self::toUpperTurkish(substr($word, 0, 1)) . self::toLowerTurkish(substr($word, 1)) . ' ';
            }
        }
        
        return trim($titleCase);
    }

    /**
     * Convert to Sentence case (first letter of first word capitalized)
     * 
     * @param string $text Input text
     * @return string Sentence case string
     */
    public static function sentenceCase(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        if (strlen($text) > 0) {
            $text = self::toUpperTurkish(substr($text, 0, 1)) . substr($text, 1);
        }
        
        return $text;
    }

    // TURKISH CHARACTER SUPPORT - TÜRKÇE KARAKTER DESTEĞİ

    /**
     * Convert to lowercase with Turkish character support
     * 
     * @param string|array $input String or array of strings
     * @return string|array Lowercase string(s) with Turkish characters
     */
    public static function toLowerTurkish($input)
    {
        if (is_array($input)) {
            $result = [];
            foreach ($input as $key => $value) {
                $result[$key] = self::toLowerTurkish($value);
            }
            return $result;
        }
        
        $input = (string)$input;
        $input = str_replace(self::TURKISH_CHARS['upper'], self::TURKISH_CHARS['lower'], $input);
        return mb_strtolower($input, 'UTF-8');
    }

    /**
     * Convert to uppercase with Turkish character support
     * 
     * @param string|array $input String or array of strings
     * @return string|array Uppercase string(s) with Turkish characters
     */
    public static function toUpperTurkish($input)
    {
        if (is_array($input)) {
            $result = [];
            foreach ($input as $key => $value) {
                $result[$key] = self::toUpperTurkish($value);
            }
            return $result;
        }
        
        $input = (string)$input;
        $input = str_replace(self::TURKISH_CHARS['lower'], self::TURKISH_CHARS['upper'], $input);
        return mb_strtoupper($input, 'UTF-8');
    }

    /**
     * Remove Turkish characters and convert to English equivalents
     * 
     * @param string|array $input String or array of strings
     * @return string|array String(s) with Turkish characters replaced
     */
    public static function removeTurkishChars($input)
    {
        if (is_array($input)) {
            $result = [];
            foreach ($input as $key => $value) {
                $result[$key] = self::removeTurkishChars($value);
            }
            return $result;
        }
        
        return str_replace(array_keys(self::TURKISH_TO_ENGLISH), array_values(self::TURKISH_TO_ENGLISH), (string)$input);
    }

    /**
     * Alias for removeTurkishChars
     * 
     * @param string|array $input String or array of strings
     * @return string|array String(s) with Turkish characters replaced
     */
    public static function toEng($input)
    {
        return self::removeTurkishChars($input);
    }

    /**
     * Alias for removeTurkishChars
     * 
     * @param string|array $input String or array of strings
     * @return string|array String(s) with Turkish characters replaced
     */
    public static function eng($input)
    {
        return self::removeTurkishChars($input);
    }

    // CASE DETECTION - BÜYÜK/KÜÇÜK HARF TESPİTİ

    /**
     * Check if string is in uppercase
     * 
     * @param string $text Input text
     * @return bool True if uppercase
     */
    public static function isUpper(string $text): bool
    {
        return $text === self::toUpperTurkish($text) && $text !== self::toLowerTurkish($text);
    }

    /**
     * Check if string is in lowercase
     * 
     * @param string $text Input text
     * @return bool True if lowercase
     */
    public static function isLower(string $text): bool
    {
        return $text === self::toLowerTurkish($text) && $text !== self::toUpperTurkish($text);
    }

    /**
     * Check if string is in camelCase
     * 
     * @param string $text Input text
     * @return bool True if camelCase
     */
    public static function isCamelCase(string $text): bool
    {
        return $text === self::camelCase($text) && preg_match('/^[a-z][a-zA-Z0-9]*$/', $text);
    }

    /**
     * Check if string is in PascalCase
     * 
     * @param string $text Input text
     * @return bool True if PascalCase
     */
    public static function isPascalCase(string $text): bool
    {
        return $text === self::pascalCase($text) && preg_match('/^[A-Z][a-zA-Z0-9]*$/', $text);
    }

    /**
     * Check if string is in snake_case
     * 
     * @param string $text Input text
     * @return bool True if snake_case
     */
    public static function isSnakeCase(string $text): bool
    {
        return $text === self::snakeCase($text) && preg_match('/^[a-z][a-z0-9_]*$/', $text);
    }

    /**
     * Check if string is in kebab-case
     * 
     * @param string $text Input text
     * @return bool True if kebab-case
     */
    public static function isKebabCase(string $text): bool
    {
        return $text === self::kebabCase($text) && preg_match('/^[a-z][a-z0-9\-]*$/', $text);
    }

    // UTILITY METHODS - YARDIMCI METODLAR

    /**
     * Normalize string by removing extra spaces and special characters
     * 
     * @param string $text Input text
     * @return string Normalized string
     */
    public static function normalize(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        return $text;
    }

    /**
     * Convert string to URL-friendly format
     * 
     * @param string $text Input text
     * @return string URL-friendly string
     */
    public static function toUrl(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = self::removeTurkishChars($text);
        $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
        $text = preg_replace('/\s+/', '-', trim($text));
        $text = preg_replace('/-+/', '-', $text);
        
        return trim($text, '-');
    }

    /**
     * Convert string to filename-friendly format
     * 
     * @param string $text Input text
     * @return string Filename-friendly string
     */
    public static function toFilename(string $text): string
    {
        $text = self::toLowerTurkish($text);
        $text = self::removeTurkishChars($text);
        $text = preg_replace('/[^a-z0-9\s\-_\.]/', '', $text);
        $text = preg_replace('/\s+/', '_', trim($text));
        $text = preg_replace('/_+/', '_', $text);
        
        return trim($text, '_');
    }

    /**
     * Get all available case conversion methods
     * 
     * @return array Array of available methods
     */
    public static function getAvailableMethods(): array
    {
        return [
            'upper', 'lower', 'camelCase', 'pascalCase', 'snakeCase', 
            'upperSnakeCase', 'kebabCase', 'titleCase', 'sentenceCase',
            'toLowerTurkish', 'toUpperTurkish', 'removeTurkishChars',
            'normalize', 'toUrl', 'toFilename'
        ];
    }

    /**
     * Convert string to specified case
     * 
     * @param string $text Input text
     * @param string $case Case type
     * @return string Converted string
     * @throws \InvalidArgumentException If case type is not supported
     */
    public static function toCase(string $text, string $case): string
    {
        $case = strtolower($case);
        $methods = [
            'upper' => 'upper',
            'lower' => 'lower',
            'camel' => 'camelCase',
            'pascal' => 'pascalCase',
            'snake' => 'snakeCase',
            'upper_snake' => 'upperSnakeCase',
            'kebab' => 'kebabCase',
            'title' => 'titleCase',
            'sentence' => 'sentenceCase',
            'url' => 'toUrl',
            'filename' => 'toFilename',
            'normalize' => 'normalize'
        ];

        if (!isset($methods[$case])) {
            return $text;
        }

        $method = $methods[$case];
        return self::$method($text);
    }
}

?>