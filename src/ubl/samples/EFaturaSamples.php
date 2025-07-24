<?php
namespace Efaturacim\Util\Ubl\Samples;
class EFaturaSamples{
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