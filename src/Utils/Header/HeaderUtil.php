<?php

namespace Efaturacim\Util\Utils\Header;

use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\IO\IO_Util;
use Efaturacim\Util\Utils\IO\MimeTypes;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;

class HeaderUtil
{
    public static function smart($content,$fileName=null,$opt=null,$defVals=null){
        $options = new Options($opt,$defVals);
        
        // Determine content type
        $contentType = $options->getAsString(array("contentType","type","contenttype"));
        $isDownload = $options->getAsBool(array("download","force_download"), false);
        $charset = $options->getAsString(array("charset","encoding"), "utf-8");
        $cacheControl = $options->getAsString(array("cache_control","cache"), null);
        
        // Auto-detect content type from file name if not provided
        if(StrUtil::isEmpty($contentType) && StrUtil::notEmpty($fileName)){
            $extension   = IO_Util::getExtensionFromName($fileName);
            $contentType = MimeTypes::getMimeType($extension);
        }

        
        
        // Set default content type if still empty
        if(StrUtil::isEmpty($contentType)){
            // Try to detect from content
            if(StrUtil::startsWith($content, '<?xml') || strpos($content, '<html') !== false){
                $contentType = 'text/html';
            } else if(StrUtil::startsWith($content, '{') || StrUtil::startsWith($content, '[')){
                $contentType = 'application/json';
            } else if(strpos($content, '<?xml') !== false){
                $contentType = 'text/xml';
            } else {
                $contentType = 'text/plain';
            }
        }
        
        // Set appropriate headers
        if($isDownload){
            // Force download headers
            $disposition = 'attachment';
            if($options->getAsBool("inline", false)){
                $disposition = 'inline';
            }
            
            header("Content-Type: {$contentType}");
            if(StrUtil::notEmpty($fileName)){
                header("Content-Disposition: {$disposition}; filename=\"{$fileName}\"");
            } else {
                header("Content-Disposition: {$disposition}");
            }
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Cache-Control: no-cache, no-store, must-revalidate");
        } else {
            // Normal display headers
            if(strpos($contentType, 'text/') !== false || strpos($contentType, 'application/json') !== false || strpos($contentType, 'application/xml') !== false){
                header("Content-Type: {$contentType}; charset={$charset}");
            } else {
                header("Content-Type: {$contentType}");
            }
            
            // Handle caching
            if(StrUtil::notEmpty($cacheControl)){
                if($cacheControl === 'no-cache'){
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                } else if($cacheControl === 'public'){
                    header("Cache-Control: public, max-age=3600");
                } else {
                    header("Cache-Control: {$cacheControl}");
                }
            } else {
                // Default caching for different content types
                if(strpos($contentType, 'image/') !== false || strpos($contentType, 'application/pdf') !== false){
                    header("Cache-Control: public, max-age=86400"); // 24 hours for static content
                } else if(strpos($contentType, 'text/html') !== false || strpos($contentType, 'application/json') !== false){
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                }
            }
        }
        
        // Set content length if content is not empty
        if(strlen("".$content) > 0){
            header("Content-Length: " . strlen($content));
            echo "".$content;
        }
        
        die("");
    }
    public static function downloadHeader($fileName=null,$fileSize=0){
        $fileSize = CastUtil::asInt($fileSize);
        if(StrUtil::isEmpty($fileName)){
            $fileName = "download.bin";
        }
        $mimeType = "application/octet-stream";
        $a = MimeTypes::getMimeType($fileName);
        if(strlen("".$a)>0){
            $mimeType = "".$a;
        }

        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: {$mimeType}");
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header("Content-Transfer-Encoding: binary");
        if($fileSize > 0){
            header("Content-Length: {$fileSize}");
         }            
    }    
}