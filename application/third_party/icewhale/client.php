<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icewhale_Client
{
    const ENV_TEST = 'test';
    const ENV_LIVE = 'live';

    protected $_environment    = NULL;

    protected $_ca_certificates_file = null;


    protected $_icewhale_host = array(
        'test' => '54.93.231.217:8080',
        'live' => '54.93.231.217:8080',
    );

    protected $_arraytoxml;

    public function __construct ($environment = self::ENV_LIVE)
    {
        $this->_environment = $environment;

        if ( ! function_exists('curl_init'))
        {
            throw new Icewhale_Exception('cURL library is required to run this library');
        }

        $this->_arraytoxml = new ArrayToXML();
    }

    public function setCaCertificatesFile ($file)
    {
        if (file_exists($file))
        {
            $this->_ca_certificates_file = $file;
        }

        return false;
    }

    public function call (Icewhale_Operation $operation,$method = "GET")
    {
        if ($operation->checkRequiredData() !== TRUE)
        {
            throw new Icewhale_Exception('Not all required fields are filled');
        }

        if (is_null($operation->getOperationName()) || is_null($operation->getAction()))
        {
            throw new Icewhale_Exception('Operation has no Operation Name or Action specified');
        }

        $res = $this->_performRequest($operation,$method);

        $response = $this->_arraytoxml->toXML($res);

        // XML to Object
        try
        {
            $simplexml = new SimpleXMLElement($response);
        }
        catch (Exception $e)
        {
            throw new Icewhale_Exception('Result from IceWhale is not XML (XML parsing failed: ' . $e->getMessage() . ')');
        }

        try
        {
            $operation->processXmlResult($simplexml);
        }
        catch (Icewhale_Exception $e)
        {
            throw new Icewhale_Exception('Unable to process XML data in ' . get_class($operation) . ' (' . $e->getMessage() . ')');
        }

        return TRUE;
    }

    protected function _performRequest (Icewhale_Operation $operation,$method)
    {
        $url  = 'http://' .
            $this->_icewhale_host[$this->_environment] .
//            '/'.urlencode($operation->getAction()) .
            '/' .
            urlencode($operation->getOperationName());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, TRUE);
                break;
            default:
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        }

        if ($this->_ca_certificates_file)
        {
            curl_setopt($ch, CURLOPT_CAINFO, $this->_ca_certificates_file);
        }

        if ( ! is_null($operation->getDataAsArray()) && is_array($operation->getDataAsArray()))
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($operation->getDataAsArray()));
        }

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $result = array(
            'IceObj'=>(isJson($response) ? json_decode($response,true) : "NULL"),
            'Result'=>$httpcode,
            'Message'=>Icewhale_Translator::getHttpResponse($httpcode)
        );

        return $result;
    }
}