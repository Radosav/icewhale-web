<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Tasks extends Whale_controller {

    function __construct(){
        parent::__construct();
    }

    /* List all tasks */
    public function index(){

        $this->get_list();

    }

    public function get_list(){

        $response = $this->icewhale->tasks();

        header('Content-Type: application/json');
        echo json_encode( $response );
        die();
    }

    public function task($id=""){

        header('Content-Type: application/json');

        if (!is_numeric($id)){
            $response = array(
                'Result'=>408,
                'Message'=>Icewhale_Translator::getHttpResponse(408)
            );

            echo json_encode( $response );
            die();
        }

        $response = $this->icewhale->tasks($id);

        if (!$response['ResultObj']){
            $response = array(
                'Result'=>409,
                'Message'=>Icewhale_Translator::getHttpResponse(409)
            );

            echo json_encode( $response );
            die();
        }

        echo json_encode( $response );
        die();
    }
}