<?php

namespace Efaturacim\Util\Ubl\Objects;

use Efaturacim\Util\Utils\String\StrUtil;

class UblDataTypeListForPartyIdentification extends UblDataTypeList{
    public function initMe(){
        if(is_null($this->className)){
            $this->className = PartyIdentification::class;
        }    
    }
    public function setVkn($vkn=null){
        $vkn = StrUtil::trimAllExceptNumbers($vkn);
        if(StrUtil::len($vkn)==11){
            // TC KIMLIK
            $this->getSchemeID("TCKN")->setValue($vkn);    
            
        }else if(StrUtil::len($vkn)==10){
            // VKN
            $this->getSchemeID("VKN")->setValue($vkn);    
        }
        return $this;
    }
    public function setMersisNo($value){
        $this->getSchemeID("MERSISNO")->setValue($value);    
    }
    public function setDorse($value){
        $this->getSchemeID("DORSEPLAKA")->setValue($value);    
    }
    public function setTicaretSicilNo($value){
        $this->getSchemeID("TICARETSICILNO")->setValue($value);    
    }
    public function setBayiNo($value){
        $this->getSchemeID("BAYINO")->setValue($value);    
    }

    public function &getSchemeID($schemeID,$createIfNot=false){
        $key = $this->getKeyForSchemeID($schemeID,true);
        return $this->list[$key];
    }
    public function getKeyForSchemeID($schemeID,$createIfNot=false){
        if(count($this->list) > 0){
            foreach($this->list as $key => $item){
                if($item instanceof PartyIdentification){
                    if(isset($item->id->attributes['schemeID']) && $item->id->attributes['schemeID'] === $schemeID){
                        return $key;
                    }
                }
            }
        }
        if($createIfNot){
            $partyId = new PartyIdentification();
            $partyId->id->attributes['schemeID'] = $schemeID;
            $this->add($partyId);
            return array_key_last($this->list);
        }        
        return null;
    }    
}
?>