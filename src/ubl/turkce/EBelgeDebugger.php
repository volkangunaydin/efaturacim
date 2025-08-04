<?php
namespace Efaturacim\Util\Ubl\Turkce;
class EBelgeDebugger{
    public static function debug($obj){
        if($obj instanceof EFaturaBelgesi){
            $arrDebug = array(
                "belge_turu"=>"efatura"
                ,"belge_no"=>$obj->getBelgeNo()
                ,"belge_tarihi"=>$obj->getBelgeTarihi()->toDbDateTime()
                ,"satir_sayisi"=>$obj->getSatirSayisi()
                ,"gonderen"=>$obj->ubl->accountingSupplierParty->getName()
                ,"gonderen_vkn"=>$obj->ubl->accountingSupplierParty->getVknOrTckn()
                ,"alan"=>$obj->ubl->accountingCustomerParty->getName()
                ,"alan_vkn"=>$obj->ubl->accountingCustomerParty->getVknOrTckn()
                ,"LineExtensionAmount"=>$obj->ubl->legalMonetaryTotal->getLineExtensionAmount()
                ,"TaxExclusiveAmount"=>$obj->ubl->legalMonetaryTotal->getTaxExclusiveAmount()
                ,"TaxInclusiveAmount"=>$obj->ubl->legalMonetaryTotal->getTaxInclusiveAmount()
                ,"AllowanceTotalAmount"=>$obj->ubl->legalMonetaryTotal->getAllowanceTotalAmount()
                ,"ChargeTotalAmount"=>$obj->ubl->legalMonetaryTotal->getChargeTotalAmount()
                ,"PayableAmount"=>$obj->ubl->legalMonetaryTotal->getPayableAmount()
                ,"kdv"=>$obj->ubl->getVatsAsArray()
            );                        
        }else{
            $arrDebug = array(
                "belge_turu"=>"?"
                ,"error"=>"BELGE TÜRÜ ANLAŞILAMADI"
            );            
        }
        \Vulcan\V::dump($arrDebug);
    }    
}
?>