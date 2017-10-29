<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'third_party/icewhale/init/icewhale_autoload.php');

class Icewhale
{
    private $CI;

    public function __construct(){

        $this->CI = &get_instance();

    }

    public function tasks(){

        try{

            $client = new Icewhale_Client(Icewhale_Client::ENV_TEST);

            $operation = new Icewhale_BasicOperations_Tasks();
            $operation->setMember('1234', 'AAAA-AAAA-AAAA');

            $client->call($operation);

            $response = array(
                'ResultState'=>$operation->getResultCode(),
                'ResultMessage'=>$operation->getResultMessage(),
                'TotalItems'=>$operation->getTotalResultItems(),
                'ResultObj'=>$operation->getResultObjectData()
            );

        }catch(Icewhale_Exception $e){

            $response = array(
                'ResultState'=>true,
                "ResultCode"=>$e->getCode(),
                'ResultMessage'=>$e->getMessage()
            );
        }

        return $response;
    }
}