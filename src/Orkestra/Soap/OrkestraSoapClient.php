<?php
namespace Efaturacim\Util\Orkestra\Soap;

use Efaturacim\Util\Orkestra\Soap\Services\OrkestraFactoryWebService;

class OrkestraSoapClient{
    /** @var OrkestraFactoryWebService */
    protected static $factoryService = null;


    public function __construct(){

    }
    public static function newFactory($options=null){
        return new OrkestraFactoryWebService($options);
    }
    /**
     * @return OrkestraFactoryWebService
     */
    public static function getFactory($options=null){
        if(is_null(self::$factoryService)){
            self::$factoryService = self::newFactory($options);
        }
        return self::$factoryService;
    }
    /**
     * @return OrkestraFactoryWebService
     */
    public static function getFactoryWithRedis($options=null){
        return self::getFactory($options)->useRedisCache();
    }
}
?>