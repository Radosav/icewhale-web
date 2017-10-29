<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Tasks extends Whale_controller {

    function __construct(){
        parent::__construct();
    }

    /* List all tasks */
    public function index(){

        $title = 'Test';
        $data['tasks'] = $this->icewhale->tasks();
        $data['title'] = $title;
        $this->load->view('whale/tasks/manage', $data);

    }

    public function get_list(){

        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            $response = array(
                'Result'=>406,
                'Message'=>Icewhale_Translator::getHttpResponse(406)
            );

            echo json_encode( $response );
            die();
        }

        $response = $this->icewhale->tasks();

        echo json_encode( $response );
        die();
    }

    public function task($id=""){

        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            $response = array(
                'Result'=>406,
                'Message'=>Icewhale_Translator::getHttpResponse(406)
            );

            echo json_encode( $response );
            die();
        }

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