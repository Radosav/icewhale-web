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

//        $response = '[{"name":"Renamed","start":"0001-01-01T00:00:00Z","due":"0001-01-01T00:00:00Z","assignee":{},"time spent":"0001-01-01T00:00:00Z"},{"id":5,"name":"Set up Server","start":"0001-01-01T00:00:00Z","due":"0001-01-01T00:00:00Z","assignee":{"id":2,"name":"Ahmet","description":"Pro. Sys admin and Security Expert ( Hacker )","status":true,"cost":"0.75 coffee/h, 1.5 Packs of cigarettes/day","mail":"ahmet.gudenoglu@gmail.com","role":"Admin","position":"System Admin","image":"http://3.images.southparkstudios.com/blogs/southparkstudios.com/files/2014/02/1008-MWBZ-faq-q1.jpg?quality=0.8"},"time spent":"0001-01-01T00:00:00Z"},{"id":6,"name":"Write API","start":"0001-01-01T00:00:00Z","due":"0001-01-01T00:00:00Z","assignee":{"id":1,"name":"Radosav","description":"Totally Awsome Coder","status":true,"cost":"1 coffee/h, 2 Packs of cigarettes/day","mail":"rasabrajic@gmail.com","role":"Admin","position":"Api Coder","image":"https://avatars1.githubusercontent.com/u/6116078?s=400\u0026u=85e96b04d88f1dddb7b5603bdcbab1002cadc71c\u0026v=4"},"time spent":"0001-01-01T00:00:00Z"},{"name":"Renamed","start":"0001-01-01T00:00:00Z","due":"0001-01-01T00:00:00Z","assignee":{},"time spent":"0001-01-01T00:00:00Z"}]';

        $result = array(
            'IceObj'=>(isJson($response) ? json_decode($response,true) : "NULL"),
            'Result'=>$httpcode,
            'Message'=>Icewhale_Translator::getHttpResponse($httpcode)
        );

        return $result;
    }
}