<?php
namespace Efaturacim\Util\Utils\Array;

use Efaturacim\Util\Utils\String\StrUtil;

class ArrayFilter{
    public static function filterByFunction($array,$callback){
        return array_filter($array,$callback);
    }
    
    /**
     * Smart filtering, searching, ordering, and pagination for arrays
     * 
     * @param array $dataOrg Original data array
     * @param string|array|null $searchTextOrArray Search text(s) to filter by
     * @param int|null $startIndex Starting index for pagination (offset)
     * @param int|null $limit Maximum number of items to return
     * @param int|null $orderIndex Index of the field to order by
     * @param bool|null $orderAsc True for ascending, false for descending, null defaults to ascending
     * @param array|null $useFieldsForSearch Specific fields to search in
     * @return array Filtered and processed array
     */
    public static function filterSmart($dataOrg, $searchTextOrArray = null, $startIndex = null, $limit = null, $orderIndex = null, $orderAsc = null, $useFieldsForSearch = null,$convertToEng=true)
    {
        // If no data, return empty array
        if (empty($dataOrg) || !is_array($dataOrg)) {
            return [];
        }
        
        $result = $dataOrg;        
        // Step 1: Apply search filtering
        if ($searchTextOrArray !== null) {
            $result = self::applySearchFilter($result, $searchTextOrArray, $useFieldsForSearch, $convertToEng);
        }        
        // Step 2: Apply ordering
        if ($orderIndex !== null) {
            $result = self::applyOrdering($result, $orderIndex, $orderAsc);
        }
        
        // Step 3: Apply pagination (offset)
        if ($startIndex !== null && $startIndex > 0) {
            $result = array_slice($result, $startIndex);
        }
        
        // Step 4: Apply limit
        if ($limit !== null && $limit > 0) {
            $result = array_slice($result, 0, $limit);
        }
        
        return $result;
    }
    
    /**
     * Apply search filtering to the array
     * 
     * @param array $data
     * @param string|array $searchTextOrArray
     * @param array|null $useFieldsForSearch
     * @param bool $convertToEng
     * @return array
     */
    private static function applySearchFilter($data, $searchTextOrArray, $useFieldsForSearch = null, $convertToEng = true)
    {
        // Convert search text to array if it's a string
        $searchTexts = is_array($searchTextOrArray) ? $searchTextOrArray : [$searchTextOrArray];
        
        // Filter out empty search texts
        $searchTexts = array_filter($searchTexts, function($text) {
            return $text !== null && $text !== '' && trim("".$text)!="";
        });        
        // If no valid search texts, return original data
        if (empty($searchTexts)) {
            return $data;
        }
        
        return array_filter($data, function($item) use ($searchTexts, $useFieldsForSearch, $convertToEng) {
            // If item is not an array, convert to string and search
            if (!is_array($item)) {
                $itemString = $convertToEng ? StrUtil::toLowerEng((string)$item) : strtolower((string)$item);
                foreach ($searchTexts as $searchText) {
                    $searchTextNormalized = $convertToEng ? StrUtil::toLowerEng($searchText) : strtolower($searchText);
                    if (strpos($itemString, $searchTextNormalized) === false) {
                        return false;
                    }
                }
                return true;
            }
            
            // Determine which fields to search
            $fieldsToSearch = $useFieldsForSearch !== null ? $useFieldsForSearch : array_keys($item);
            
            // Check if all search texts are found in any of the specified fields
            foreach ($searchTexts as $searchText) {
                $found = false;
                $searchTextNormalized = $convertToEng ? StrUtil::toLowerEng($searchText) : strtolower($searchText);
                
                foreach ($fieldsToSearch as $field) {
                    if (isset($item[$field])) {
                        $fieldValue = $convertToEng ? StrUtil::toLowerEng((string)$item[$field]) : strtolower((string)$item[$field]);
                        if (strpos($fieldValue, $searchTextNormalized) !== false) {
                            $found = true;
                            break;
                        }
                    }
                }
                
                if (!$found) {
                    return false;
                }
            }
            
            return true;
        });
    }
    
    /**
     * Apply ordering to the array
     * 
     * @param array $data
     * @param int $orderIndex
     * @param bool|null $orderAsc
     * @return array
     */
    private static function applyOrdering($data, $orderIndex, $orderAsc = null)
    {
        // Default to ascending if not specified
        $orderAsc = $orderAsc !== false; // true if null or true, false only if explicitly false
        
        usort($data, function($a, $b) use ($orderIndex, $orderAsc) {
            $valueA = self::getValueByIndex($a, $orderIndex);
            $valueB = self::getValueByIndex($b, $orderIndex);
            
            // Handle null values
            if ($valueA === null && $valueB === null) {
                return 0;
            }
            if ($valueA === null) {
                return $orderAsc ? -1 : 1;
            }
            if ($valueB === null) {
                return $orderAsc ? 1 : -1;
            }
            
            // Compare values
            $comparison = 0;
            if (is_numeric($valueA) && is_numeric($valueB)) {
                $comparison = $valueA <=> $valueB;
            } else {
                $comparison = strcasecmp((string)$valueA, (string)$valueB);
            }
            
            return $orderAsc ? $comparison : -$comparison;
        });
        
        return $data;
    }
    
    /**
     * Get value by index from array or object
     * 
     * @param mixed $item
     * @param int $index
     * @return mixed
     */
    private static function getValueByIndex($item, $index)
    {
        if (is_array($item)) {
            $keys = array_keys($item);
            if (isset($keys[$index])) {
                return $item[$keys[$index]];
            }
        } elseif (is_object($item)) {
            $properties = get_object_vars($item);
            $keys = array_keys($properties);
            if (isset($keys[$index])) {
                return $properties[$keys[$index]];
            }
        }
        
        return null;
    }
}
?>