<?php
namespace Efaturacim\Util\Orkestra\Soap\Util;

use Efaturacim\Util\Orkestra\Database\OrkestraTables;
use Efaturacim\Util\Utils\String\StrContains;
use Efaturacim\Util\Utils\String\StrUtil;

class OrkestraWorkProducts{
public static $OBJECTS = array();
public static $ALIAS   = array();
public static function isClassString($clsName){
    if(StrUtil::notEmpty($clsName) && is_string($clsName) && strlen("".$clsName)>0){
        if(substr("".$clsName, 0,4)=="com."){
            return true;
        }
    }
    return false;
}
    public static function isClassHashString($clsName){
        if(is_string($clsName) && substr($clsName,-1)=="L"){
            $clsName = substr($clsName,0,-1);
        }
        if(is_string($clsName) && substr($clsName,0,1)=="-"){
            $clsName = substr($clsName,1);
        }
        if(is_int($clsName)){
            return true;
        }else if( (StrUtil::onlyNumeric($clsName)."")===("".$clsName) && strlen("".$clsName)>0 ){
                return true;
        }
        return false;
    }
    public static function getWpClassName($className){
        if($className && is_string($className) && strlen("".$className)>0){
            $wp = self::getWp($className);
            if($wp && count($wp)>0 && strlen("".@$wp["wp"])>0){
                return @$wp["wp"];
            }                
        }
        return "";
    }
    protected static function initIfNot(){
        if(!self::$OBJECTS || count(self::$OBJECTS)==0){
            self::initObjects();
        }
    }
    protected static function addWp($mainName,$desc,$table,$hash,$wp,$aliasArray=null,$resimHash=null){
        if(StrUtil::notEmpty($mainName)){                
            self::$ALIAS[$mainName]   = $mainName;                
            self::$OBJECTS[$mainName] = array("name"=>$mainName,"desc"=>$desc,"table"=>$table,"hash"=>$hash,"hash2"=>$resimHash,"wp"=>$wp,"alias"=> is_array($aliasArray) ? $aliasArray : array() );
            if(StrUtil::notEmpty($hash)){ self::$ALIAS[$hash] =$mainName; }                
            if(StrUtil::notEmpty($table)){ self::$ALIAS[$table] =$mainName; }
            if(StrUtil::notEmpty($wp)){ self::$ALIAS[$wp] =$mainName; }
            if($aliasArray && is_array($aliasArray)){
                foreach ($aliasArray as $alias){ self::$ALIAS[$alias] = $mainName; }
            }
        }
    }
    protected static function getWp($className){
        self::initIfNot();
        if(key_exists($className, self::$ALIAS) && key_exists(self::$ALIAS[$className], self::$OBJECTS)){
            return self::$OBJECTS[self::$ALIAS[$className]];
        }
        $className = StrUtil::toVarName($className,"");
        if(key_exists($className, self::$ALIAS) && key_exists(self::$ALIAS[$className], self::$OBJECTS)){
            return self::$OBJECTS[self::$ALIAS[$className]];
        }            
        return array();
    }
    public static function getWpInfo($className=null,$nesneHash=null){
        $r = self::getWp($className."");
        if(count($r)==0 && StrUtil::notEmpty($nesneHash)){
            $r2 = self::getWp($nesneHash."");
            if(count($r2)>0){
                return $r2;
            }
        }            
        return $r;
    }
    public static function getClassHashFromName($class=null,$getHash2=false){
        self::initIfNot();
        $a = self::getWp($class);
        if(count($a)>0){
            if($getHash2){
                return StrUtil::coalesce(@$a["hash2"],@$a["hash"]);
            }else{
                return @$a["hash"];
            }                                
        }
        return "";
    }
    public static function getClassHashForImage($class=null){
        self::initIfNot();
        $a = self::getWp($class);
        if(count($a)>0){
            return StrUtil::coalesce(@$a["hash2"],@$a["hash"]);
        }
        return "";
    }
    public static function ensureClassHash(&$hash){
        if($hash=="user"){
            $hash = "-580283046700766986";
        }
    }
    public static function initObjects(){
        self::addWp("user","Sistem Kullanıcıları","G_USER","-580283046700766986","com.gtech.relax.system.objects.User",array("kullanici"));
        self::addWp("ozellik_seti","Özellik Setleri","G_FEATURESET","864457096955562020","com.gtech.relax.system.feature.DbEntityFeatureSet",array("feature_set","featureset"));
        self::addWp("ozellik_seti_kayit_degeri","Kayıt Özellik Değer Setleri","G_FEATUREVALSET","7300366488013670912","com.gtech.relax.system.feature.DbEntityFeatureValueSet",array("feature_value_set","feature_val_set"));
        self::addWp("kayit_ozellik_degeri","Kayıt Özellik Değerleri","G_FEATUREVAL","-4615617910872411391","com.gtech.relax.system.feature.DbEntityFeatureValue",array("ozellik_degeri"));
        self::addWp("ozellik_set_degeri","Kayıt Özellik Değerleri","G_FEATSETENTRY","-1656780155769228222","com.gtech.relax.system.feature.DbEntityFeatureSetEntry",array("ozellik_seti_degeri"));
        self::addWp("finansal_donem","Finansal Dönemler","E_LEGBIZPERIOD","-8986340813552277234","com.gtech.erp.ledger.objects.FinancialPeriod",array("donem"));
        self::addWp("urun_marka","Marka Kartları","I_BRAND","-8915889420559099046","com.gtech.erp.inventory.objects.StockItemBrand",array("marka"));
        self::addWp("cek_hesaplari","Çek Hesapları","F_CHEQUEACC","-8558465779876187119","com.gtech.erp.deposit.objects.ChequeOrBillAccount",array("cek_hesap"));
        self::addWp("satis_faturasi","Satış Faturası","L_INVOICE","-1266355059522092722","com.gtech.erp.logistics.objects.InvoiceVoucherSales",array("fatura_satis"));
        self::addWp("satinalma_faturasi","Satınalma Faturası","L_INVOICE","2757714314426076191","com.gtech.erp.logistics.objects.InvoiceVoucherPurchase",array("fatura_alim","fatura_satinalma"));
        self::addWp("fatura","Faturalar","L_INVOICE","-8455883601618574196","com.gtech.erp.logistics.objects.InvoiceVoucher",array("faturalar"));
        self::addWp("muhasebe_hesabi","Muhasebe Hesabı","E_ACCOUNT","-1190654119589687295","com.gtech.erp.ledger.objects.LedgerAccount",array("muhasebe_hesap"));
        self::addWp("muhasebe_fisi","Muhasebe Fişi","E_LEDGERVOUCHER","5327252671611760259","com.gtech.erp.ledger.objects.LedgerVoucher",array("muhasebe_fis"));            
        self::addWp("cek_senet_kartlari","Çek/Senet Kartları","F_CHEQUEORBILL","-8395409836852203514","com.gtech.erp.deposit.objects.ChequeOrBill",array("cek","senet"));
        self::addWp("marka_ürün_modelleri","Marka Ürün Modelleri","I_BRANDMODEL","-7982118907723676099","com.gtech.erp.inventory.objects.StockItemBrandModel",array());
        self::addWp("fatura_satirlari","Fatura Satırları","L_INVOICELINE","-6000090984001716055","com.gtech.erp.logistics.objects.InvoiceLine",array());
        self::addWp("irsaliye_satirlari","İrsaliye Satırları","L_SHIPMENTLINE","-5701712329605685447","com.gtech.erp.logistics.objects.ShipmentLine",array());
        self::addWp("kdv_oran_tanimlari","KDV Oran Tanımları","G_VATRATE","-5546438977131337322","com.gtech.erp.base.objects.VatRateDefinition",array());
        self::addWp("birim_tanimlari","Birim Tanımları","G_UOM","-5465739505497107931","com.gtech.relax.crmbase.objects.UnitOfMeasure",array());
        self::addWp("sözlesmeler","Sözleşmeler","F_CONTRACT","-4454398605539459982","com.gtech.erp.deposit.objects.FinContract",array());
        self::addWp("finansal_islem_fisleri","Finansal İşlem Fişleri","F_FINVOUCHER","-4387638720710198132","com.gtech.erp.deposit.objects.FinVoucher",array("Finansal İşlem Fişi"));
        //self::addWp("finansal_islem_fisleri","Finansal İşlem Fişleri","F_FINVOUCHER","-4387638720710198132","com.gtech.erp.deposit.objects.FinVoucher",array());
        //self::addWp("finansal_islem_fisleri","Finansal İşlem Fişleri","F_FINVOUCHER","-4387638720710198132","com.gtech.erp.deposit.objects.FinVoucher",array());
        //self::addWp("finansal_islem_fisleri","Finansal İşlem Fişleri","F_FINVOUCHER","-4387638720710198132","com.gtech.erp.deposit.objects.FinVoucher",array());
        self::addWp("GTIP_kodlari","GTİP Kodları","I_GTIP","-4192977124456679460","com.gtech.erp.inventory.objects.GtipCode",array());
        self::addWp("iller","İller","G_CITY","-3662988630698426489","com.gtech.relax.global.objects.City",array());
        self::addWp("ülkeler","Ülkeler","G_COUNTRY","-3659755579548969025","com.gtech.relax.global.objects.Country",array());
        self::addWp("yasal_kurumlar","Yasal Kurumlar","G_LEGALENTITY","-3474268022775208008","com.gtech.erp.base.objects.LegalEntity",array());
        self::addWp("tahakkuk_kartlari","Tahakkuk Kartları","F_ACCRURALTYPE","-3311669324455459232","com.gtech.erp.deposit.objects.FinAccruralCard",array());
        self::addWp("sözlesme_satirlari","Sözleşme Satırları","L_CONTRACTLINE","-3268617565967224277","com.gtech.erp.logistics.objects.ContractItemLine",array());
        self::addWp("ürün_paketleri","Ürün Paketleri","I_ITEMUNIT","-3243740239266996236","com.gtech.erp.inventory.objects.StockItemUnit",array());
        self::addWp("ürün_paketleri","Ürün Paketleri","I_ITEMUNIT","-3243740239266996236","com.gtech.erp.inventory.objects.StockItemUnit",array());
        self::addWp("ürün_hizmet_gruplari","Ürün/Hizmet Grupları","I_ITEMGRP","-2672007779094186885","com.gtech.erp.inventory.objects.StockItemGroup",array());
        self::addWp("kullanici_gruplari","Kullanıcı Grupları","G_USERGRP","-2565418676263038434","com.gtech.relax.system.objects.UserGroup",array());
        self::addWp("urun_baz","Ürün Baz","I_ITEM","-2322136438331555899","com.gtech.erp.inventory.objects.StockItemBase",array(),"-2322136438331555899");
        
        //self::addWp("ürün_hizmet_kartlari","Ürün/Hizmet Kartları","I_ITEM","-1166042124495742316","com.gtech.erp.inventory.objects.StockItem",array()); // 27 -2322136438331555899- uyuşmuyor
        self::addWp("ürün_hizmet_kartlari","Ürün/Hizmet Kartları","I_ITEM","-1166042124495742316","com.gtech.erp.inventory.objects.StockItem",array("urun"),"-2322136438331555899"); // 28 -2322136438331555899- uyuşmuyor
        self::addWp("banka_kartlari","Banka Kartları","F_BANKCARD","-2190275461940920082","com.gtech.erp.deposit.objects.FinBankCard",array());
        self::addWp("teklif_fisleri","Teklif Fişleri","L_TENDERVOUCHER","-1909512949293360191","com.gtech.erp.logistics.objects.TenderVoucher",array("teklif"));
        self::addWp("teklif_satis","Satış Teklifleri","L_TENDERVOUCHER","-8561079772885827354","com.gtech.erp.logistics.objects.TenderVoucher",array("satis_teklifi","satis_teklif"),"-1909512949293360191");
        self::addWp("masraf_merkezleri","Masraf Merkezleri","E_CC","-1772096942101472072","com.gtech.erp.ledger.objects.CostCenterAccount",array());
        self::addWp("ürün_hizmet_muhasabe_baglantisi","Ürün/Hizmet Muhasebe Bağlantıları","L_ITEMACCPLAN","45393063300549927","com.gtech.erp.logistics.objects.StockItemAccPlanBind",array());
        self::addWp("ambarlar","Ambarlar","I_FACILITY","60966854043525479","com.gtech.erp.inventory.objects.Facility",array());
        self::addWp("teklif_satirlari","Teklif Satırları","L_TENDERLINE","98993282306631488","com.gtech.erp.logistics.objects.TenderLine",array("teklif_satiri","teklif_satir"));
        self::addWp("hesap_planlari","Hesap Planları","E_ACCPLAN","2051537400772326105","com.gtech.erp.ledger.objects.LedgerAccountPlan",array());
        self::addWp("para_birimleri","Para Birimleri","G_CURRENCY","2361961901091992465","com.gtech.erp.base.objects.Currency",array());
        self::addWp("banka_hesaplari","Banka Hesapları","F_BANKACCOUNT","2643778108043254516","com.gtech.erp.deposit.objects.FinBankAccount",array());
        self::addWp("banka_hesaplari","Banka Hesapları","F_BANKACCOUNT","2643778108043254516","com.gtech.erp.deposit.objects.FinBankAccount",array());
        self::addWp("kasa_hesaplari","Kasa Hesapları","F_CASHDEPOSIT","2998076683502597360","com.gtech.erp.deposit.objects.FinSafeDeposit",array());
        self::addWp("cek_senet_bordrolari","Çek/Senet Bordroları","F_CHEQUEVOUCHER","3435682196970506899","com.gtech.erp.deposit.objects.ChequeOrBillVoucher",array());
        self::addWp("ödeme_planlari","Ödeme Planları","F_PAYTEMPLATE","3564807436755242379","com.gtech.erp.deposit.objects.FinPaymentPlan",array());
        self::addWp("siparis_satirlari","Sipariş Satırları","L_ORDERLINE","3966560982516611147","com.gtech.erp.logistics.objects.OrderLine",array());
        self::addWp("ilceler","İlçeler","G_TOWN","4029073425514509076","com.gtech.relax.global.objects.Town",array());
        self::addWp("birim_set_tanimlari","Birim Seti Tanımları","G_UOMSET","4318516725905326880","com.gtech.relax.crmbase.objects.UnitOfMeasureSet",array());
        self::addWp("personel_listesi","Personel Listesi","C_PERSONNEL","5973068481368840632","com.gtech.relax.crmbase.objects.Personnel",array("personel"));            
        self::addWp("irsaliyeler","İrsaliyeler","L_SHIPMENT","7603787256310896652","com.gtech.erp.logistics.objects.ShipmentVoucher",array("irsaliye"));
        self::addWp("is_birimleri","İş Birimleri","G_LEGBIZUNIT","7949279114330525260","com.gtech.erp.base.objects.BusinessUnit",array());
        self::addWp("cari_hesaplar","Cari Hesaplar","F_ARAP","8184989181188640652","com.gtech.erp.deposit.objects.FinParty",array("cari","cari_hesap"));
        self::addWp("malzeme_fisleri","Malzeme Fişleri","I_ITEMVOUCHER","8242423001183185721","com.gtech.erp.inventory.objects.StockItemVoucher",array("malzemefisi","malzeme_fisi"));
        self::addWp("siparis_fisleri","Sipariş Fişleri","L_ORDERVOUCHER","6277441113113142168","com.gtech.erp.logistics.objects.OrderVoucher",array("siparis"));
        self::addWp("satinalma_siparisi","Satınalma Siparişi","L_ORDERVOUCHER","-7432064926775085606","com.gtech.erp.logistics.objects.OrderVoucher",array("siparis_satinalma"),"6277441113113142168");
        self::addWp("satis_siparisi","Satış Siparişi","L_ORDERVOUCHER","4847864065105600896","com.gtech.erp.logistics.objects.OrderVoucher",array("siparis_satis"),"6277441113113142168");
        self::addWp("izin_islemi","İzin İşlemi","HR_PRVACLINE","1268282654888569767","com.gtech.erp.hr.objects.PrVacationLine",array("izin_islem","izin_kullanim"));
        self::addWp("izin_tanimi","İzin Tanımı","HR_VACATIONTYPE","8761508882424117186","com.gtech.erp.hr.objects.HrVacationType",array("izin_tanim"));
        self::addWp("masraf_merkezi","Masraf Merkezi","E_CC","-1772096942101472072","com.gtech.erp.ledger.objects.CostCenterAccount",array("masrafmerkezi","mm"));
        self::addWp("sayim","Sayım Fişi","I_COUNTINGVOUCHER","-4782522776658706708","com.gtech.erp.inventory.objects.FacilityStockCountingVoucher",array("sayim_fisi","sayimfisi"));
        self::addWp("firma","Firma","C_CONTACTFIRM","2929108029941341592","com.gtech.relax.crmbase.objects.ContactFirm",array("contact_firm","firm"));
        self::addWp("talep","TALEP",OrkestraTables::$TALEP_FISI,"-302717422851158687","com.gtech.erp.logistics.objects.DemandVoucher",array("talep_fisi"));
        self::addWp("yetki_paketi","YETKİ PAKETİ",OrkestraTables::$YETKI_PAKETI,"-3231671541007869350","com.gtech.relax.system.objects.UserRights",array("yetki"));
        self::addWp("amortisman_islem_satiri","Amortisman İşlem Satırları",OrkestraTables::$AMORTISMAN_ISLEM_SATIRI,"-6995684288505147790","com.gtech.erp.asset.objects.DepreciationPeriod",array("depreciationperiod"));
        self::addWp("amortisman_karti","Amortisman Kartı",OrkestraTables::$AMORTISMAN_KARTI,"-1429998703640194465","com.gtech.erp.asset.objects.DepreciationCard",array("depreciationcard"));
        self::addWp("ilgili_kisi","Kişi","C_CONTACTPERSON","5776501189513176250","com.gtech.relax.crmbase.objects.ContactPerson",array("kisi"));
        self::addWp("gorev","Görev","C_TASK","8605152053156763111","com.gtech.relax.task.objects.TaskInstance",array("gorevler"));
        self::addWp("eposta_hesabi","E-POSTA HESABI","C_GWMAILACCOUNT","4618262138914011079","com.gtech.relax.gw.objects.UserMailAccount",array("posta_hesabi"));
            
        
        //\Vulcan\V::dump(self::$ALIAS);
        
    }
    public static function isClassSiparis($className){
        return $className=="com.gtech.erp.logistics.objects.OrderVoucher";
    }
    public static function isClassFatura($className){
        return in_array("".$className, array("com.gtech.erp.logistics.objects.InvoiceVoucherSales","com.gtech.erp.logistics.objects.InvoiceVoucherPurchase","com.gtech.erp.logistics.objects.InvoiceVoucher"));
    }
    public static function isOrkestraWorkProductClass($s){
        if($s && is_string($s) && StrContains::startsWith($s, "com.gtech.")){
            return true;
        }
        return false;
    }
    public static function getClassHashString($a){
        $a = trim("".$a);
        if(strlen("".$a)>5){
            $a = str_replace("L","",$a);
            return $a;
        }
        return "";
    }
    public static function isClassHashEquals($a,$b){
        $a = self::getClassHashString($a);
        $b = self::getClassHashString($b);
        if(strlen("".$a)>0 && strlen("".$b)>0){
            if($a==$b){
                return true;
            }
        }
        return false;
    }
}


?>