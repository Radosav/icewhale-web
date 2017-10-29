<?php

function init_table($tablename,$data=array()){
    $CI =& get_instance();

    if (file_exists(APPPATH . DIRECTORY_SEPARATOR . 'views/whale/tables/' . $tablename . '.php')){

        $CI->load->view('whale/tables/' . $tablename,$data);
    }
}