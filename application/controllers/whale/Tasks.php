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
    }
}