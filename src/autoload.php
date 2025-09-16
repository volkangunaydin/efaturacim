<?php 

/**
 * PSR-4 compliant autoloader for the Efaturacim\Util library.
 *
 * This autoloader follows PSR-4 standards:
 * - Namespace Efaturacim\Util maps to directory src/
 * - Class names are converted to file names
 * - File names must match class names exactly
 */
spl_autoload_register(function ($className) {
    // The namespace prefix for this library    
    $prefix = 'Efaturacim\\Util\\';
    
    // Check if the class uses the namespace prefix
    $len = strlen($prefix);    
    if (strncmp($prefix, $className, $len) !== 0) {
        // No, move to the next registered autoloader        
        return;
    }
    
    
    // Get the relative class name
    $relativeClass = substr($className, $len);
    
    // Convert namespace separators to directory separators
    $relativeClass = str_replace('\\', '/', $relativeClass);
    
    // The base directory for the classes (the directory of this file)
    $baseDir = __DIR__ . '/';
    
    // Construct the file path
    $file = $baseDir . $relativeClass . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // PSR-4 fallback: try with lowercase directory names for first level
    $parts = explode('/', $relativeClass);
    if (count($parts) > 1) {
        $firstPart = strtolower($parts[0]);
        $remainingParts = array_slice($parts, 1);
        $fallbackFile = $baseDir . $firstPart . '/' . implode('/', $remainingParts) . '.php';
        
        if (file_exists($fallbackFile)) {
            require_once $fallbackFile;
            return;
        }
    }
    
    // Debug: log if class not found
    if (defined('EFATURACIM_DEBUG') && EFATURACIM_DEBUG) {
        error_log("Efaturacim PSR-4 autoloader: Class not found: {$className}, tried: {$file}");
    }
});

?>