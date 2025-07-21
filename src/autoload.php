<?php 

/**
 * Registers an autoloader for the Efaturacim\Util library.
 *
 * This autoloader handles loading classes from the Efaturacim\Util namespace.
 * It assumes that the class files are located in the same directory as this
 * autoload.php file. For example, the class `Efaturacim\Util\ArrayUtil`
 * is expected to be in a file named `ArrayUtil.php`.
 */
spl_autoload_register(function ($className) {
    // The namespace prefix for this library    
    $prefix = 'Efaturacim\\Util\\';
    // The base directory for the classes (the directory of this file)
    $baseDir = str_replace("\\","/",__DIR__) . '/';
    // Check if the class uses the namespace prefix
    $len = strlen($prefix);    
    if (strncmp($prefix, $className, $len) !== 0) {
        // No, move to the next registered autoloader        
        return;
    }
    
    // Get the class name part
    $relativeClassName = substr($className, $len);
    
    // Construct the file path
    $file = $baseDir . $relativeClassName . '.php';    
    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }else{
        $p1 = strrpos($relativeClassName,"\\",0);
        if($p1>0){
            $file = $baseDir.strtolower(substr($relativeClassName,0,$p1))."/".substr($relativeClassName,$p1+1).".php";
            if (file_exists($file)) {
                require_once $file;
            }
        }        
    }
});

?>