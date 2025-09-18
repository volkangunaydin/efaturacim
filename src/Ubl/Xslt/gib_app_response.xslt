<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ar="urn:oasis:names:specification:ubl:schema:xsd:ApplicationResponse-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">

    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/ar:ApplicationResponse">
        <html>
            <head>
                <title>Fatura Uygulama Yanıtı</title>
                <style>
                    body { font-family: sans-serif; line-height: 1.6; }
                    h2 { margin-top: 20px; }
                    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Fatura Uygulama Yanıtı Detayları</h1>

                <h2>Genel Bilgiler</h2>
                <table>
                    <tr>
                        <th>UBL Versiyon ID</th>
                        <td><xsl:value-of select="cbc:UBLVersionID"/></td>
                    </tr>
                    <tr>
                        <th>Özelleştirme ID</th>
                        <td><xsl:value-of select="cbc:CustomizationID"/></td>
                    </tr>
                    <tr>
                        <th>Profil ID</th>
                        <td><xsl:value-of select="cbc:ProfileID"/></td>
                    </tr>
                    <tr>
                        <th>Yanıt ID</th>
                        <td><xsl:value-of select="cbc:ID"/></td>
                    </tr>
                    <tr>
                        <th>UUID</th>
                        <td><xsl:value-of select="cbc:UUID"/></td>
                    </tr>
                    <tr>
                        <th>Yanıt Tarihi</th>
                        <td><xsl:value-of select="cbc:IssueDate"/></td>
                    </tr>
                    <tr>
                        <th>Yanıt Saati</th>
                        <td><xsl:value-of select="cbc:IssueTime"/></td>
                    </tr>
                </table>

                <h2>Gönderen Taraf Bilgileri</h2>
                <xsl:for-each select="cac:SenderParty">
                    <table>
                        <tr>
                            <th>Vergi/TC Kimlik No</th>
                            <td><xsl:value-of select="cac:PartyIdentification/cbc:ID"/></td>
                        </tr>
                        <tr>
                            <th>Adı/Unvanı</th>
                            <td><xsl:value-of select="cac:PartyName/cbc:Name"/></td>
                        </tr>
                        <tr>
                            <th>Adres</th>
                            <td>
                                <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/>,
                                <xsl:value-of select="cac:PostalAddress/cbc:BuildingNumber"/>
                                <xsl:if test="cac:PostalAddress/cbc:Region">
                                    , <xsl:value-of select="cac:PostalAddress/cbc:Region"/>
                                </xsl:if>
                                <xsl:if test="cac:PostalAddress/cbc:District">
                                    , <xsl:value-of select="cac:PostalAddress/cbc:District"/>
                                </xsl:if>
                                , <xsl:value-of select="cac:PostalAddress/cbc:CitySubdivisionName"/>
                                , <xsl:value-of select="cac:PostalAddress/cbc:CityName"/>
                                - <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/>
                                / <xsl:value-of select="cac:PostalAddress/cac:Country/cbc:Name"/>
                            </td>
                        </tr>
                         <tr>
                            <th>Vergi Dairesi</th>
                            <td><xsl:value-of select="cac:PartyTaxScheme/cac:TaxScheme/cbc:Name"/></td>
                        </tr>
                        <tr>
                            <th>E-posta</th>
                            <td><xsl:value-of select="cac:Contact/cbc:ElectronicMail"/></td>
                        </tr>
                    </table>
                </xsl:for-each>

                <h2>Alıcı Taraf Bilgileri</h2>
                 <xsl:for-each select="cac:ReceiverParty">
                    <table>
                        <tr>
                            <th>Web Sitesi</th>
                            <td><xsl:value-of select="cbc:WebsiteURI"/></td>
                        </tr>
                         <tr>
                            <th>Vergi/TC Kimlik No</th>
                            <td><xsl:value-of select="cac:PartyIdentification[starts-with(cbc:ID/@schemeID, 'VKN_TCKN') or @schemeID='VKN']/cbc:ID"/></td>
                        </tr>
                         <tr>
                            <th>Ticaret Sicil No</th>
                             <td><xsl:value-of select="cac:PartyIdentification[@schemeID='TICARETSICILNO']/cbc:ID"/></td>
                        </tr>
                         <tr>
                            <th>Mersis No</th>
                             <td><xsl:value-of select="cac:PartyIdentification[@schemeID='MERSISNO']/cbc:ID"/></td>
                        </tr>
                        <tr>
                            <th>Adı/Unvanı</th>
                            <td><xsl:value-of select="cac:PartyName/cbc:Name"/></td>
                        </tr>
                        <tr>
                            <th>Adres</th>
                             <td>
                                <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/>,
                                <xsl:value-of select="cac:PostalAddress/cbc:BuildingNumber"/>
                                 <xsl:if test="cac:PostalAddress/cbc:Region">
                                    , <xsl:value-of select="cac:PostalAddress/cbc:Region"/>
                                </xsl:if>
                                <xsl:if test="cac:PostalAddress/cbc:District">
                                    , <xsl:value-of select="cac:PostalAddress/cbc:District"/>
                                </xsl:if>
                                , <xsl:value-of select="cac:PostalAddress/cbc:CitySubdivisionName"/>
                                , <xsl:value-of select="cac:PostalAddress/cbc:CityName"/>
                                - <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/>
                                / <xsl:value-of select="cac:PostalAddress/cac:Country/cbc:Name"/>
                            </td>
                        </tr>
                         <tr>
                            <th>Vergi Dairesi</th>
                            <td><xsl:value-of select="cac:PartyTaxScheme/cac:TaxScheme/cbc:Name"/></td>
                        </tr>
                        <tr>
                            <th>Telefon</th>
                            <td><xsl:value-of select="cac:Contact/cbc:Telephone"/></td>
                        </tr>
                        <tr>
                            <th>E-posta</th>
                            <td><xsl:value-of select="cac:Contact/cbc:ElectronicMail"/></td>
                        </tr>
                    </table>
                </xsl:for-each>

                 <h2>Belge Yanıtı</h2>
                 <xsl:for-each select="cac:DocumentResponse">
                    <table>
                        <tr>
                            <th>Yanıt Kodu</th>
                            <td><xsl:value-of select="cac:Response/cbc:ResponseCode"/></td>
                        </tr>
                         <tr>
                            <th>Açıklama</th>
                            <td><xsl:value-of select="cac:Response/cbc:Description"/></td>
                        </tr>
                        <tr>
                            <th>Referans Belge ID</th>
                            <td><xsl:value-of select="cac:DocumentReference/cbc:ID"/></td>
                        </tr>
                         <tr>
                            <th>Referans Belge Tarihi</th>
                            <td><xsl:value-of select="cac:DocumentReference/cbc:IssueDate"/></td>
                        </tr>
                         <tr>
                            <th>Referans Belge Tipi Kodu</th>
                            <td><xsl:value-of select="cac:DocumentReference/cbc:DocumentTypeCode"/></td>
                        </tr>
                         <tr>
                            <th>Referans Belge Tipi</th>
                            <td><xsl:value-of select="cac:DocumentReference/cbc:DocumentType"/></td>
                        </tr>
                    </table>

                     <h3>Satır Yanıtları</h3>
                     <xsl:if test="cac:LineResponse">
                         <table>
                            <tr>
                                <th>Satır ID</th>
                                <th>Yanıt Kodu</th>
                                <th>Açıklama</th>
                            </tr>
                             <xsl:for-each select="cac:LineResponse">
                                 <tr>
                                     <td><xsl:value-of select="cac:LineReference/cbc:LineID"/></td>
                                     <td><xsl:value-of select="cac:Response/cbc:ResponseCode"/></td>
                                     <td><xsl:value-of select="cac:Response/cbc:Description"/></td>
                                 </tr>
                             </xsl:for-each>
                         </table>
                     </xsl:if>
                 </xsl:for-each>

            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>