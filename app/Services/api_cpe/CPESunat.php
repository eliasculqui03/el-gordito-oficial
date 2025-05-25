<?php
require_once('../funcionesGlobales/validaciones.php');

function cpeFacturaPrueba($ruta) {
    $doc = new DOMDocument();
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->encoding = 'ISO-8859-1';
    $xmlCPE = '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
                <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <ext:UBLExtensions>
                <ext:UBLExtension>
                <ext:ExtensionContent>
                <sac:AdditionalInformation>
                <sac:AdditionalMonetaryTotal>
                <cbc:ID>1001</cbc:ID>
                <cbc:PayableAmount currencyID="PEN">625.0</cbc:PayableAmount>
                </sac:AdditionalMonetaryTotal>
                <sac:AdditionalMonetaryTotal>
                <cbc:ID>1002</cbc:ID>
                <cbc:PayableAmount currencyID="PEN">0.0</cbc:PayableAmount>
                </sac:AdditionalMonetaryTotal>
                <sac:AdditionalMonetaryTotal>
                <cbc:ID>1003</cbc:ID>
                <cbc:PayableAmount currencyID="PEN">0.0</cbc:PayableAmount>
                </sac:AdditionalMonetaryTotal>
                <sac:AdditionalMonetaryTotal>
                <cbc:ID>1004</cbc:ID>
                <cbc:PayableAmount currencyID="PEN">0.0</cbc:PayableAmount>
                </sac:AdditionalMonetaryTotal>
                <sac:AdditionalProperty>
                <cbc:ID>1000</cbc:ID>
                <cbc:Value>SETECIENTOS TREINTA Y SIETE CON 50/100 SOLES</cbc:Value>
                </sac:AdditionalProperty>
                </sac:AdditionalInformation>
                </ext:ExtensionContent>
                </ext:UBLExtension>
                <ext:UBLExtension>
                <ext:ExtensionContent>
                </ext:ExtensionContent>
                </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
                <cbc:CustomizationID>1.0</cbc:CustomizationID>
                <cbc:ID>F001-5079</cbc:ID>
                <cbc:IssueDate>2017-05-10</cbc:IssueDate>
                <cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
                <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
                <cac:Signature>
                <cbc:ID>F001-5079</cbc:ID>
                <cac:SignatoryParty>
                <cac:PartyIdentification>
                <cbc:ID>10447915125</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                <cbc:Name><![CDATA[JOSE LUI ZAMBRANO YACHA]]></cbc:Name>
                </cac:PartyName>
                </cac:SignatoryParty>
                <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                <cbc:URI>#F001-5079</cbc:URI>
                </cac:ExternalReference>
                </cac:DigitalSignatureAttachment>
                </cac:Signature>
                <cac:AccountingSupplierParty>
                <cbc:CustomerAssignedAccountID>10447915125</cbc:CustomerAssignedAccountID>
                <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
                <cac:Party>
                <cac:PartyName>
                <cbc:Name><![CDATA[JOSE LUI ZAMBRANO YACHA]]></cbc:Name>
                </cac:PartyName>
                <cac:PostalAddress>
                <cbc:ID>070104</cbc:ID>
                <cbc:StreetName><![CDATA[PSJ HUAMPANI]]></cbc:StreetName>
                <cbc:CitySubdivisionName/>
                <cbc:CityName><![CDATA[LIMA]]></cbc:CityName>
                <cbc:CountrySubentity><![CDATA[LIMA]]></cbc:CountrySubentity>
                <cbc:District><![CDATA[CHACLACAYO]]></cbc:District>
                <cac:Country>
                <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                </cac:Country>
                </cac:PostalAddress>
                <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[JOSE LUI ZAMBRANO YACHA]]></cbc:RegistrationName>
                </cac:PartyLegalEntity>
                </cac:Party>
                </cac:AccountingSupplierParty>
                <cac:AccountingCustomerParty>
                <cbc:CustomerAssignedAccountID>10447915125</cbc:CustomerAssignedAccountID>
                <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
                <cac:Party>
                <cac:PhysicalLocation>
                <cbc:Description><![CDATA[HUAMPANI ALTO ZON 1 MZ B LT 6]]></cbc:Description>
                </cac:PhysicalLocation>
                <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[JOSE LUIS ZAMBRANO YACHA]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                <cbc:StreetName><![CDATA[LIMA]]></cbc:StreetName>
                <cac:Country>
                <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                </cac:Country>
                </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                </cac:Party>
                </cac:AccountingCustomerParty>
                <cac:TaxTotal>
                <cbc:TaxAmount currencyID="PEN">112.5</cbc:TaxAmount>
                <cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="PEN">112.5</cbc:TaxAmount>
                <cac:TaxCategory>
                <cac:TaxScheme>
                <cbc:ID>1000</cbc:ID>
                <cbc:Name>IGV</cbc:Name>
                <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
                </cac:TaxCategory>
                </cac:TaxSubtotal>
                </cac:TaxTotal>
                <cac:LegalMonetaryTotal>
                <cbc:LineExtensionAmount currencyID="PEN">625.0</cbc:LineExtensionAmount>
                <cbc:TaxExclusiveAmount currencyID="PEN">112.5</cbc:TaxExclusiveAmount>
                <cbc:PayableAmount currencyID="PEN">737.5</cbc:PayableAmount>
                </cac:LegalMonetaryTotal>
                <cac:InvoiceLine>
                <cbc:ID>1</cbc:ID>
                <cbc:InvoicedQuantity unitCode="NIU">1.00001</cbc:InvoicedQuantity>
                <cbc:LineExtensionAmount currencyID="PEN">625.0</cbc:LineExtensionAmount>
                <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="PEN">737.5</cbc:PriceAmount>
                <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
                </cac:PricingReference>
                <cac:TaxTotal>
                <cbc:TaxAmount currencyID="PEN">112.5</cbc:TaxAmount>
                <cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="PEN">112.5</cbc:TaxAmount>
                <cac:TaxCategory>
                <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
                <cac:TaxScheme>
                <cbc:ID>1000</cbc:ID>
                <cbc:Name>IGV</cbc:Name>
                <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
                </cac:TaxCategory>
                </cac:TaxSubtotal>
                </cac:TaxTotal>
                <cac:Item>
                <cbc:Description><![CDATA[PRUEBA]]></cbc:Description>
                <cac:SellersItemIdentification>
                <cbc:ID><![CDATA[0001]]></cbc:ID>
                </cac:SellersItemIdentification>
                </cac:Item>
                <cac:Price>
                <cbc:PriceAmount currencyID="PEN">625.0</cbc:PriceAmount>
                </cac:Price>
                </cac:InvoiceLine>
                </Invoice>
            ';

    $doc->loadXML($xmlCPE);
    $doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

    return '1';
}

//      private int ID;
//	private String FECHA_REGISTRO;
//	private int ID_EMPRESA;
//	private int ID_CLIENTE_CPE;
function cpeFactura(
$ruta,
 //===================
        $cabecera, $detalle
) {
$doc = new DOMDocument();
    $doc->formatOutput = TRUE;
    $doc->preserveWhiteSpace = FALSE;
	$doc->encoding = 'utf-8';
    $xmlCPE = '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
            </ext:ExtensionContent>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>B001-100</cbc:ID>
    <cbc:IssueDate>2018-04-30</cbc:IssueDate>
    <cbc:IssueTime>15:42:20</cbc:IssueTime>
    <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">03</cbc:InvoiceTypeCode>
    <cbc:Note languageLocaleID="1000">SETENTA Y UN MIL TRESCIENTOS CINCUENTICUATRO Y 99/100</cbc:Note>
    <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">PEN</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
    <cac:Signature>
        <cbc:ID>IDSignCF</cbc:ID>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>20200464529</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name>MAYORISTA CFF S.A.</cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#SignatureCF</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
<cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20100454523</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name>Tu Soporte</cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[TI SOLUCIONES S.A.C.]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
<cbc:ID schemeID="1" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">22552233</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[PUBLICO GENERAL]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="PEN">7891.2</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="PEN">43840.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="PEN">7891.2</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="PEN">72782.09</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="BX" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">2000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="PEN">43840.00</cbc:LineExtensionAmount>
        <cac:PricingReference>
<cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="PEN">38.00</cbc:PriceAmount>
<cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="PEN">7891.2</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="PEN">43840.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="PEN">7891.2</cbc:TaxAmount>
                <cac:TaxCategory>
<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                    <cbc:Percent>18.00</cbc:Percent>
<cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
<cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>Cerveza Clásica x 12 bot. 620 ml.</cbc:Description>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="PEN">21.92</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
</Invoice>';

    $doc->loadXML($xmlCPE);
    $doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

    return '1';
}

function cpeNC($ruta, $cabecera, $detalle) {
$doc = new DOMDocument();
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    //$doc->encoding = 'ISO-8859-1';
    $doc->encoding = 'utf-8';
	
$xmlCPE = '<?xml version="1.0" encoding="UTF-8"?>
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
            </ext:ExtensionContent>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>'.$cabecera["NRO_COMPROBANTE"].'</cbc:ID>
    <cbc:IssueDate>'.$cabecera["FECHA_DOCUMENTO"].'</cbc:IssueDate>
    <cbc:IssueTime>00:00:00</cbc:IssueTime>
    <cbc:Note languageLocaleID="3000">0501002017062500125</cbc:Note>
    <cbc:DocumentCurrencyCode>'.$cabecera["COD_MONEDA"].'</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID>'.$cabecera["NRO_DOCUMENTO_MODIFICA"].'</cbc:ReferenceID>
        <cbc:ResponseCode>'.$cabecera["COD_TIPO_MOTIVO"].'</cbc:ResponseCode>
        <cbc:Description><![CDATA['.$cabecera["DESCRIPCION_MOTIVO"].']]></cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>'.$cabecera["NRO_DOCUMENTO_MODIFICA"].'</cbc:ID>
            <cbc:DocumentTypeCode>' . $cabecera["TIPO_COMPROBANTE_MODIFICA"] . '</cbc:DocumentTypeCode>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    <cac:Signature>
        <cbc:ID>' . $cabecera["NRO_COMPROBANTE"] . '</cbc:ID>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[' . $cabecera["RAZON_SOCIAL_EMPRESA"] . ']]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#' . $cabecera["NRO_COMPROBANTE"] . '</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[' . $cabecera["NOMBRE_COMERCIAL_EMPRESA"] . ']]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
<cbc:RegistrationName><![CDATA[' . $cabecera["RAZON_SOCIAL_EMPRESA"] . ']]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'. $cabecera["NRO_DOCUMENTO_CLIENTE"] .'</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>' . $cabecera["RAZON_SOCIAL_CLIENTE"] . '</cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
<cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">'.$cabecera["TOTAL_IGV"].'</cbc:TaxAmount>
        <cac:TaxSubtotal>
<cbc:TaxableAmount currencyID="'.$cabecera["COD_MONEDA"] . '">'.$cabecera["TOTAL"].'</cbc:TaxableAmount>
<cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">'.$cabecera["TOTAL_IGV"].'</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="PEN">'.$cabecera["TOTAL"].'</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>';
for ($i = 0; $i < count($detalle); $i++) {
        $xmlCPE = $xmlCPE . '<cac:CreditNoteLine>
<cbc:ID>' . $detalle[$i]["txtITEM"] . '</cbc:ID>
<cbc:CreditedQuantity unitCode="' . $detalle[$i]["txtUNIDAD_MEDIDA_DET"] . '">' . $detalle[$i]["txtCANTIDAD_DET"] . '</cbc:CreditedQuantity>
<cbc:LineExtensionAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtIMPORTE_DET"] . '</cbc:LineExtensionAmount>
<cac:PricingReference>
<cac:AlternativeConditionPrice>
<cbc:PriceAmount currencyID="'.$cabecera["COD_MONEDA"].'">'.$detalle[$i]["txtPRECIO_DET"] . '</cbc:PriceAmount>
<cbc:PriceTypeCode>'.$detalle[$i]["txtPRECIO_TIPO_CODIGO"].'</cbc:PriceTypeCode>
</cac:AlternativeConditionPrice>
</cac:PricingReference>
<cac:TaxTotal>
<cbc:TaxAmount currencyID="'.$cabecera["COD_MONEDA"].'">'.$detalle[$i]["txtIGV"].'</cbc:TaxAmount>
            <cac:TaxSubtotal>
<cbc:TaxableAmount currencyID="'.$cabecera["COD_MONEDA"].'">'.$detalle[$i]["txtIGV"].'</cbc:TaxableAmount>
<cbc:TaxAmount currencyID="'.$cabecera["COD_MONEDA"].'">'.$detalle[$i]["txtIGV"].'</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>18.00</cbc:Percent>
<cbc:TaxExemptionReasonCode>'.$detalle[$i]["txtCOD_TIPO_OPERACION"].'</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>		
<cac:Item>
<cbc:Description><![CDATA[' . ValidarCaracteresInv((isset($detalle[$i]["txtDESCRIPCION_DET"]))?$detalle[$i]["txtDESCRIPCION_DET"]:"") . ']]></cbc:Description>
            <cac:SellersItemIdentification>
<cbc:ID><![CDATA[' . ValidarCaracteresInv((isset($detalle[$i]["txtCODIGO_DET"]))?$detalle[$i]["txtCODIGO_DET"]:"") . ']]></cbc:ID>
            </cac:SellersItemIdentification>
            <cac:CommodityClassification>
<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification"><![CDATA[' . ValidarCaracteresInv((isset($detalle[$i]["txtCODIGO_DET"]))?$detalle[$i]["txtCODIGO_DET"]:"") . ']]></cbc:ItemClassificationCode>
            </cac:CommodityClassification>
        </cac:Item>
        <cac:Price>
<cbc:PriceAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtPRECIO_DET"] . '</cbc:PriceAmount>
        </cac:Price>
</cac:CreditNoteLine>';
    }

    $xmlCPE = $xmlCPE . '</CreditNote>';

    $doc->loadXML($xmlCPE);
    $doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

    return 'XML CREADO';
	
}

function cpeND($ruta,
 //===================
        $cabecera, $detalle) {
    $doc = new DOMDocument();
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->encoding = 'ISO-8859-1';
    $xmlCPE = '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?><DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<ext:UBLExtensions>
<ext:UBLExtension>
<ext:ExtensionContent>
<sac:AdditionalInformation>
<sac:AdditionalMonetaryTotal>
<cbc:ID>1001</cbc:ID>
<cbc:PayableAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $cabecera["TOTAL_GRAVADAS"] . '</cbc:PayableAmount>
</sac:AdditionalMonetaryTotal>
</sac:AdditionalInformation>
</ext:ExtensionContent>
</ext:UBLExtension>
<ext:UBLExtension>
<ext:ExtensionContent>
</ext:ExtensionContent>
</ext:UBLExtension>
</ext:UBLExtensions>
<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
<cbc:CustomizationID>1.0</cbc:CustomizationID>
<cbc:ID>' . $cabecera["NRO_COMPROBANTE"] . '</cbc:ID>
<cbc:IssueDate>' . $cabecera["FECHA_DOCUMENTO"] . '</cbc:IssueDate>
<cbc:DocumentCurrencyCode>' . $cabecera["COD_MONEDA"] . '</cbc:DocumentCurrencyCode>
<cac:DiscrepancyResponse>
    <cbc:ReferenceID>' . $cabecera["NRO_DOCUMENTO_MODIFICA"] . '</cbc:ReferenceID>
    <cbc:ResponseCode>' . $cabecera["COD_TIPO_MOTIVO"] . '</cbc:ResponseCode>
    <cbc:Description><![CDATA[' . $cabecera["DESCRIPCION_MOTIVO"] . ']]></cbc:Description>
</cac:DiscrepancyResponse>
<cac:BillingReference>
    <cac:InvoiceDocumentReference>
        <cbc:ID>' . $cabecera["NRO_DOCUMENTO_MODIFICA"] . '</cbc:ID>
        <cbc:DocumentTypeCode>' . $cabecera["TIPO_COMPROBANTE_MODIFICA"] . '</cbc:DocumentTypeCode>
    </cac:InvoiceDocumentReference>
</cac:BillingReference>
<cac:Signature>
    <cbc:ID>' . $cabecera["NRO_COMPROBANTE"] . '</cbc:ID>
    <cac:SignatoryParty>
        <cac:PartyIdentification>
            <cbc:ID>' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name><![CDATA[' . $cabecera["RAZON_SOCIAL_EMPRESA"] . ']]></cbc:Name>
        </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
        <cac:ExternalReference>
            <cbc:URI>#' . $cabecera["NRO_COMPROBANTE"] . '</cbc:URI>
        </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
</cac:Signature>
<cac:AccountingSupplierParty>
<cbc:CustomerAssignedAccountID>' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>' . $cabecera["TIPO_DOCUMENTO_EMPRESA"] . '</cbc:AdditionalAccountID>
    <cac:Party>
        <cac:PartyName>
            <cbc:Name><![CDATA[' . $cabecera["NOMBRE_COMERCIAL_EMPRESA"] . ']]></cbc:Name>
        </cac:PartyName>
        <cac:PostalAddress>
            <cbc:ID>' . $cabecera["CODIGO_UBIGEO_EMPRESA"] . '</cbc:ID>
            <cbc:StreetName><![CDATA[' . $cabecera["DIRECCION_EMPRESA"] . ']]></cbc:StreetName>
            <cbc:CitySubdivisionName/>
            <cbc:CityName><![CDATA[' . $cabecera["DEPARTAMENTO_EMPRESA"] . ']]></cbc:CityName>
            <cbc:CountrySubentity><![CDATA[' . $cabecera["PROVINCIA_EMPRESA"] . ']]></cbc:CountrySubentity>
            <cbc:District><![CDATA[' . $cabecera["DISTRITO_EMPRESA"] . ']]></cbc:District>
            <cac:Country>
                <cbc:IdentificationCode>' . $cabecera["CODIGO_PAIS_EMPRESA"] . '</cbc:IdentificationCode>
            </cac:Country>
        </cac:PostalAddress>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[' . $cabecera["RAZON_SOCIAL_EMPRESA"] . ']]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:Party>
</cac:AccountingSupplierParty>
<cac:AccountingCustomerParty>
    <cbc:CustomerAssignedAccountID>' . $cabecera["NRO_DOCUMENTO_CLIENTE"] . '</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>' . $cabecera["TIPO_DOCUMENTO_CLIENTE"] . '</cbc:AdditionalAccountID>
    <cac:Party>
        <cac:PhysicalLocation>
            <cbc:Description><![CDATA[' . $cabecera["DIRECCION_CLIENTE"] . ']]></cbc:Description>
        </cac:PhysicalLocation>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[' . $cabecera["RAZON_SOCIAL_CLIENTE"] . ']]></cbc:RegistrationName>
            <cac:RegistrationAddress>
                <cbc:StreetName><![CDATA[' . $cabecera["CIUDAD_CLIENTE"] . ']]></cbc:StreetName>
                <cac:Country>
                    <cbc:IdentificationCode>' . $cabecera["COD_PAIS_CLIENTE"] . '</cbc:IdentificationCode>
                </cac:Country>
            </cac:RegistrationAddress>
        </cac:PartyLegalEntity>
    </cac:Party>
</cac:AccountingCustomerParty>
<cac:TaxTotal>
    <cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $cabecera["TOTAL_IGV"] . '</cbc:TaxAmount>
    <cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $cabecera["TOTAL_IGV"] . '</cbc:TaxAmount>
        <cac:TaxCategory>
            <cac:TaxScheme>
                <cbc:ID>1000</cbc:ID>
                <cbc:Name>IGV</cbc:Name>
                <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
            </cac:TaxScheme>
        </cac:TaxCategory>
    </cac:TaxSubtotal>
</cac:TaxTotal>
<cac:RequestedMonetaryTotal>
   <cbc:PayableAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $cabecera["TOTAL"] . '</cbc:PayableAmount>
</cac:RequestedMonetaryTotal>';

    for ($i = 0; $i < count($detalle); $i++) {
        $xmlCPE = $xmlCPE . '<cac:DebitNoteLine>
<cbc:ID>' . $detalle[$i]["txtITEM"] . '</cbc:ID>
<cbc:DebitedQuantity unitCode="' . $detalle[$i]["txtUNIDAD_MEDIDA_DET"] . '">' . $detalle[$i]["txtCANTIDAD_DET"] . '</cbc:DebitedQuantity>
<cbc:LineExtensionAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtIMPORTE_DET"] . '</cbc:LineExtensionAmount>
<cac:PricingReference>
<cac:AlternativeConditionPrice>
<cbc:PriceAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtPRECIO_DET"] . '</cbc:PriceAmount>
<cbc:PriceTypeCode>' . $detalle[$i]["txtPRECIO_TIPO_CODIGO"] . '</cbc:PriceTypeCode>
</cac:AlternativeConditionPrice>
</cac:PricingReference>
<cac:TaxTotal>
<cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtIGV"] . '</cbc:TaxAmount>
<cac:TaxSubtotal>
<cbc:TaxAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtIGV"] . '</cbc:TaxAmount>
<cac:TaxCategory>
<cbc:TaxExemptionReasonCode>' . $detalle[$i]["txtCOD_TIPO_OPERACION"] . '</cbc:TaxExemptionReasonCode>
<cac:TaxScheme>
<cbc:ID>1000</cbc:ID>
<cbc:Name>IGV</cbc:Name>
<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
</cac:TaxScheme>
</cac:TaxCategory>
</cac:TaxSubtotal>
</cac:TaxTotal>
<cac:Item>
<cbc:Description><![CDATA[' . ValidarCaracteresInv((isset($detalle[$i]["txtDESCRIPCION_DET"]))?$detalle[$i]["txtDESCRIPCION_DET"]:"") . ']]></cbc:Description>
<cac:SellersItemIdentification>
<cbc:ID><![CDATA[' . ValidarCaracteresInv((isset($detalle[$i]["txtCODIGO_DET"]))?$detalle[$i]["txtCODIGO_DET"]:"") . ']]></cbc:ID>
</cac:SellersItemIdentification>
</cac:Item>
<cac:Price>
<cbc:PriceAmount currencyID="' . $cabecera["COD_MONEDA"] . '">' . $detalle[$i]["txtPRECIO_DET"] . '</cbc:PriceAmount>
</cac:Price>
</cac:DebitNoteLine>';
    }
    $xmlCPE = $xmlCPE . '</DebitNote>';

    $doc->loadXML($xmlCPE);
    $doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

    return 'XML CREADO';
}

function cpeBajaSunat($ruta,$cabecera, $detalle) {
    $doc = new DOMDocument();
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->encoding = 'ISO-8859-1';
    $xmlCPE = '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?><VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<ext:UBLExtensions>
<ext:UBLExtension>
<ext:ExtensionContent>
</ext:ExtensionContent>
</ext:UBLExtension>
</ext:UBLExtensions>
<cbc:UBLVersionID>2.1</cbc:UBLVersionID>
<cbc:CustomizationID>1.0</cbc:CustomizationID>
<cbc:ID>'.$cabecera["CODIGO"].'-'.$cabecera["SERIE"].'-'.$cabecera["SECUENCIA"].'</cbc:ID>
<cbc:ReferenceDate>'.$cabecera["FECHA_REFERENCIA"].'</cbc:ReferenceDate>
<cbc:IssueDate>'.$cabecera["FECHA_BAJA"].'</cbc:IssueDate>
<cac:Signature>
<cbc:ID>IDSignKG</cbc:ID>
<cac:SignatoryParty>
<cac:PartyIdentification>
<cbc:ID>'.$cabecera["NRO_DOCUMENTO_EMPRESA"].'</cbc:ID>
</cac:PartyIdentification>
<cac:PartyName>
<cbc:Name>'.ValidarCaracteresInv($cabecera["RAZON_SOCIAL"]).'</cbc:Name>
</cac:PartyName>
</cac:SignatoryParty>
<cac:DigitalSignatureAttachment>
<cac:ExternalReference>
<cbc:URI>#'.$cabecera["SERIE"].'-'.$cabecera["SECUENCIA"].'</cbc:URI>
</cac:ExternalReference>
</cac:DigitalSignatureAttachment>
</cac:Signature>
<cac:AccountingSupplierParty>
<cbc:CustomerAssignedAccountID>'.$cabecera["NRO_DOCUMENTO_EMPRESA"].'</cbc:CustomerAssignedAccountID>
<cbc:AdditionalAccountID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cabecera["TIPO_DOCUMENTO"].'</cbc:AdditionalAccountID>
<cac:Party>
<cac:PartyLegalEntity>
<cbc:RegistrationName><![CDATA['.ValidarCaracteresInv($cabecera["RAZON_SOCIAL"]).']]></cbc:RegistrationName>
</cac:PartyLegalEntity>
</cac:Party>
</cac:AccountingSupplierParty>';

for ($i = 0; $i < count($detalle); $i++) {
$xmlCPE = $xmlCPE . '<sac:VoidedDocumentsLine>
<cbc:LineID>'.$detalle[$i]["ITEM"].'</cbc:LineID>
<cbc:DocumentTypeCode  listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento"
listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">'.$detalle[$i]["TIPO_COMPROBANTE"].'</cbc:DocumentTypeCode>
<sac:DocumentSerialID>'.$detalle[$i]["SERIE"].'</sac:DocumentSerialID>
<sac:DocumentNumberID>'.$detalle[$i]["NUMERO"].'</sac:DocumentNumberID>
<sac:VoidReasonDescription><![CDATA['.ValidarCaracteresInv($detalle[$i]["DESCRIPCION"]).']]></sac:VoidReasonDescription>
</sac:VoidedDocumentsLine>';
}
$xmlCPE = $xmlCPE . '</VoidedDocuments>';

$doc->loadXML($xmlCPE);
$doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

return 'XML BAJA CREADO';
}



function cpeResumenBoleta($ruta,$cabecera, $detalle){
    $doc = new DOMDocument();
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $doc->encoding = 'ISO-8859-1';
    $xmlCPE = '<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
    <SummaryDocuments 
	xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" 
	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" 
	xmlns:ds="http://www.w3.org/2000/09/xmldsig#" 
	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" 
	xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
	xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" 
	xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
	<ext:UBLExtensions>
		<ext:UBLExtension>
                        <ext:ExtensionContent>
			</ext:ExtensionContent>
		</ext:UBLExtension>
	</ext:UBLExtensions>
	<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
	<cbc:CustomizationID>1.1</cbc:CustomizationID>
	<cbc:ID>' . $cabecera["CODIGO"] . '-' . $cabecera["SERIE"] . '-' . $cabecera["SECUENCIA"] . '</cbc:ID>
	<cbc:ReferenceDate>' . $cabecera["FECHA_REFERENCIA"] . '</cbc:ReferenceDate>
	<cbc:IssueDate>' . $cabecera["FECHA_DOCUMENTO"] . '</cbc:IssueDate>
	<cac:Signature>
		<cbc:ID>' . $cabecera["CODIGO"] . '-' . $cabecera["SERIE"] . '-' . $cabecera["SECUENCIA"] . '</cbc:ID>
		<cac:SignatoryParty>
			<cac:PartyIdentification>
				<cbc:ID>' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:ID>
			</cac:PartyIdentification>
			<cac:PartyName>
				<cbc:Name>' . $cabecera["RAZON_SOCIAL"] . '</cbc:Name>
			</cac:PartyName>
		</cac:SignatoryParty>
		<cac:DigitalSignatureAttachment>
			<cac:ExternalReference>
				<cbc:URI>' . $cabecera["CODIGO"] . '-' . $cabecera["SERIE"] . '-' . $cabecera["SECUENCIA"] . '</cbc:URI>
			</cac:ExternalReference>
		</cac:DigitalSignatureAttachment>
	</cac:Signature>
	<cac:AccountingSupplierParty>
		<cbc:CustomerAssignedAccountID>' . $cabecera["NRO_DOCUMENTO_EMPRESA"] . '</cbc:CustomerAssignedAccountID>
		<cbc:AdditionalAccountID>' . $cabecera["TIPO_DOCUMENTO"] . '</cbc:AdditionalAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName>' . $cabecera["RAZON_SOCIAL"] . '</cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:AccountingSupplierParty>';
    /*
private int ITEM;
	private String TIPO_COMPROBANTE;
	private String NRO_COMPROBANTE;
	private String TIPO_DOCUMENTO;
	private String NRO_DOCUMENTO;
	private String TIPO_COMPROBANTE_REF;
	private String NRO_COMPROBANTE_REF;
	private String STATU;
	private String COD_MONEDA;
	private double TOTAL;
	private double GRAVADA;
	private double ISC;
	private double IGV;
	private double OTROS;
	private int CARGO_X_ASIGNACION;
	private double MONTO_CARGO_X_ASIG;
	private String COD_TIPO_IMPORTE1;
	private double EXONERADO;
	private String COD_TIPO_IMPORTE2;
	private double INAFECTO;
	private String COD_TIPO_IMPORTE3;
	private double EXPORTACION;
	private String COD_TIPO_IMPORTE4;
	private double GRATUITAS;
	private String COD_TIPO_IMPORTE5;
	private String ESTADOS;
    */
	for ($i = 0; $i < count($detalle); $i++) {
        $xmlCPE = $xmlCPE . '<sac:SummaryDocumentsLine>
		<cbc:LineID>' . $detalle[$i]["ITEM"] . '</cbc:LineID>
		<cbc:DocumentTypeCode>' . $detalle[$i]["TIPO_COMPROBANTE"] . '</cbc:DocumentTypeCode>
		<cbc:ID>' . $detalle[$i]["NRO_COMPROBANTE"] . '</cbc:ID>
		<cac:AccountingCustomerParty>
			<cbc:CustomerAssignedAccountID>' . $detalle[$i]["NRO_DOCUMENTO"] . '</cbc:CustomerAssignedAccountID>
			<cbc:AdditionalAccountID>' . $detalle[$i]["TIPO_DOCUMENTO"] . '</cbc:AdditionalAccountID>
		</cac:AccountingCustomerParty>';
                if ($detalle[$i]["TIPO_COMPROBANTE"]=="07"||$detalle[$i]["TIPO_COMPROBANTE"]=="08"){
		 $xmlCPE = $xmlCPE . '<cac:BillingReference>
			<cac:InvoiceDocumentReference>
				<cbc:ID>' . $detalle[$i]["NRO_COMPROBANTE_REF"] . '</cbc:ID>
				<cbc:DocumentTypeCode>' . $detalle[$i]["TIPO_COMPROBANTE_REF"] . '</cbc:DocumentTypeCode>
			</cac:InvoiceDocumentReference>
		</cac:BillingReference>';
                }
		$xmlCPE = $xmlCPE . '<cac:Status>
			<cbc:ConditionCode>' . $detalle[$i]["STATU"] . '</cbc:ConditionCode>
		</cac:Status>                
		<sac:TotalAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["TOTAL"] . '</sac:TotalAmount>
		
                <sac:BillingPayment>
			<cbc:PaidAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["GRAVADA"] . '</cbc:PaidAmount>
			<cbc:InstructionID>01</cbc:InstructionID>
		</sac:BillingPayment>';
                
                if ($detalle[$i]["EXONERADO"] > 0) {
                $xmlCPE = $xmlCPE . '<sac:BillingPayment>
			<cbc:PaidAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["EXONERADO"] . '</cbc:PaidAmount>
			<cbc:InstructionID>02</cbc:InstructionID>
		</sac:BillingPayment>';
                }
                
                if ($detalle[$i]["INAFECTO"] > 0) {
                $xmlCPE = $xmlCPE . '<sac:BillingPayment>
			<cbc:PaidAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["INAFECTO"] . '</cbc:PaidAmount>
			<cbc:InstructionID>03</cbc:InstructionID>
		</sac:BillingPayment>';
                }
                
                if ($detalle[$i]["EXPORTACION"] > 0) {
                $xmlCPE = $xmlCPE . '<sac:BillingPayment>
			<cbc:PaidAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["EXPORTACION"] . '</cbc:PaidAmount>
			<cbc:InstructionID>04</cbc:InstructionID>
		</sac:BillingPayment>';
                }
                
                if ($detalle[$i]["GRATUITAS"] > 0) {
                $xmlCPE = $xmlCPE . '<sac:BillingPayment>
			<cbc:PaidAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["GRATUITAS"] . '</cbc:PaidAmount>
			<cbc:InstructionID>05</cbc:InstructionID>
		</sac:BillingPayment>';
                }
                
                if ($detalle[$i]["MONTO_CARGO_X_ASIG"] > 0) {
                    $xmlCPE = $xmlCPE . '<cac:AllowanceCharge>';
                    if ($detalle[$i]["CARGO_X_ASIGNACION"] == 1) {
                        $xmlCPE = $xmlCPE . '<cbc:ChargeIndicator>true</cbc:ChargeIndicator>';
                    }else{
                        $xmlCPE = $xmlCPE . '<cbc:ChargeIndicator>false</cbc:ChargeIndicator>';
                    }
                    $xmlCPE = $xmlCPE . '<cbc:Amount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["MONTO_CARGO_X_ASIG"] . '</cbc:Amount>
                    </cac:AllowanceCharge>';
                }
                if($detalle[$i]["ISC"]>0){
		$xmlCPE = $xmlCPE . '<cac:TaxTotal>
			<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["ISC"] . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["ISC"] . '</cbc:TaxAmount>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:ID>2000</cbc:ID>
						<cbc:Name>ISC</cbc:Name>
						<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>';
                }
                $xmlCPE = $xmlCPE . '<cac:TaxTotal>
			<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["IGV"] . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["IGV"] . '</cbc:TaxAmount>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:ID>1000</cbc:ID>
						<cbc:Name>IGV</cbc:Name>
						<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>';
                
                if($detalle[$i]["OTROS"]>0){
                $xmlCPE = $xmlCPE . '<cac:TaxTotal>
			<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["OTROS"] . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxAmount currencyID="' . $detalle[$i]["COD_MONEDA"] . '">' . $detalle[$i]["OTROS"] . '</cbc:TaxAmount>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:ID>9999</cbc:ID>
						<cbc:Name>OTROS</cbc:Name>
						<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>';
                }
	$xmlCPE = $xmlCPE . '</sac:SummaryDocumentsLine>';
    }
    $xmlCPE = $xmlCPE . '</SummaryDocuments>';

    $doc->loadXML($xmlCPE);
    $doc->save(dirname(__FILE__) . '/' . $ruta . '.XML');

    return 'XML RESUMEN BOLETA CREADO';
}

?>