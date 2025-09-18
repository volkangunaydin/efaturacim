<?php

namespace Efaturacim\Util\Ubl\Objects;

use Efaturacim\Util\Utils\String\StrUtil;

class UblDataTypeListForAdditionalItemIdentification extends UblDataTypeList{
    public function initMe(){
        if(is_null($this->className)){
            $this->className = AdditionalItemIdentification::class;
        }    
    }
    
    public function setAdditionalItemID($value, $schemeID = null){
        if(StrUtil::notEmpty($schemeID)){
            $this->getSchemeID($schemeID)->setValue($value, $schemeID);
        } else {
            // Eğer schemeID belirtilmemişse, varsayılan olarak ekle
            $additionalItemId = new AdditionalItemIdentification();
            $additionalItemId->setValue($value);
            $this->add($additionalItemId);
        }
        return $this;
    }

    public function &getSchemeID($schemeID, $createIfNot = false){
        $key = $this->getKeyForSchemeID($schemeID, $createIfNot);
        return $this->list[$key];
    }
    
    public function getKeyForSchemeID($schemeID, $createIfNot = false){
        if(count($this->list) > 0){
            foreach($this->list as $key => $item){
                if($item instanceof AdditionalItemIdentification){
                    if(isset($item->id->attributes['schemeID']) && $item->id->attributes['schemeID'] === $schemeID){
                        return $key;
                    }
                }
            }
        }
        if($createIfNot){
            $additionalItemId = new AdditionalItemIdentification();
            $additionalItemId->id->attributes['schemeID'] = $schemeID;
            $this->add($additionalItemId);
            return array_key_last($this->list);
        }        
        return null;
    }    
}
?>
