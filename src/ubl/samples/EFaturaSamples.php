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
}
?>