<?php
namespace Efaturacim\Util\Utils\Laravel\Doc;

use Illuminate\Support\Facades\DB;

class LV_Doc{
    protected static $INSTANCE = null;
    protected static $params   = [];
    public static function test(){
        return 'test string ';
    }
    public static function getInstance(){
        if(is_null(self::$INSTANCE)){
            static::$INSTANCE = new static();
        }
        return static::$INSTANCE;
    }
    public static function smart($action=null){
        $args = func_get_args();
        if($action=="set" && count($args)>=3){
            self::$params[$args[1]] = $args[2];
        }else if($action=="get" && count($args)>=3){
            return "=> ".@$args[1];
        }else if($action=="require_js" && count($args)>=2){
            return "required=>".@$args[2];
        }else if($action=="debug"){
            return \Vulcan\V::dump(self::getDebugArray());
        }
        return print_r($args, true);
    }
    public static function getDebugArray(){
        return [
            "params" => self::$params,
        ];
    }
    public static function smartDirectiveFunction($expression){        
        $sub = substr("".$expression,0,5);
        if($sub=="\"get\""){
            $params = self::parseExpression($expression);
            if(count($params)>=2 && is_string(@$params[1])){
                //return "<?php \$deneme = Efaturacim\Util\Utils\Laravel\Doc\LV_Doc::smart($expression); ?".">";
            }                                    
        }        
        return "<?php Efaturacim\Util\Utils\Laravel\Doc\LV_Doc::smart($expression); ?>";
    }
    private static function parseExpression($expression){
        $params = [];
        $expression = trim($expression, '()');        
        if (empty($expression)) {
            return $params;
        }        
        // Handle different parameter types
        $parts = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $depth = 0;
        
        for ($i = 0; $i < strlen($expression); $i++) {
            $char = $expression[$i];
            
            if (($char === '"' || $char === "'") && !$inString) {
                $inString = true;
                $stringChar = $char;
            } elseif ($char === $stringChar && $inString) {
                $inString = false;
            } elseif ($char === '(' && !$inString) {
                $depth++;
            } elseif ($char === ')' && !$inString) {
                $depth--;
            } elseif ($char === ',' && !$inString && $depth === 0) {
                $parts[] = trim($current);
                $current = '';
                continue;
            }
            
            $current .= $char;
        }
        
        if (!empty($current)) {
            $parts[] = trim($current);
        }
        
        return $parts;
    }
    public static function debugStart(){
        DB::enableQueryLog();
    }
    public static function debugEnd(){
        $queries = DB::getQueryLog();
        dd($queries);
    }
}

?>