<?php
namespace Efaturacim\Util\Utils;

use Efaturacim\Util\Utils\Results\ResultUtil;

class RestApiResult extends SimpleResult{
        public $statusCode = 200;
        public $lines      = array();
        public $startTime  = null;
        public $startDate  = null;
        public $dataObject = null;
        public $apiMsgGuid = null;
        public $debugEnabled = true;
        public function __construct(){            

        }
        public static function newFromJson($jsonStringOrArray){
            return ResultUtil::newFromJson($jsonStringOrArray,self::class);
        }
}
?>