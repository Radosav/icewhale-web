<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icewhale_Operation
{
    protected $_action     = NULL;
    protected $_operation  = NULL;

    protected $_parameters = array();
    protected $_data       = array();

    protected $_result_object   = NULL;
    protected $_result_code     = NULL;
    protected $_result_message  = NULL;

    public function __construct ()
    {
        $this->_generateDefaultData();
    }

    public function getOperationName ()
    {
        return $this->_operation;
    }

    public function getAction ()
    {
        return $this->_action;
    }

    public function getDataAsArray ()
    {
        return $this->_data;
    }

    public function addData ($name, $value)
    {
        if (isset($this->_data[$name]))
        {
            return $this->_data[$name] = $value;
        }
        else {
            throw new Icewhale_Exception("Variable '{$name}' is not available'");
        }
    }

    public function setMember ($member_id, $member_guid)
    {
        $this->addData('memberId',   $member_id);
        $this->addData('memberGuid', $member_guid);
    }

    public function getResultState ()
    {
        return ($this->_result_code === '0');
    }

    public function getResultCode ()
    {
        return $this->_result_code;
    }

    public function getResultMessage ()
    {
        return $this->_result_message;
    }

    public function getResultObjectData ($key = NULL)
    {
        if (is_null($key))
        {
            return $this->_result_object;
        }
        elseif (isset($this->_result_object['id_'.$key]))
        {
            return $this->_result_object['id_'.$key];
        }
        return NULL;
    }

    public function getTotalResultItems($key = NULL){

        if (is_null($key))
        {
            return count($this->_result_object);
        }
        elseif (isset($this->_result_object['id_'.$key]))
        {
            return count($this->_result_object['id_'.$key]);
        }
        return 0;
    }

    protected function _generateDefaultData ()
    {
        foreach ($this->_parameters as $name => $settings)
        {
            $this->_data[$name] = (isset($settings['default_value']) ? $settings['default_value'] :'');
        }
    }

    public function checkRequiredData ()
    {
        foreach ($this->_parameters as $name => $settings)
        {
            // Check if required field is set (not empty)
            if (isset($settings['required']) && $settings['required'] === TRUE
                && $this->_data[$name] == '')
            {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Save generic information in object from XML object returned by Payvision
     *
     * @param SimpleXMLElement $simplexml
     */
    public function processXmlResult (SimpleXMLElement $simplexml)
    {

        if (isset($simplexml->Result))
        {
            $this->_result_code = $simplexml->Result->__toString();
        }

        if (isset($simplexml->Message))
        {
            $this->_result_message = $simplexml->Message->__toString();
        }

        if (isset($simplexml->IceObj))
        {

            foreach($simplexml->IceObj as $key=>$item)
            {

                $this->_result_object['id_'.$item->id] = $item;
            }
        }
    }
}