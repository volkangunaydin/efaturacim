<?php
namespace Efaturacim\Util\Utils\Laravel\Util;

class LaravelFolderCheck{
    /**
     * Check if current directory is a Laravel project
     */
    public static function isLaravelProject($path = null){
        if ($path === null) {
            $path = getcwd();
        }
        
        // Check for artisan file (Laravel's command-line interface)
        if (!file_exists($path . '/artisan')) {
            return false;
        }
        
        // Check composer.json for Laravel framework dependency
        $composerFile = $path . '/composer.json';
        if (file_exists($composerFile)) {
            $composer = json_decode(file_get_contents($composerFile), true);
            if (isset($composer['require']['laravel/framework'])) {
                return true;
            }
        }
        
        // Check for bootstrap/app.php (Laravel 11+)
        if (file_exists($path . '/bootstrap/app.php')) {
            return true;
        }
        
        // Check for app/ directory and typical Laravel structure
        if (file_exists($path . '/app') && 
            file_exists($path . '/config') && 
            file_exists($path . '/routes')) {
            return true;
        }
        
        return false;
    }    
}
?>