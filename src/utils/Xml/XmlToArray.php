<?php
namespace Efaturacim\Util\Utils\Xml;

use DOMDocument;
use DOMNode;

/**
 * A utility class to convert XML strings to PHP arrays.
 */
class XmlToArray
{
    public static $LAST_ERRORS = array();
  /**
     * Converts an XML string to a structured PHP array.
     *
     * This function can handle XML with namespaces and provides an option to
     * include namespace prefixes in the array keys.
     *
     * @param string $xmlString The XML string to convert.
     * @param bool $useNamespacesOnKeys If true, array keys will include namespace prefixes.
     *                                  Defaults to false.
     * @return array The XML data as an array. Returns an empty array on failure or for empty XML.
     */
    public static function xmlStringToArray(string $xmlString, bool $useNamespacesOnKeys = false,$forceNameSpaceRemoval=false): array
    {
        if (empty(trim($xmlString))) {
            return [];
        }

        $doc = new DOMDocument();
        try {
            // Suppress warnings for invalid XML, we'll check for errors explicitly.
            libxml_use_internal_errors(true);
            // Don't use LIBXML_NOCDATA, so we can handle CDATA sections ourselves.
            // Use LIBXML_NOBLANKS to remove empty text nodes.
            if (!$doc->loadXML($xmlString, LIBXML_NOBLANKS)) {
                // You might want to log libxml_get_errors() here for debugging.
                self::$LAST_ERRORS = libxml_get_errors();
                libxml_clear_errors();
                return []; // Return empty array on parsing failure
            }
            libxml_clear_errors();
        } catch (\Exception $e) {
            // In case of a more severe parsing error
            return [];
        }

        if (!$doc->documentElement) {
            return [];
        }

        $root = $doc->documentElement;
        $output = [];
        $key = $useNamespacesOnKeys ? $root->nodeName : $root->localName;
        if($root!=null){
            $output[$key] = self::domNodeToArray($root, $useNamespacesOnKeys,$forceNameSpaceRemoval);
        }        
        return $output;
    }

    /**
     * Recursively converts a DOMNode to an array.
     *
     * @param DOMNode $node The node to convert.
     * @param bool $useNamespacesOnKeys If true, array keys will include namespace prefixes.
     * @return array|string The converted array or string value.
     */
    private static function domNodeToArray(DOMNode $node, bool $useNamespacesOnKeys,bool $forceNameSpaceRemoval=false)
    {
        $output = [];

        // Handle attributes
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $output['@attributes'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        // Handle child nodes
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_CDATA_SECTION_NODE) {
                $output['@cdata'] = trim($child->nodeValue);
            } elseif ($child->nodeType === XML_TEXT_NODE) {
                $trimmedValue = trim($child->nodeValue);
                if (strlen($trimmedValue) > 0) {
                    $output['@value'] = $trimmedValue;
                }
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $key = $useNamespacesOnKeys ? $child->nodeName : $child->localName;                
                if($useNamespacesOnKeys==false && $forceNameSpaceRemoval){
                    $p1 = strpos($key,":",0);
                    if($p1!==false && $p1>0){
                        $key = substr($key,$p1+1);
                    }
                }
                $childData = self::domNodeToArray($child, $useNamespacesOnKeys);                                                
                if (isset($output[$key])) {
                    if (!is_array($output[$key]) || !isset($output[$key][0])) {
                        $output[$key] = [$output[$key]];
                    }
                    $output[$key][] = $childData;
                } else {
                    $output[$key] = $childData;
                }
            }
        }

        if (is_array($output) && count($output) === 1 && isset($output['@value'])) {
            return $output['@value'];
        }

        if (empty($output)) {
            return '';
        }

        return $output;
    }   
   
}
?>