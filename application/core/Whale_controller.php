<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class Whale_controller extends CI_Controller
{
    private $_current_version;

    function __construct()
    {
        parent::__construct();

        $this->load->library('icewhale/icewhale','icewhale');
        $this->load->library('arraytoxml');
    }
}