<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'third_party/icewhaleinit/icehale_autoload.php');

class Icewhale
{
    private $CI;

    public function __construct(){

        $this->CI = &get_instance();

    }

    public function tasks(){

        $response = array(
            'ResultState'=>false,
        );

        return $response;
    }
}