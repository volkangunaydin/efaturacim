<?php

namespace Efaturacim\Util\Ubl;

class ParseForArray
{
    public static function AccountingCustomerParty($array)
    {
        if (!isset($array['cac:AccountingCustomerParty'])) {
            return null;
        }

        $accountingCustomerParty = $array['cac:AccountingCustomerParty'];
        $party = $accountingCustomerParty['cac:Party'] ?? [];

        // Parse PartyIdentification with schemeID support (based on UblDataTypeListForPartyIdentification)
        $partyIdentification = [];
        if (isset($party['cac:PartyIdentification'])) {
            if (isset($party['cac:PartyIdentification'][0])) {
                // Multiple identifications
                foreach ($party['cac:PartyIdentification'] as $identification) {
                    $partyIdentification[] = [
                        'id' => $identification['cbc:ID'] ?? '',
                        'schemeID' => $identification['cbc:ID']['@schemeID'] ?? ''
                    ];
                }
            } else {
                // Single identification
                $partyIdentification[] = [
                    'id' => $party['cac:PartyIdentification']['cbc:ID'] ?? '',
                    'schemeID' => $party['cac:PartyIdentification']['cbc:ID']['@schemeID'] ?? ''
                ];
            }
        }

        // Parse PartyName (based on PartyName object)
        $partyName = '';
        if (isset($party['cac:PartyName']['cbc:Name'])) {
            $partyName = $party['cac:PartyName']['cbc:Name'];
        }

        // Parse PostalAddress (based on Address object)
        $postalAddress = [];
        if (isset($party['cac:PostalAddress'])) {
            $address = $party['cac:PostalAddress'];
            $postalAddress = [
                'streetName' => $address['cbc:StreetName'] ?? '',
                'buildingName' => $address['cbc:BuildingName'] ?? '',
                'buildingNumber' => $address['cbc:BuildingNumber'] ?? '',
                'citySubdivisionName' => $address['cbc:CitySubdivisionName'] ?? '',
                'cityName' => $address['cbc:CityName'] ?? '',
                'postalZone' => $address['cbc:PostalZone'] ?? '',
                'region' => $address['cbc:Region'] ?? '',
                'district' => $address['cbc:District'] ?? '',
                'country' => [
                    'identificationCode' => $address['cac:Country']['cbc:IdentificationCode'] ?? '',
                    'name' => $address['cac:Country']['cbc:Name'] ?? ''
                ]
            ];
        }

        // Parse PartyTaxScheme (based on PartyTaxScheme object)
        $partyTaxScheme = '';
        if (isset($party['cac:PartyTaxScheme']['cac:TaxScheme']['cbc:Name'])) {
            $partyTaxScheme = $party['cac:PartyTaxScheme']['cac:TaxScheme']['cbc:Name'];
        }

        // Parse Contact (based on Contact object)
        $contact = [];
        if (isset($party['cac:Contact'])) {
            $contactData = $party['cac:Contact'];
            $contact = [
                'telephone' => $contactData['cbc:Telephone'] ?? '',
                'telefax' => $contactData['cbc:Telefax'] ?? '',
                'electronicMail' => $contactData['cbc:ElectronicMail'] ?? ''
            ];
        }

        // Parse WebsiteURI (based on Party object)
        $websiteURI = $party['cbc:WebsiteURI'] ?? '';

        // Parse PartyLegalEntity (based on Party object)
        $partyLegalEntity = [];
        if (isset($party['cac:PartyLegalEntity']['cbc:RegistrationName'])) {
            $partyLegalEntity = [
                'registrationName' => $party['cac:PartyLegalEntity']['cbc:RegistrationName'] ?? ''
            ];
        }

        // Parse Person (based on Party object)
        $person = [];
        if (isset($party['cac:Person'])) {
            $personData = $party['cac:Person'];
            $person = [
                'firstName' => $personData['cbc:FirstName'] ?? '',
                'familyName' => $personData['cbc:FamilyName'] ?? '',
                'middleName' => $personData['cbc:MiddleName'] ?? '',
                'jobTitle' => $personData['cbc:JobTitle'] ?? ''
            ];
        }

        return [
            'partyIdentification' => $partyIdentification,
            'partyName' => $partyName,
            'postalAddress' => $postalAddress,
            'partyTaxScheme' => $partyTaxScheme,
            'contact' => $contact,
            'websiteURI' => $websiteURI,
            'partyLegalEntity' => $partyLegalEntity,
            'person' => $person
        ];
    }

    public static function AccountingSupplierParty($array)
    {
        if (!isset($array['cac:AccountingSupplierParty'])) {
            return null;
        }

        $accountingSupplierParty = $array['cac:AccountingSupplierParty'];
        $party = $accountingSupplierParty['cac:Party'] ?? [];

        // Parse PartyIdentification (can be array of multiple IDs)
        $partyIdentification = [];
        if (isset($party['cac:PartyIdentification'])) {
            if (isset($party['cac:PartyIdentification'][0])) {
                // Multiple identifications
                foreach ($party['cac:PartyIdentification'] as $identification) {
                    $partyIdentification[] = $identification['cbc:ID'] ?? '';
                }
            } else {
                // Single identification
                $partyIdentification[] = $party['cac:PartyIdentification']['cbc:ID'] ?? '';
            }
        }

        // Parse PartyName
        $partyName = $party['cac:PartyName']['cbc:Name'] ?? '';

        // Parse PostalAddress
        $postalAddress = [];
        if (isset($party['cac:PostalAddress'])) {
            $address = $party['cac:PostalAddress'];
            $postalAddress = [
                'streetName' => $address['cbc:StreetName'] ?? '',
                'buildingNumber' => $address['cbc:BuildingNumber'] ?? '',
                'citySubdivisionName' => $address['cbc:CitySubdivisionName'] ?? '',
                'cityName' => $address['cbc:CityName'] ?? '',
                'postalZone' => $address['cbc:PostalZone'] ?? '',
                'region' => $address['cbc:Region'] ?? '',
                'district' => $address['cbc:District'] ?? '',
                'country' => [
                    'identificationCode' => $address['cac:Country']['cbc:IdentificationCode'] ?? '',
                    'name' => $address['cac:Country']['cbc:Name'] ?? ''
                ]
            ];
        }

        // Parse PartyTaxScheme
        $partyTaxScheme = '';
        if (isset($party['cac:PartyTaxScheme']['cac:TaxScheme']['cbc:Name'])) {
            $partyTaxScheme = $party['cac:PartyTaxScheme']['cac:TaxScheme']['cbc:Name'];
        }

        // Parse Contact
        $contact = [];
        if (isset($party['cac:Contact'])) {
            $contactData = $party['cac:Contact'];
            $contact = [
                'telephone' => $contactData['cbc:Telephone'] ?? '',
                'telefax' => $contactData['cbc:Telefax'] ?? '',
                'electronicMail' => $contactData['cbc:ElectronicMail'] ?? ''
            ];
        }

        // Parse WebsiteURI
        $websiteURI = $party['cbc:WebsiteURI'] ?? '';

        return [
            'partyIdentification' => $partyIdentification,
            'partyName' => $partyName,
            'postalAddress' => $postalAddress,
            'partyTaxScheme' => $partyTaxScheme,
            'contact' => $contact,
            'websiteURI' => $websiteURI
        ];
    }

    public static function InvoiceLine($array)
    {
        if (!isset($array['cac:InvoiceLine'])) {
            return null;
        }

        $invoiceLine = $array['cac:InvoiceLine'];
        
        // Parse ID
        $id = $invoiceLine['cbc:ID'] ?? '';

        // Parse InvoicedQuantity
        $invoicedQuantity = [];
        if (isset($invoiceLine['cbc:InvoicedQuantity'])) {
            $invoicedQuantity = [
                'value' => $invoiceLine['cbc:InvoicedQuantity']['@value'] ?? '',
                'unitCode' => $invoiceLine['cbc:InvoicedQuantity']['@unitCode'] ?? ''
            ];
        }

        // Parse LineExtensionAmount
        $lineExtensionAmount = [];
        if (isset($invoiceLine['cbc:LineExtensionAmount'])) {
            $lineExtensionAmount = [
                'value' => $invoiceLine['cbc:LineExtensionAmount']['@value'] ?? '',
                'currencyID' => $invoiceLine['cbc:LineExtensionAmount']['@currencyID'] ?? ''
            ];
        }

        // Parse Item
        $item = [];
        if (isset($invoiceLine['cac:Item'])) {
            $itemData = $invoiceLine['cac:Item'];
            $item = [
                'name' => $itemData['cbc:Name'] ?? '',
                'description' => $itemData['cbc:Description'] ?? '',
                'sellersItemIdentification' => $itemData['cac:SellersItemIdentification']['cbc:ID'] ?? '',
                'standardItemIdentification' => $itemData['cac:StandardItemIdentification']['cbc:ID'] ?? ''
            ];
        }

        // Parse Price
        $price = [];
        if (isset($invoiceLine['cac:Price'])) {
            $priceData = $invoiceLine['cac:Price'];
            $price = [
                'priceAmount' => [
                    'value' => $priceData['cbc:PriceAmount']['@value'] ?? '',
                    'currencyID' => $priceData['cbc:PriceAmount']['@currencyID'] ?? ''
                ],
                'baseQuantity' => [
                    'value' => $priceData['cbc:BaseQuantity']['@value'] ?? '',
                    'unitCode' => $priceData['cbc:BaseQuantity']['@unitCode'] ?? ''
                ]
            ];
        }

        return [
            'id' => $id,
            'invoicedQuantity' => $invoicedQuantity,
            'lineExtensionAmount' => $lineExtensionAmount,
            'item' => $item,
            'price' => $price
        ];
    }

    public static function InvoiceLines($array)
    {
        if (!isset($array['cac:InvoiceLine'])) {
            return [];
        }

        $invoiceLines = $array['cac:InvoiceLine'];
        $parsedLines = [];

        // Handle single line or multiple lines
        if (isset($invoiceLines[0])) {
            // Multiple lines
            foreach ($invoiceLines as $line) {
                $parsedLines[] = self::InvoiceLine(['cac:InvoiceLine' => $line]);
            }
        } else {
            // Single line
            $parsedLines[] = self::InvoiceLine(['cac:InvoiceLine' => $invoiceLines]);
        }

        return $parsedLines;
    }

    public static function PaymentMeans($array)
    {
        if (!isset($array['cac:PaymentMeans'])) {
            return null;
        }

        $paymentMeans = $array['cac:PaymentMeans'];
        
        // Handle multiple payment means or single payment means
        if (isset($paymentMeans[0])) {
            // Multiple payment means - return array of parsed payment means
            $parsedPaymentMeans = [];
            foreach ($paymentMeans as $pm) {
                $parsedPaymentMeans[] = self::parseSinglePaymentMeans($pm);
            }
            return $parsedPaymentMeans;
        } else {
            // Single payment means
            return self::parseSinglePaymentMeans($paymentMeans);
        }
    }

    private static function parseSinglePaymentMeans($paymentMeans)
    {
        // Parse PaymentMeansCode
        $paymentMeansCode = '';
        if (isset($paymentMeans['cbc:PaymentMeansCode'])) {
            $paymentMeansCode = $paymentMeans['cbc:PaymentMeansCode'];
        }

        // Parse PaymentChannelCode
        $paymentChannelCode = '';
        if (isset($paymentMeans['cbc:PaymentChannelCode'])) {
            $paymentChannelCode = $paymentMeans['cbc:PaymentChannelCode'];
        }

        // Parse InstructionNote
        $instructionNote = '';
        if (isset($paymentMeans['cbc:InstructionNote'])) {
            $instructionNote = $paymentMeans['cbc:InstructionNote'];
        }

        // Parse PaymentDueDate
        $paymentDueDate = '';
        if (isset($paymentMeans['cbc:PaymentDueDate'])) {
            $paymentDueDate = $paymentMeans['cbc:PaymentDueDate'];
        }

        // Parse PayeeFinancialAccount
        $payeeFinancialAccount = [];
        if (isset($paymentMeans['cac:PayeeFinancialAccount'])) {
            $account = $paymentMeans['cac:PayeeFinancialAccount'];
            
            // Handle ID with schemeID attribute - based on XML structure
            $id = '';
            $schemeID = '';
            
            if (isset($account['cbc:ID'])) {
                $idData = $account['cbc:ID'];
                
                // Check if ID has attributes (schemeID)
                if (is_array($idData) && isset($idData['@schemeID'])) {
                    $id = $idData['@value'] ?? '';
                    $schemeID = $idData['@schemeID'];
                } else {
                    // Simple string value
                    $id = is_string($idData) ? $idData : '';
                }
            }
            
            $payeeFinancialAccount = [
                'id' => $id,
                'schemeID' => $schemeID,
                'currencyCode' => $account['cbc:CurrencyCode'] ?? '',
                'paymentNote' => $account['cbc:PaymentNote'] ?? ''
            ];
        }

        return [
            'paymentMeansCode' => $paymentMeansCode,
            'paymentChannelCode' => $paymentChannelCode,
            'instructionNote' => $instructionNote,
            'paymentDueDate' => $paymentDueDate,
            'payeeFinancialAccount' => $payeeFinancialAccount
        ];
    }

    public static function PaymentMeansList($array)
    {
        if (!isset($array['cac:PaymentMeans'])) {
            return [];
        }

        $paymentMeans = $array['cac:PaymentMeans'];
        $parsedPaymentMeans = [];

        // Handle single payment means or multiple payment means
        if (isset($paymentMeans[0])) {
            // Multiple payment means
            foreach ($paymentMeans as $pm) {
                $parsedPaymentMeans[] = self::parseSinglePaymentMeans($pm);
            }
        } else {
            // Single payment means
            $parsedPaymentMeans[] = self::parseSinglePaymentMeans($paymentMeans);
        }

        return $parsedPaymentMeans;
    }

    public static function InvoiceBasicInfo($array)
    {
        return [
            'ublVersionId' => $array['cbc:UBLVersionID'] ?? '',
            'customizationId' => $array['cbc:CustomizationID'] ?? '',
            'profileId' => $array['cbc:ProfileID'] ?? '',
            'id' => $array['cbc:ID'] ?? '',
            'copyIndicator' => $array['cbc:CopyIndicator'] ?? '',
            'uuid' => $array['cbc:UUID'] ?? '',
            'issueDate' => $array['cbc:IssueDate'] ?? '',
            'issueTime' => $array['cbc:IssueTime'] ?? '',
            'invoiceTypeCode' => $array['cbc:InvoiceTypeCode'] ?? '',
            'documentCurrencyCode' => $array['cbc:DocumentCurrencyCode'] ?? '',
            'lineCountNumeric' => $array['cbc:LineCountNumeric'] ?? '',
            'note' => $array['cbc:Note'] ?? ''
        ];
    }

    public static function OrderReference($array)
    {
        if (!isset($array['cac:OrderReference'])) {
            return [];
        }

        $orderReference = $array['cac:OrderReference'];
        
        // Handle multiple order references or single order reference
        if (isset($orderReference[0])) {
            // Multiple order references
            $parsedOrderReferences = [];
            foreach ($orderReference as $order) {
                $parsedOrderReferences[] = [
                    'id' => $order['cbc:ID'] ?? '',
                    'issueDate' => $order['cbc:IssueDate'] ?? '',
                    'documentReference' => $order['cbc:DocumentReference'] ?? ''
                ];
            }
            return $parsedOrderReferences;
        } else {
            // Single order reference
            return [
                [
                    'id' => $orderReference['cbc:ID'] ?? '',
                    'issueDate' => $orderReference['cbc:IssueDate'] ?? '',
                    'documentReference' => $orderReference['cbc:DocumentReference'] ?? ''
                ]
            ];
        }
    }

    public static function DespatchDocumentReference($array)
    {
        if (!isset($array['cac:DespatchDocumentReference'])) {
            return [];
        }

        $despatchReference = $array['cac:DespatchDocumentReference'];
        
        // Handle multiple despatch references or single despatch reference
        if (isset($despatchReference[0])) {
            // Multiple despatch references
            $parsedDespatchReferences = [];
            foreach ($despatchReference as $despatch) {
                $parsedDespatchReferences[] = [
                    'id' => $despatch['cbc:ID'] ?? '',
                    'issueDate' => $despatch['cbc:IssueDate'] ?? '',
                    'documentType' => $despatch['cbc:DocumentType'] ?? '',
                    'documentTypeCode' => $despatch['cbc:DocumentTypeCode'] ?? '',
                    'documentStatusCode' => $despatch['cbc:DocumentStatusCode'] ?? ''
                ];
            }
            return $parsedDespatchReferences;
        } else {
            // Single despatch reference
            return [
                [
                    'id' => $despatchReference['cbc:ID'] ?? '',
                    'issueDate' => $despatchReference['cbc:IssueDate'] ?? '',
                    'documentType' => $despatchReference['cbc:DocumentType'] ?? '',
                    'documentTypeCode' => $despatchReference['cbc:DocumentTypeCode'] ?? '',
                    'documentStatusCode' => $despatchReference['cbc:DocumentStatusCode'] ?? ''
                ]
            ];
        }
    }

    public static function PricingExchangeRate($array)
    {
        if (!isset($array['cac:PricingExchangeRate'])) {
            return null;
        }

        $exchangeRate = $array['cac:PricingExchangeRate'];
        
        return [
            'sourceCurrencyCode' => $exchangeRate['cbc:SourceCurrencyCode'] ?? '',
            'targetCurrencyCode' => $exchangeRate['cbc:TargetCurrencyCode'] ?? '',
            'calculationRate' => $exchangeRate['cbc:CalculationRate'] ?? '',
            'date' => $exchangeRate['cbc:Date'] ?? '',
            'mathematicalOperatorCode' => $exchangeRate['cbc:MathematicalOperatorCode'] ?? '',
            'rateTypeCode' => $exchangeRate['cbc:RateTypeCode'] ?? ''
        ];
    }

    public static function Note($array)
    {
        if (!isset($array['cbc:Note'])) {
            return null;
        }

        $note = $array['cbc:Note'];
        
        // Handle multiple notes or single note
        if (isset($note[0])) {
            // Multiple notes - return array
            return $note;
        } else {
            // Single note - return as string
            return $note;
        }
    }
}