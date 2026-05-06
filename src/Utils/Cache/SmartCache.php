<?php

namespace Efaturacim\Util\Utils\Cache;

use Efaturacim\B4B\Models\B4B\B4B_CachedData;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\String\StrContains;
use Exception;
use Vulcan\Base\Cache\SmartCache as CacheSmartCache;
use Vulcan\Base\Cache\SmartCacheUtil;

class SmartCache extends CacheSmartCache
{
    /**
     * B4B_UserUtil::clearAllCache bu metodu çağırır; boş extend'te Vulcan sürümü eskiyse metot eksik kalırdı.
     */
    public static function removeAllWith($needle1, $needle2, $cacheEngine = null)
    {
        SmartCacheUtil::removeAllWith($needle1, $needle2, $cacheEngine);
    }
}
