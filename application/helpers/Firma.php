<?php
/**
 * Created by PhpStorm.
 * User: svycar
 * Date: 26/3/18
 * Time: 16:37
 * Modified to support any valid .p12 certificate with flexible signature algorithm and enhanced error handling
 */

namespace Shara\Comprobantes;

use DOMDocument;
use Exception;

class Firma
{
    private $config;
    private $privateKey = null;
    private $publicKey = null;
    private $signTime = null;
    private $certData = null;
    private $claveAcceso = null;
    private $tipoComprobante = null;
    private $certificate = null;

    private $signatureID;
    private $signedInfoID;
    private $signedPropertiesID;
    private $signatureValueID;
    private $certificateID;
    private $referenceID;
    private $signatureSignedPropertiesID;
    private $signatureObjectID;

    public function __construct($config = array(), $claveAcceso)
    {
        $this->claveAcceso = $claveAcceso;
        $this->tipoComprobante = substr($this->claveAcceso, 8, 2);

        $this->config = array_merge(array(
            'file' => null,
            'pass' => null,
            'wordwrap' => 64,
            'algorithm' => 'sha256', // Default to SHA256
        ), $config);
    }

    public function verificarCertPKey()
    {
        try {
            $pathFirma = $this->config['file'];

            if (!file_exists($pathFirma)) {
                return array('error' => true, 'mensaje' => "El archivo .p12 no existe en la ruta especificada: $pathFirma");
            }

            $p12Content = file_get_contents($pathFirma);
            if ($p12Content === false) {
                return array('error' => true, 'mensaje' => "No se pudo leer el archivo .p12: $pathFirma");
            }

            if (openssl_pkcs12_read($p12Content, $certs, $this->config['pass'])) {
                if (!isset($certs['cert'])) {
                    return array('error' => true, 'mensaje' => "El archivo .p12 no contiene un certificado válido");
                }
                $x509cert = openssl_x509_read($certs['cert']);
                if ($x509cert === false) {
                    $error = openssl_error_string();
                    return array('error' => true, 'mensaje' => "Error al leer el certificado: $error");
                }
                $this->certificate = $x509cert;
                $this->certData = openssl_x509_parse($x509cert);

                // Handle multiple certificates if present
                if (!empty($certs['extracerts'])) {
                    foreach ($certs['extracerts'] as $item) {
                        $x509cert = openssl_x509_read($item);
                        if ($x509cert === false) continue;
                        $certData = openssl_x509_parse($x509cert);
                        if ($certData['validTo_time_t'] > time()) {
                            $this->certificate = $x509cert;
                            $this->certData = $certData;
                            break;
                        }
                    }
                }
            } else {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "No se puede leer el archivo .p12 o la contraseña es incorrecta: $error");
            }

            $this->publicKey = openssl_get_publickey($this->certificate);
            if ($this->publicKey === false) {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "No se pudo acceder a la clave pública del certificado: $error");
            }

            if ($this->getPublicPem() === "") {
                return array('error' => true, 'mensaje' => "No existe ningún certificado para firmar");
            }

            $resp = $this->getPrivateKey();
            if ($resp["error"] === true) {
                return array('error' => true, 'mensaje' => $resp["mensaje"]);
            }

            $fecha_actual = strtotime(date("Y-m-d H:i:s", time()));
            $fecha_entrada = strtotime(date("Y-m-d H:i:s", $this->certData['validTo_time_t']));
            if ($fecha_actual > $fecha_entrada) {
                return array('error' => true, 'mensaje' => "El certificado está expirado. Actualice su certificado digital");
            }

            // Optional RUC validation
            $rucCert = $this->getRucCert($aux);
            if ($rucCert === false) {
                return array('error' => true, 'mensaje' => $aux);
            }

            $rucCmp = substr($this->claveAcceso, 10, 13);
            if ($rucCert !== false && $aux !== '' && $rucCmp != $aux) {
                return array('error' => true, 'mensaje' => "RUC del certificado ($aux) no coincide con el RUC del emisor ($rucCmp)");
            }

            $this->generarId();
        } catch (Exception $ex) {
            return array('error' => true, 'mensaje' => "Excepción en verificarCertPKey: " . $ex->getMessage());
        }
        return array('error' => false, 'mensaje' => "");
    }

    public function getPublicPem()
    {
        $publicPEM = "";
        if (!openssl_x509_export($this->certificate, $publicPEM)) {
            $error = openssl_error_string();
            return "";
        }
        $publicPEM = str_replace("-----BEGIN CERTIFICATE-----", "", $publicPEM);
        $publicPEM = str_replace("-----END CERTIFICATE-----", "", $publicPEM);
        $publicPEM = str_replace("\n", "", $publicPEM);
        $publicPEM = wordwrap($publicPEM, $this->config['wordwrap'], "\n", true);
        return $publicPEM;
    }

    private function getPrivateKey()
    {
        try {
            $pfx = $this->config['file'];
            $password = $this->config['pass'];

            $p12Content = file_get_contents($pfx);
            if ($p12Content === false) {
                return array('error' => true, 'mensaje' => "No se pudo leer el archivo .p12: $pfx");
            }

            if (!openssl_pkcs12_read($p12Content, $certs, $password)) {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "No se pudo leer el archivo .p12 o la contraseña es incorrecta: $error");
            }

            if (!isset($certs['pkey'])) {
                return array('error' => true, 'mensaje' => "No se encontró la clave privada en el archivo .p12");
            }

            $this->privateKey = openssl_pkey_get_private($certs['pkey'], $password);
            if ($this->privateKey === false) {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "No se pudo acceder a la clave privada del certificado: $error");
            }

            if (!openssl_x509_check_private_key($this->certificate, $this->privateKey)) {
                return array('error' => true, 'mensaje' => "La clave privada no corresponde al certificado");
            }
        } catch (Exception $ex) {
            return array('error' => true, 'mensaje' => "Excepción en getPrivateKey: " . $ex->getMessage());
        }

        return array('error' => false, 'mensaje' => '');
    }

    public function getRucCert(&$output)
    {
        // Try to extract RUC from subjectAltName or extensions
        if (isset($this->certData['extensions'])) {
            foreach ($this->certData['extensions'] as $clave => $value) {
                $output = "";
                if (strpos($clave, "1.3.6.1.4.1.47286.102.3.11") !== false || strpos($clave, "subjectAltName") !== false) {
                    if (is_string($value)) {
                        $aux = explode("\r", $value);
                        if (sizeof($aux) >= 2) {
                            $output = $aux[1];
                        } else {
                            $output = $value;
                        }
                    } elseif (is_array($value)) {
                        foreach ($value as $val) {
                            if (isset($val['otherName']['value']['ia5String'])) {
                                $output = ltrim($val['otherName']['value']['ia5String'], '0');
                                break;
                            }
                        }
                    }
                    if ($output !== "") return true;
                }
            }
        }
        // Fallback to serialNumber if no RUC found
        if (isset($this->certData['subject']['serialNumber'])) {
            $output = ltrim($this->certData['subject']['serialNumber'], '0');
            return true;
        }
        $output = '';
        return true; // RUC is optional
    }

    public function generarId()
    {
        $this->signatureID = $this->random();
        $this->signedInfoID = $this->random();
        $this->signedPropertiesID = $this->random();
        $this->signatureValueID = $this->random();
        $this->certificateID = $this->random();
        $this->referenceID = $this->random();
        $this->signatureSignedPropertiesID = $this->random();
        $this->signatureObjectID = $this->random();
    }

    private function random()
    {
        return rand(100000, 999999);
    }

    public function getValidoHasta()
    {
        return date('Y-m-d H:i:s', $this->certData['validTo_time_t']);
    }

    public function firmar($xml, $docFirmados)
    {
        $respuesta = null;

        try {
            if (empty($this->publicKey) || empty($this->privateKey)) {
                return array('error' => true, 'mensaje' => 'Clave pública o privada no disponible');
            }

            $payload = $xml;
            $xml = new DOMDocument();
            if (!$xml->loadXML($payload)) {
                $errors = libxml_get_errors();
                $errorMsg = "XML inválido: ";
                foreach ($errors as $error) {
                    $errorMsg .= $error->message . " ";
                }
                return array('error' => true, 'mensaje' => $errorMsg);
            }
            $xml->formatOutput = false;
            $xml->preserveWhiteSpace = false;

            $xmlContent = $xml->saveXML($xml->documentElement);
            if ($xmlContent === false) {
                return array('error' => true, 'mensaje' => 'No se pudo serializar el XML');
            }

            $xmlns = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
            $algorithm = $this->config['algorithm'] === 'sha256' ? 'sha256' : 'sha1';
            $signatureMethod = $algorithm === 'sha256' ? 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256' : 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
            $digestMethod = $algorithm === 'sha256' ? 'http://www.w3.org/2001/04/xmlenc#sha256' : 'http://www.w3.org/2000/09/xmldsig#sha1';

            $signTime = is_null($this->signTime) ? time() : $this->signTime;
            $certDigest = $this->getcertDigest();
            if ($certDigest === "") {
                return array('error' => true, 'mensaje' => 'No se pudo generar el digest del certificado');
            }
            $certIssuer = $this->getIssuer();
            $serialNumber = $this->getSerial();

            $prop = '<etsi:SignedProperties Id="Signature' . $this->signatureID .
                '-SignedProperties' . $this->signatureSignedPropertiesID . '">' .
                '<etsi:SignedSignatureProperties>' .
                '<etsi:SigningTime>' . date('c', $signTime) . '</etsi:SigningTime>' .
                '<etsi:SigningCertificate>' .
                '<etsi:Cert>' .
                '<etsi:CertDigest>' .
                '<ds:DigestMethod Algorithm="' . $digestMethod . '"></ds:DigestMethod>' .
                '<ds:DigestValue>' . $certDigest . '</ds:DigestValue>' .
                '</etsi:CertDigest>' .
                '<etsi:IssuerSerial>' .
                '<ds:X509IssuerName>' . $certIssuer . '</ds:X509IssuerName>' .
                '<ds:X509SerialNumber>' . $serialNumber . '</ds:X509SerialNumber>' .
                '</etsi:IssuerSerial>' .
                '</etsi:Cert>' .
                '</etsi:SigningCertificate>' .
                '</etsi:SignedSignatureProperties>' .
                '<etsi:SignedDataObjectProperties>' .
                '<etsi:DataObjectFormat ObjectReference="#Reference-ID-' . $this->referenceID . '">' .
                '<etsi:Description>contenido comprobante</etsi:Description>' .
                '<etsi:MimeType>text/xml</etsi:MimeType>' .
                '</etsi:DataObjectFormat>' .
                '</etsi:SignedDataObjectProperties>' .
                '</etsi:SignedProperties>';

            $modulus = $this->getModulus();
            if ($modulus === "") {
                return array('error' => true, 'mensaje' => 'No se pudo obtener el módulo de la clave privada');
            }
            $exponent = $this->getExponent();
            if ($exponent === "") {
                return array('error' => true, 'mensaje' => 'No se pudo obtener el exponente de la clave privada');
            }
            $publicPEM = $this->getPublicPem();

            $kInfo = '<ds:KeyInfo Id="Certificate' . $this->certificateID . '">' . "\n" .
                '<ds:X509Data>' . "\n" .
                '<ds:X509Certificate>' . "\n" . $publicPEM . "\n" . '</ds:X509Certificate>' . "\n" .
                '</ds:X509Data>' . "\n" .
                '<ds:KeyValue>' . "\n" .
                '<ds:RSAKeyValue>' . "\n" .
                '<ds:Modulus>' . "\n" . $modulus . "\n" . '</ds:Modulus>' . "\n" .
                '<ds:Exponent>' . $exponent . '</ds:Exponent>' . "\n" .
                '</ds:RSAKeyValue>' . "\n" .
                '</ds:KeyValue>' . "\n" .
                '</ds:KeyInfo>';

            $propDigest = base64_encode(hash($algorithm, str_replace('<etsi:SignedProperties',
                '<etsi:SignedProperties ' . $xmlns, $prop), true));

            $aux = str_replace('<ds:KeyInfo', '<ds:KeyInfo ' . $xmlns, $kInfo);
            $kInfoDigest = base64_encode(hash($algorithm, $aux, true));

            $documentDigest = base64_encode(hash($algorithm, $xmlContent, true));

            $sInfo = '<ds:SignedInfo Id="Signature-SignedInfo' . $this->signedInfoID . '">' . "\n" .
                '<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">' .
                '</ds:CanonicalizationMethod>' . "\n" .
                '<ds:SignatureMethod Algorithm="' . $signatureMethod . '">' .
                '</ds:SignatureMethod>' . "\n" .
                '<ds:Reference Id="SignedPropertiesID' . $this->signedPropertiesID . '" ' .
                'Type="http://uri.etsi.org/01903#SignedProperties" ' .
                'URI="#Signature' . $this->signatureID . '-SignedProperties' .
                $this->signatureSignedPropertiesID . '">' . "\n" .
                '<ds:DigestMethod Algorithm="' . $digestMethod . '">' .
                '</ds:DigestMethod>' . "\n" .
                '<ds:DigestValue>' . $propDigest . '</ds:DigestValue>' . "\n" .
                '</ds:Reference>' . "\n" .
                '<ds:Reference URI="#Certificate' . $this->certificateID . '">' . "\n" .
                '<ds:DigestMethod Algorithm="' . $digestMethod . '">' .
                '</ds:DigestMethod>' . "\n" .
                '<ds:DigestValue>' . $kInfoDigest . '</ds:DigestValue>' . "\n" .
                '</ds:Reference>' . "\n" .
                '<ds:Reference Id="Reference-ID-' . $this->referenceID . '" URI="#comprobante">' . "\n" .
                '<ds:Transforms>' . "\n" .
                '<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">' .
                '</ds:Transform>' . "\n" .
                '</ds:Transforms>' . "\n" .
                '<ds:DigestMethod Algorithm="' . $digestMethod . '">' .
                '</ds:DigestMethod>' . "\n" .
                '<ds:DigestValue>' . $documentDigest . '</ds:DigestValue>' . "\n" .
                '</ds:Reference>' . "\n" .
                '</ds:SignedInfo>';

            $signaturePayload = str_replace('<ds:SignedInfo', '<ds:SignedInfo ' . $xmlns, $sInfo);

            $resp = $this->sign($signaturePayload, $signatureResult);
            if ($resp != null) return $resp;

            if ($signatureResult != null) {
                $sig = '<ds:Signature ' . $xmlns . ' Id="Signature' . $this->signatureID . '">' . "\n" .
                    $sInfo . "\n" .
                    '<ds:SignatureValue Id="SignatureValue' . $this->signatureValueID . '">' . "\n" .
                    $signatureResult . "\n" .
                    '</ds:SignatureValue>' . "\n" .
                    $kInfo . "\n" .
                    '<ds:Object Id="Signature' . $this->signatureID . '-Object' . $this->signatureObjectID . '">' .
                    '<etsi:QualifyingProperties Target="#Signature' . $this->signatureID . '">' .
                    $prop .
                    '</etsi:QualifyingProperties>' .
                    '</ds:Object>' .
                    '</ds:Signature>';

                if ($this->tipoComprobante === '01') {
                    $xmlContent = str_replace('</factura>', $sig . '</factura>', $xmlContent);
                } elseif ($this->tipoComprobante === '07') {
                    $xmlContent = str_replace('</comprobanteRetencion>', $sig . '</comprobanteRetencion>', $xmlContent);
                } elseif ($this->tipoComprobante === '06') {
                    $xmlContent = str_replace('</guiaRemision>', $sig . '</guiaRemision>', $xmlContent);
                } elseif ($this->tipoComprobante === '04') {
                    $xmlContent = str_replace('</notaCredito>', $sig . '</notaCredito>', $xmlContent);
                } else {
                    return array('error' => true, 'mensaje' => 'Tipo de comprobante no soportado: ' . $this->tipoComprobante);
                }

                $xmlSigned = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $xmlContent;

                try {
                    if (!is_writable($docFirmados)) {
                        return array('error' => true, 'mensaje' => "No se puede escribir en el directorio de documentos firmados: $docFirmados");
                    }
                    if (!file_put_contents($docFirmados . DIRECTORY_SEPARATOR . $this->claveAcceso . '.xml', $xmlSigned)) {
                        return array('error' => true, 'mensaje' => "No se pudo guardar el archivo firmado: $docFirmados/$this->claveAcceso.xml");
                    }
                } catch (Exception $ex) {
                    return array('error' => true, 'mensaje' => "El documento fue firmado pero no se pudo guardar: " . $ex->getMessage());
                }
            } else {
                return array('error' => true, 'mensaje' => 'Error desconocido en la firma del documento');
            }
        } catch (Exception $ex) {
            return array('error' => true, 'mensaje' => "Excepción en firmar: " . $ex->getMessage());
        }

        return $respuesta;
    }

    public function getcertDigest()
    {
        $algorithm = $this->config['algorithm'] === 'sha256' ? 'sha256' : 'sha1';
        $certDigest = openssl_x509_fingerprint($this->certificate, $algorithm, true);
        if ($certDigest === false) {
            $error = openssl_error_string();
            return "";
        }
        $certDigest = base64_encode($certDigest);
        return $certDigest;
    }

    public function getIssuer()
    {
        $reversed = array_reverse($this->certData['issuer']);
        $certIssuer = array();
        foreach ($reversed as $item => $value) {
            $certIssuer[] = $item . '=' . $value;
        }
        return implode(',', $certIssuer);
    }

    public function getSerial()
    {
        return $this->certData['serialNumber'];
    }

    public function getModulus()
    {
        $details = openssl_pkey_get_details($this->privateKey);
        if ($details === false || !isset($details['rsa']['n'])) {
            return "";
        }
        $modulus = wordwrap(base64_encode($details['rsa']['n']), $this->config['wordwrap'], "\n", true);
        return $modulus;
    }

    public function getExponent()
    {
        $details = openssl_pkey_get_details($this->privateKey);
        if ($details === false || !isset($details['rsa']['e'])) {
            return "";
        }
        $exponent = wordwrap(base64_encode($details['rsa']['e']), $this->config['wordwrap'], "\n", true);
        return $exponent;
    }

    public function sign($dataTosign, &$firmado = null)
    {
        $respuesta = null;
        try {
            $algorithm = $this->config['algorithm'] === 'sha256' ? 'sha256' : 'sha1';
            if (!openssl_sign($dataTosign, $signature, $this->privateKey, $algorithm)) {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "Error en openssl_sign: $error");
            }
            openssl_free_key($this->privateKey);

            if (openssl_verify($dataTosign, $signature, $this->publicKey, $algorithm) != 1) {
                $error = openssl_error_string();
                return array('error' => true, 'mensaje' => "Error al verificar la firma: $error");
            }
            $firmado = wordwrap(base64_encode($signature), $this->config['wordwrap'], "\n", true);
            openssl_free_key($this->publicKey);
        } catch (Exception $ex) {
            return array('error' => true, 'mensaje' => "Excepción en sign: " . $ex->getMessage());
        }

        return $respuesta;
    }
}