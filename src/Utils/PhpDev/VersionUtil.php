<?php
namespace Efaturacim\Util\Utils\PhpDev;

class VersionUtil{
    public static function getVersion($versionStr){
        $r = array("isok"=>false,"org"=>$versionStr,"main"=>null,"parts"=>array(),"major"=>null,"minor"=>null,"patch"=>null);
        
        if(empty($versionStr) || !is_string($versionStr)){
            return $r;
        }
        
        // Remove any non-numeric characters except dots and hyphens
        $cleanVersion = preg_replace('/[^0-9\.\-]/', '', $versionStr);
        
        // Split by dots
        $parts = explode('.', $cleanVersion);
        
        if(count($parts) >= 1){
            $r["isok"] = true;
            $r["parts"] = $parts;
            $r["main"] = $parts[0];
            $r["major"] = isset($parts[0]) ? (int)$parts[0] : null;
            $r["minor"] = isset($parts[1]) ? (int)$parts[1] : null;
            $r["patch"] = isset($parts[2]) ? (int)$parts[2] : null;
            
            // Add additional parts if they exist
            for($i = 3; $i < count($parts); $i++){
                $r["parts"][$i] = (int)$parts[$i];
            }
        }
        
        return $r;
    }
    public static function getVersionAsString($versionStrOrArray){
        if(is_array($versionStrOrArray)){
            $r = $versionStrOrArray;
        }else{
            $r = self::getVersion($versionStrOrArray);
        }
        
        
        if(!is_array($r) || !key_exists("isok",$r) || !$r["isok"]){
            return null; // Return original if parsing failed
        }
        
        $parts = array();
        
        // Only add parts that are not null
        if($r["major"] !== null){
            $parts[] = $r["major"];
        }
        if($r["minor"] !== null){
            $parts[] = $r["minor"];
        }
        if($r["patch"] !== null){
            $parts[] = $r["patch"];
        }
        
        // Handle additional parts beyond patch
        for($i = 3; $i < count($r["parts"]); $i++){
            if($r["parts"][$i] !== null && $r["parts"][$i] !== ""){
                $parts[] = $r["parts"][$i];
            }
        }
        
        return implode('.', $parts);
    }
    
    public static function incrementVersion($versionStr,$type="patch",$returnAsString=true,$max=9999){
        $versionInfo = self::getVersion($versionStr);
        
        if(!$versionInfo["isok"]){
            return $returnAsString ? $versionStr : $versionInfo;
        }
        
        $parts = $versionInfo["parts"];
        
        switch(strtolower($type)){
            case "major":
                if(isset($parts[0])){
                    $parts[0] = (int)$parts[0] + 1;
                }
                // Reset minor and patch to 0
                if(isset($parts[1])) $parts[1] = 0;
                if(isset($parts[2])) $parts[2] = 0;
                break;
                
            case "minor":
                if(isset($parts[1])){
                    $newMinor = (int)$parts[1] + 1;
                    if($newMinor >= $max){
                        // Increment major and reset minor to 0
                        if(isset($parts[0])){
                            $parts[0] = (int)$parts[0] + 1;
                        }
                        $parts[1] = 0;
                    } else {
                        $parts[1] = $newMinor;
                    }
                } else {
                    $parts[1] = 1; // Add minor version if it doesn't exist
                }
                // Reset patch to 0
                if(isset($parts[2])) $parts[2] = 0;
                break;
                
            case "patch":
            default:
                if(isset($parts[2])){
                    $newPatch = (int)$parts[2] + 1;
                    if($newPatch >= $max){
                        // Increment minor and reset patch to 0
                        if(isset($parts[1])){
                            $newMinor = (int)$parts[1] + 1;
                            if($newMinor >= $max){
                                // Increment major and reset minor to 0
                                if(isset($parts[0])){
                                    $parts[0] = (int)$parts[0] + 1;
                                }
                                $parts[1] = 0;
                            } else {
                                $parts[1] = $newMinor;
                            }
                        } else {
                            $parts[1] = 1; // Add minor version if it doesn't exist
                        }
                        $parts[2] = 0;
                    } else {
                        $parts[2] = $newPatch;
                    }
                } else {
                    $parts[2] = 1; // Add patch version if it doesn't exist
                }
                break;
        }
        
        $newVersionStr = implode('.', $parts);
        
        if($returnAsString){
            return $newVersionStr;
        } else {
            return self::getVersion($newVersionStr);
        }
    }
    
    // Alias functions for convenience
    public static function incrementMajorVersion($versionStr, $returnAsString = true,$max=9999){
        return self::incrementVersion($versionStr, "major", $returnAsString,$max);
    }
    
    public static function incrementMinorVersion($versionStr, $returnAsString = true,$max=9999){
        return self::incrementVersion($versionStr, "minor", $returnAsString,$max);
    }
    
    public static function incrementPatchVersion($versionStr, $returnAsString = true,$max=9999){
        return self::incrementVersion($versionStr, "patch", $returnAsString,$max);
    }
}
?>