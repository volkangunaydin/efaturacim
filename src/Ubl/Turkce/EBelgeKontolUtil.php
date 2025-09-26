<?php
namespace Efaturacim\Util\Ubl\Turkce;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Ubl\CreditNoteDocument;
use Efaturacim\Util\Ubl\DespatchAdviceDocument;
use Efaturacim\Util\Ubl\InvoiceDocument;


class EBelgeKontolUtil{
    public static function getKontrolResult($ubl){
        $r = new SimpleResult();
        if($ubl && $ubl instanceof InvoiceDocument){
            return self::getKontrolResultForInvoice($ubl);
        }else if($ubl && $ubl instanceof DespatchAdviceDocument){
            return self::getKontrolResultForDespatchAdvice($ubl);
        }else if($ubl && $ubl instanceof CreditNoteDocument){
            return self::getKontrolResultForCreditNote($ubl);
        }else{
            $r->setIsOk(false);
            $r->addError("Ubl geçersiz.");
        }
        return $r;
    }
    /**
     * Summary of getKontrolResultForInvoice
     * @param InvoiceDocument $ubl
     * @return SimpleResult
     */
    public static function getKontrolResultForInvoice($ubl){
        $r = new SimpleResult();        
        if($ubl && $ubl instanceof InvoiceDocument){
            $r->setIsOk(true);
            $r->setAttribute("belge_turu","efatura");
            $r->setAttribute("belge_no",$ubl->getDocNo());
            $r->setAttribute("belge_tarihi",$ubl->getIssueDate());
            $r->setAttribute("belge_saati",$ubl->getIssueTime());
            $r->setAttribute("belge_doviz_kodu",$ubl->getDocumentCurrencyCode());
            $r->setAttribute("belge_profili",$ubl->getProfileId());
            $r->setAttribute("gonderen_vkn",$ubl->getSenderTaxNumber());
            $r->setAttribute("alici_vkn",$ubl->getCustomerTaxNumber());
            $r->setAttribute("belge_tutari",$ubl->getPayableAmount());            
        }
        return $r;
    }
    /**
     * Summary of getKontrolResultForDespatchAdvice
     * @param DespatchAdviceDocument $ubl
     * @return SimpleResult
     */
    public static function getKontrolResultForDespatchAdvice($ubl){
        $r = new SimpleResult();
        if($ubl && $ubl instanceof DespatchAdviceDocument){
            $r->setIsOk(true);
            $r->addSuccess("Ubl geçerli.");
        }
        return $r;
    }
    /**
     * Summary of getKontrolResultForCreditNote
     * @param CreditNoteDocument $ubl
     * @return SimpleResult
     */
    public static function getKontrolResultForCreditNote($ubl){
        $r = new SimpleResult();
        if($ubl && $ubl instanceof CreditNoteDocument){
            $r->setIsOk(true);
            $r->addSuccess("Ubl geçerli.");
        }
        return $r;
    }
}
?>