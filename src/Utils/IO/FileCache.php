<?php
namespace Efaturacim\Util\Utils\IO;

use Efaturacim\Util\Utils\SimpleResult;

/**
 * File-based caching utility class
 */
class FileCache{
    
    /**
     * Get cached data or create it if not exists or expired
     * 
     * @param string $cacheFolder The cache folder path
     * @param string|array $cacheKey The cache key for the file
     * @param callable $callableCreateCacheObj Function to create cache object if not exists
     * @param int $timeout Cache timeout in seconds (default: 3600 = 1 hour)
     * @return SimpleResult The result containing cached data and metadata
     */
    public static function getCached($cacheFolder, $cacheKey, $callableCreateCacheObj, $timeout = 3600)
    {
        $result = new SimpleResult();
        $startTime = microtime(true);
        if(is_array($cacheKey)){
            $cacheKey = self::getCacheKey($cacheKey);
        }
        try {
            // Ensure cache folder exists
            if (!is_dir($cacheFolder)) {
                if (!mkdir($cacheFolder, 0755, true)) {
                    throw new \Exception("Unable to create cache directory: " . $cacheFolder);
                }
            }
            
            // Create cache file path
            $cacheFile = rtrim($cacheFolder, '/') . '/' . md5($cacheKey) . '.cache';
            
            // Check if cache file exists and is not expired
            if (file_exists($cacheFile)) {
                $fileTime = filemtime($cacheFile);
                $currentTime = time();
                
                // If cache is still valid, return cached data
                if (($currentTime - $fileTime) < $timeout) {
                    $cachedData = file_get_contents($cacheFile);
                    if ($cachedData !== false) {
                        $unserialized = unserialize($cachedData);
                        if ($unserialized !== false) {
                            $elapsedTime = microtime(true) - $startTime;
                            
                            $result->setIsOk(true)
                                   ->setValue($unserialized)
                                   ->setAttribute('cache_status', 'hit')
                                   ->setAttribute('cache_file', $cacheFile)
                                   ->setAttribute('cache_age', $currentTime - $fileTime)
                                   ->setAttribute('elapsed_time', $elapsedTime)
                                   ->setAttribute('cache_key', $cacheKey)
                                   ->setAttribute('timeout', $timeout)
                                   ->addSuccess("Cache hit - data retrieved from cache");
                            
                            return $result;
                        }
                    }
                }
            }
            
            // Cache doesn't exist or is expired, create new data
            $creationStartTime = microtime(true);
            $newData = call_user_func($callableCreateCacheObj);
            $creationTime = microtime(true) - $creationStartTime;
            
            // Serialize and save to cache file
            $serializedData = serialize($newData);
            if (file_put_contents($cacheFile, $serializedData) === false) {
                throw new \Exception("Unable to write cache file: " . $cacheFile);
            }
            
            $elapsedTime = microtime(true) - $startTime;
            
            $result->setIsOk(true)
                   ->setValue($newData)
                   ->setAttribute('cache_status', 'miss_created')
                   ->setAttribute('cache_file', $cacheFile)
                   ->setAttribute('cache_age', 0)
                   ->setAttribute('elapsed_time', $elapsedTime)
                   ->setAttribute('creation_time', $creationTime)
                   ->setAttribute('cache_key', $cacheKey)
                   ->setAttribute('timeout', $timeout)
                   ->addSuccess("Cache miss - new data created and cached");
            
            return $result;
            
        } catch (\Exception $e) {
            // If creation fails, try to return cached data even if expired
            if (file_exists($cacheFile)) {
                $cachedData = file_get_contents($cacheFile);
                if ($cachedData !== false) {
                    $unserialized = unserialize($cachedData);
                    if ($unserialized !== false) {
                        $elapsedTime = microtime(true) - $startTime;
                        
                        $result->setIsOk(true)
                               ->setValue($unserialized)
                               ->setAttribute('cache_status', 'fallback_expired')
                               ->setAttribute('cache_file', $cacheFile)
                               ->setAttribute('cache_age', 'expired')
                               ->setAttribute('elapsed_time', $elapsedTime)
                               ->setAttribute('cache_key', $cacheKey)
                               ->setAttribute('timeout', $timeout)
                               ->addWarn("Using expired cache as fallback due to creation error: " . $e->getMessage());
                        
                        return $result;
                    }
                }
            }
            
            // No fallback available, return error result
            $elapsedTime = microtime(true) - $startTime;
            
            $result->setIsOk(false)
                   ->setAttribute('cache_status', 'error')
                   ->setAttribute('cache_file', $cacheFile ?? 'unknown')
                   ->setAttribute('elapsed_time', $elapsedTime)
                   ->setAttribute('cache_key', $cacheKey)
                   ->setAttribute('timeout', $timeout)
                   ->addError("Cache error: " . $e->getMessage());
            
            return $result;
        }
    }
    
    /**
     * Clear cache for a specific key
     * 
     * @param string $cacheFolder The cache folder path
     * @param string $cacheKey The cache key to clear
     * @return SimpleResult Result with success/error status
     */
    public static function clearCache($cacheFolder, $cacheKey)
    {
        $result = new SimpleResult();
        $cacheFile = rtrim($cacheFolder, '/') . '/' . md5($cacheKey) . '.cache';
        
        if (file_exists($cacheFile)) {
            if (unlink($cacheFile)) {
                $result->setIsOk(true)
                       ->setAttribute('cache_file', $cacheFile)
                       ->setAttribute('cache_key', $cacheKey)
                       ->addSuccess("Cache cleared successfully");
            } else {
                $result->setIsOk(false)
                       ->setAttribute('cache_file', $cacheFile)
                       ->setAttribute('cache_key', $cacheKey)
                       ->addError("Failed to delete cache file");
            }
        } else {
            $result->setIsOk(false)
                   ->setAttribute('cache_file', $cacheFile)
                   ->setAttribute('cache_key', $cacheKey)
                   ->addWarn("Cache file not found");
        }
        
        return $result;
    }
    
    /**
     * Clear all cache files in the cache folder
     * 
     * @param string $cacheFolder The cache folder path
     * @return SimpleResult Result with count of cleared files
     */
    public static function clearAllCache($cacheFolder)
    {
        $result = new SimpleResult();
        
        if (!is_dir($cacheFolder)) {
            $result->setIsOk(false)
                   ->addError("Cache folder does not exist: " . $cacheFolder);
            return $result;
        }
        
        $clearedCount = 0;
        $files = glob(rtrim($cacheFolder, '/') . '/*.cache');
        
        foreach ($files as $file) {
            if (unlink($file)) {
                $clearedCount++;
            }
        }
        
        $result->setIsOk(true)
               ->setValue($clearedCount)
               ->setAttribute('cache_folder', $cacheFolder)
               ->setAttribute('total_files', count($files))
               ->setAttribute('cleared_files', $clearedCount)
               ->addSuccess("Cleared {$clearedCount} cache files");
        
        return $result;
    }
    
    /**
     * Check if cache exists and is valid
     * 
     * @param string $cacheFolder The cache folder path
     * @param string $cacheKey The cache key to check
     * @param int $timeout Cache timeout in seconds
     * @return SimpleResult Result with cache validity status
     */
    public static function hasValidCache($cacheFolder, $cacheKey, $timeout = 3600)
    {
        $result = new SimpleResult();
        $cacheFile = rtrim($cacheFolder, '/') . '/' . md5($cacheKey) . '.cache';
        
        if (!file_exists($cacheFile)) {
            $result->setIsOk(false)
                   ->setValue(false)
                   ->setAttribute('cache_file', $cacheFile)
                   ->setAttribute('cache_key', $cacheKey)
                   ->setAttribute('cache_status', 'not_exists')
                   ->addWarn("Cache file does not exist");
            return $result;
        }
        
        $fileTime = filemtime($cacheFile);
        $currentTime = time();
        $isValid = ($currentTime - $fileTime) < $timeout;
        
        $result->setIsOk(true)
               ->setValue($isValid)
               ->setAttribute('cache_file', $cacheFile)
               ->setAttribute('cache_key', $cacheKey)
               ->setAttribute('cache_age', $currentTime - $fileTime)
               ->setAttribute('timeout', $timeout)
               ->setAttribute('cache_status', $isValid ? 'valid' : 'expired');
        
        if ($isValid) {
            $result->addSuccess("Cache is valid");
        } else {
            $result->addWarn("Cache has expired");
        }
        
        return $result;
    }
    public static function getCacheKey($keyOrArray){
        if(is_array($keyOrArray) && count($keyOrArray)>0){
            return md5(serialize($keyOrArray));
        }
        if(is_string($keyOrArray) && strlen($keyOrArray)>0){
            return  $keyOrArray;
        }
        return null;
    }
}
?>