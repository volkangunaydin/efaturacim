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
                ,"LineExtensionAmount"=>$obj->ubl->getLineExtensionAmount()
                ,"LineExtensionAmount_from_lines"=>$obj->ubl->getLineExtensionAmountFromLines()
                ,"TaxExclusiveAmount"=>$obj->ubl->getTaxExclusiveAmount()
                ,"TaxExclusiveAmount_from_lines"=>$obj->ubl->getTaxExclusiveAmountFromLines()
                ,"TaxInclusiveAmount"=>$obj->ubl->getTaxInclusiveAmount()
                ,"TaxInclusiveAmount_from_lines"=>$obj->ubl->getTaxInclusiveAmountFromLines()
                ,"AllowanceTotalAmount"=>$obj->ubl->getAllowanceTotalAmount()
                ,"AllowanceTotalAmount_from_lines"=>$obj->ubl->getAllowanceTotalAmountFromLines()
                ,"ChargeTotalAmount"=>$obj->ubl->getChargeTotalAmount()
                ,"ChargeTotalAmount_from_lines"=>$obj->ubl->getChargeTotalAmountFromLines()
                ,"PayableAmount"=>$obj->ubl->getPayableAmount()
                ,"PayableAmount_from_lines"=>$obj->ubl->getPayableAmountFromLines()
                ,"kdv"=>$obj->ubl->getVatsAsArray()
            );                        
        }else{
            $arrDebug = array(
                "belge_turu"=>"?"
                ,"error"=>"BELGE TÜRÜ ANLAŞILAMADI"
            );            
        }
        echo "<pre>";
        print_r($arrDebug);
        echo "</pre>";
        die();
    }    
}
?>