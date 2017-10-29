<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icewhale_Translator
{
    /**
     * Translate ISO 3166 alpha-3 country code to ISO 3166 numeric country ID's
     *
     * @param $iso_code string ISO 3166 alpha-3 country code
     * @return string ISO 3166 numeric country ID
     */
    public static function getHttpResponse ($code)
    {
        $translations = array(
            '0' => 'Can not complete the process.',
            '200'=>'OK',
            '408'=>'Missing ID parameter!'
        );

        if (isset($translations[$code]))
        {
            return $translations[$code];
        }

        return FALSE;
    }
}
