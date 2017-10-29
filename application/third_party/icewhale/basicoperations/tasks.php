<?php

class Icewhale_BasicOperations_Tasks extends Icewhale_Operation
{
    protected $_action     = 'basicoperations';
    protected $_operation  = 'tasks';
    protected $_parameters = array(
        'memberId'            => array(
            'required' => TRUE,
        ),
        'memberGuid'          => array(
            'required' => TRUE,
        ),
    );
}

