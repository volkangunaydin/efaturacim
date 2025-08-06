<?php
namespace Efaturacim\Util\Ubl\Samples;

use Efaturacim\Util\ArrayUtil;
use Efaturacim\Util\IO\IO_Util;
use Efaturacim\Util\Ubl\Objects\InvoiceLine;
use Efaturacim\Util\Ubl\Objects\PartyIdentification;
use Efaturacim\Util\Ubl\Objects\PartyName;
use Efaturacim\Util\Ubl\Turkce\EFaturaBelgesi;
use PDO;
use Vulcan\Base\Debug\DebugUtil;
use Vulcan\Base\Util\StringUtil\StrSerialize;

class EFaturaSamples{
    public static function callDebugAction(){
        $extraOptions = array("fatura_no"=>"TST2025000000001","uid"=>"f499cac5-7fee-4f67-9c48-f760c11ca83e");
        $efatura = EFaturaSamples::newFatura("",$extraOptions);
        $efatura->ubl->addLineFromArray(array("id"=>1,"InvoicedQuantity"=>5,"invoicedQuantityUnitCode"=>"NIU","LineExtensionAmount"=>100,"birim_fiyat"=>100,"kdv_orani"=>20));
        $efatura->showAsXml();        
    }
    /**
     * @param mixed $template
     * @return EFaturaBelgesi
     */
    public static function newFatura($template="",$extraArray=null){
        $efatura = new EFaturaBelgesi();
        if($template=="none"){
        }else{
            $efatura->setSaticiBilgileri([
                "unvan" => "G YAZILIM LTD",
                "vkn" => "3880628557",
                "mersis"=>"0388062855700013",
                "ticari_sicil"=>"276743",
                "vergi_dairesi" => "Segmenler",
                "sokak" => "Halit Ziya Cad",
                "bina" => "19",
                "ilce" => "CANKAYA",
                "il" => "ANKARA",
                "telefon" => "0850 420 2344",
                "eposta" => "orkestra@orkestra.com.tr",
                "web" => "www.orkestra.com.tr"
            ]);

            $efatura->setAliciBilgileri([
                "unvan" => "GUNAYDIN OTOMOTIV - VOLKAN GUNAYDIN",
                "ad"=>"Volkan",
                "soyad"=>"Gunaydin",
                "vkn" => "11111111111",
                "vergi_dairesi" => "Baskent",
                "sokak" => "Halit Ziya Cad",
                "bina" => "19/4",
                "ilce" => "CANKAYA",
                "il" => "ANKARA",
                "telefon" => "0535 555 4979",
                "eposta" => "volkan@orkestra.com.tr",
                "web" => "www.gyazilim.com"
            ]);            
            if(ArrayUtil::notEmpty(arr: $extraArray)){
                $efatura->ubl->loadFromArray($extraArray);
            }            
        }
        return $efatura;
    }
    public static function getCurrentDebugFatura(){
        $debug = false;
        if(false){                        
            //$p = new InvoiceLine(StrSerialize::unserializeBase64('YTo4OntzOjI6IklEIjtzOjE6IjEiO3M6MTY6Ikludm9pY2VkUXVhbnRpdHkiO2E6Mjp7czoxMToiQGF0dHJpYnV0ZXMiO2E6MTp7czo4OiJ1bml0Q29kZSI7czozOiJDNjIiO31zOjY6IkB2YWx1ZSI7czoxOiIxIjt9czoxOToiTGluZUV4dGVuc2lvbkFtb3VudCI7YToyOntzOjExOiJAYXR0cmlidXRlcyI7YToxOntzOjEwOiJjdXJyZW5jeUlEIjtzOjM6IkVVUiI7fXM6NjoiQHZhbHVlIjtzOjU6IjIwMTUwIjt9czoxNToiQWxsb3dhbmNlQ2hhcmdlIjthOjM6e3M6MTU6IkNoYXJnZUluZGljYXRvciI7czo0OiJ0cnVlIjtzOjIzOiJNdWx0aXBsaWVyRmFjdG9yTnVtZXJpYyI7czo2OiIwLjAwNzUiO3M6NjoiQW1vdW50IjthOjI6e3M6MTE6IkBhdHRyaWJ1dGVzIjthOjE6e3M6MTA6ImN1cnJlbmN5SUQiO3M6MzoiRVVSIjt9czo2OiJAdmFsdWUiO3M6MzoiMTUwIjt9fXM6ODoiVGF4VG90YWwiO2E6Mjp7czo5OiJUYXhBbW91bnQiO2E6Mjp7czoxMToiQGF0dHJpYnV0ZXMiO2E6MTp7czoxMDoiY3VycmVuY3lJRCI7czozOiJFVVIiO31zOjY6IkB2YWx1ZSI7czo3OiIyMDAwLjAwIjt9czoxMToiVGF4U3VidG90YWwiO2E6NDp7czoxMzoiVGF4YWJsZUFtb3VudCI7YToyOntzOjExOiJAYXR0cmlidXRlcyI7YToxOntzOjEwOiJjdXJyZW5jeUlEIjtzOjM6IkVVUiI7fXM6NjoiQHZhbHVlIjtzOjU6IjIwMDAwIjt9czo5OiJUYXhBbW91bnQiO2E6Mjp7czoxMToiQGF0dHJpYnV0ZXMiO2E6MTp7czoxMDoiY3VycmVuY3lJRCI7czozOiJFVVIiO31zOjY6IkB2YWx1ZSI7czo3OiIyMDAwLjAwIjt9czo3OiJQZXJjZW50IjtzOjI6IjEwIjtzOjExOiJUYXhDYXRlZ29yeSI7YToxOntzOjk6IlRheFNjaGVtZSI7YToyOntzOjQ6Ik5hbWUiO3M6MzoiS0RWIjtzOjExOiJUYXhUeXBlQ29kZSI7czo0OiIwMDE1Ijt9fX19czoxOToiV2l0aGhvbGRpbmdUYXhUb3RhbCI7YToyOntzOjk6IlRheEFtb3VudCI7YToyOntzOjExOiJAYXR0cmlidXRlcyI7YToxOntzOjEwOiJjdXJyZW5jeUlEIjtzOjM6IkVVUiI7fXM6NjoiQHZhbHVlIjtzOjQ6IjE4MDAiO31zOjExOiJUYXhTdWJ0b3RhbCI7YTo0OntzOjEzOiJUYXhhYmxlQW1vdW50IjthOjI6e3M6MTE6IkBhdHRyaWJ1dGVzIjthOjE6e3M6MTA6ImN1cnJlbmN5SUQiO3M6MzoiRVVSIjt9czo2OiJAdmFsdWUiO3M6NDoiMjAwMCI7fXM6OToiVGF4QW1vdW50IjthOjI6e3M6MTE6IkBhdHRyaWJ1dGVzIjthOjE6e3M6MTA6ImN1cnJlbmN5SUQiO3M6MzoiRVVSIjt9czo2OiJAdmFsdWUiO3M6NDoiMTgwMCI7fXM6NzoiUGVyY2VudCI7czoyOiI5MCI7czoxMToiVGF4Q2F0ZWdvcnkiO2E6MTp7czo5OiJUYXhTY2hlbWUiO2E6Mjp7czo0OiJOYW1lIjtzOjQyOiJUZW1pemxpayBoaXptZXRpICpHVCAxMTctQsO2bMO8bSAoMy4yLjEwKSsiO3M6MTE6IlRheFR5cGVDb2RlIjtzOjM6IjYxMiI7fX19fXM6NDoiSXRlbSI7YTo0OntzOjQ6Ik5hbWUiO3M6MTA6IsOWUk5FSyBLT0QiO3M6MjQ6IkJ1eWVyc0l0ZW1JZGVudGlmaWNhdGlvbiI7YToxOntzOjI6IklEIjtzOjEwOiLDllJORUsgS09EIjt9czoyNToiU2VsbGVyc0l0ZW1JZGVudGlmaWNhdGlvbiI7YToxOntzOjI6IklEIjtzOjI5OiJUZXY6IEdvb2R5ZWFyIE90byBMYXN0aWsgOS8xMCI7fXM6MzE6Ik1hbnVmYWN0dXJlcnNJdGVtSWRlbnRpZmljYXRpb24iO2E6MTp7czoyOiJJRCI7czoxMDoiw5ZSTkVLIEtPRCI7fX1zOjU6IlByaWNlIjthOjE6e3M6MTE6IlByaWNlQW1vdW50IjthOjI6e3M6MTE6IkBhdHRyaWJ1dGVzIjthOjE6e3M6MTA6ImN1cnJlbmN5SUQiO3M6MzoiRVVSIjt9czo2OiJAdmFsdWUiO3M6NToiMjAwMDAiO319fQ=='),$debug);
            $p = new InvoiceLine(array("id"=>123,"InvoicedQuantity"=>5,"invoicedQuantityUnitCode"=>"NIU","LineExtensionAmount"=>array("@attributes"=>array("currencyID"=>"USD"),"@value"=>1234)),$debug);
            $p->showAsXml();
        }

        return EFaturaBelgesi::smart(IO_Util::readFileAsString("C:/Users/volka/OneDrive/Desktop/EAM2025000000086.xml"),null,$debug);
    }
    public static function getJsonForStdFatura(){
        return '{
    "ublVersionId": "2.1",
    "customizationId": "TR1.2",
    "profileId": "TICARIFATURA",
    "id": "FAT2025000000001",
    "uuid": "0aa4ee26-16a8-4621-a0c7-402b0e61b0f4",
    "issueDate": "2025-07-20",
    "issueTime": "11:20:48",
    "rootElementName": "Invoice",
    "documentCurrencyCode": "TRY",
    "invoiceTypeCode": "SATIS",
    "accountingCustomerParty": {
        "websiteURI": "www.gyazilim.com",
        "partyName": "Volkan GUNAYDIN LTD",
        "postalAddress": {
            "streetName": "Halit Ziya Cad ",
            "buildingNumber": "19\/4",
            "cityName": "ANKARA",
            "postalZone": null,
            "citySubdivisionName": "CANKAYA",
            "country": {
                "identificationCode": "TR",
                "name": "TURKIYE"
            }
        },
        "partyIdentification": {
            "id": "11111111111",
            "schemeID": "TCKN"
        },
        "partyTaxScheme": {
            "taxScheme": {
                "name": "Baskent"
            }
        },
        "contact": {
            "telephone": "0535 555 4979",
            "telefax": null,
            "electronicMail": "volkan@orkestra.com.tr"
        }
    },
    "accountingSupplierParty": {
        "websiteURI": "www.orkestra.com.tr",
        "partyName": "G YAZILIM LTD",
        "postalAddress": {
            "streetName": "Halit Ziya Cad ",
            "buildingNumber": "19",
            "cityName": "ANKARA",
            "postalZone": null,
            "citySubdivisionName": "CANKAYA",
            "country": {
                "identificationCode": "TR",
                "name": "TURKIYE"
            }
        },
        "partyIdentification": {
            "id": "3880628557",
            "schemeID": "VKN"
        },
        "partyTaxScheme": {
            "taxScheme": {
                "name": "Segmenler"
            }
        },
        "contact": {
            "telephone": "0850 420 2344",
            "telefax": null,
            "electronicMail": "orkestra@orkestra.com.tr"
        }
    }
}';
    }
    public static function getArrayForStdFatura(){
        return array(
            "profileId"=>"TEMELFATURA"
            ,"fatura_no"=>"ARR2025000000002"
            ,"satici"=>array("unvan"=>"G YAZILIM LTD","vkn"=>"3880628557","il"=>"ANKARA","ilce"=>"CANKAYA","sokak"=>"Halit Ziya Cad ","bina"=>"19")
            ,"alici"=>array("unvan"=>"VOLKAN GUNAYDIN","vkn"=>"65401211066","il"=>"ANKARA","ilce"=>"CANKAYA","sokak"=>"Halit Ziya Cad ","bina"=>"19")
            ,"notlar"=>array("Not 1","Not 2","Not 3")
            ,"satirlar"=>array(
                array("ad"=>"Urun 1","miktar"=>1,"birim"=>"C62","kdv"=>20,"birim_fiyat"=>100)
                ,array("ad"=>"Urun 2","miktar"=>4,"birim"=>"C62","kdv_tutari"=>100,"birim_fiyat"=>125)
            )
        );
    }
    public static function getJsonForStdFatura2(){
        return '{
    "ublVersionId": "2.1",
    "customizationId": "TR1.2",
    "profileId": "TEMELFATURA",
    "id": "ARR2025000000002",
    "uuid": "deee9b22-24f8-4bc1-968f-3815d445391f",
    "issueDate": "2025-07-24",
    "issueTime": "06:42:59",
    "rootElementName": "Invoice",
    "documentCurrencyCode": "TRY",
    "invoiceTypeCode": "SATIS",
    "accountingCustomerParty": {
        "party": {
            "websiteURI": null,
            "partyName": "VOLKAN GUNAYDIN",
            "postalAddress": {
                "streetName": "Halit Ziya Cad ",
                "buildingNumber": "19",
                "cityName": "ANKARA",
                "postalZone": null,
                "citySubdivisionName": "CANKAYA",
                "country": {
                    "identificationCode": "TR",
                    "name": "TURKIYE"
                }
            },
            "partyIdentification": {
                "id": "65401211066",
                "schemeID": "TCKN"
            },
            "partyTaxScheme": null,
            "contact": null
        }
    },
    "accountingSupplierParty": {
        "party": {
            "websiteURI": null,
            "partyName": "G YAZILIM LTD",
            "postalAddress": {
                "streetName": "Halit Ziya Cad ",
                "buildingNumber": "19",
                "cityName": "ANKARA",
                "postalZone": null,
                "citySubdivisionName": "CANKAYA",
                "country": {
                    "identificationCode": "TR",
                    "name": "TURKIYE"
                }
            },
            "partyIdentification": {
                "id": "3880628557",
                "schemeID": "VKN"
            },
            "partyTaxScheme": null,
            "contact": null
        }
    },
    "orderReference": [],
    "despatchDocumentReference": [],
    "note": [
        {
            "value": "Not 1"
        },
        {
            "value": "Not 2"
        },
        {
            "value": "Not 3"
        }
    ],
    "invoiceLine": [
        {
            "id": "1",
            "invoicedQuantity": 1,
            "invoicedQuantityUnitCode": "C62",
            "lineExtensionAmount": 100,
            "lineExtensionAmountCurrencyID": "TRY",
            "note": [],
            "allowanceCharge": [],
            "taxTotal": {
                "taxAmount": null,
                "taxAmountCurrencyID": "TRY",
                "taxSubtotal": [
                    {
                        "taxableAmount": 100,
                        "percent": 20,
                        "taxableAmountCurrencyID": "TRY",
                        "taxAmount": 20,
                        "taxAmountCurrencyID": "TRY",
                        "taxCategory": {
                            "name": null,
                            "percent": null,
                            "taxScheme": {
                                "name": "KDV",
                                "taxTypeCode": "0015"
                            }
                        }
                    }
                ]
            },
            "item": {
                "name": "Urun 1",
                "description": null,
                "sellersItemID": null
            },
            "price": {
                "priceAmount": 100,
                "priceAmountCurrencyID": "TRY",
                "baseQuantity": null,
                "baseQuantityUnitCode": "C62"
            }
        },
        {
            "id": "2",
            "invoicedQuantity": 4,
            "invoicedQuantityUnitCode": "C62",
            "lineExtensionAmount": 500,
            "lineExtensionAmountCurrencyID": "TRY",
            "note": [],
            "allowanceCharge": [],
            "taxTotal": {
                "taxAmount": null,
                "taxAmountCurrencyID": "TRY",
                "taxSubtotal": [
                    {
                        "taxableAmount": 500,
                        "percent": 0,
                        "taxableAmountCurrencyID": "TRY",
                        "taxAmount": 0,
                        "taxAmountCurrencyID": "TRY",
                        "taxCategory": {
                            "name": null,
                            "percent": null,
                            "taxScheme": {
                                "name": "KDV",
                                "taxTypeCode": "0015"
                            }
                        }
                    }
                ]
            },
            "item": {
                "name": "Urun 2",
                "description": null,
                "sellersItemID": null
            },
            "price": {
                "priceAmount": 125,
                "priceAmountCurrencyID": "TRY",
                "baseQuantity": null,
                "baseQuantityUnitCode": "C62"
            }
        }
    ]
}';
    }

}
?>