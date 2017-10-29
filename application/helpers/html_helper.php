<?php

/**
 * Init admin head
 * @param  boolean $aside should include aside
 */
function init_head($aside = true)
{
    $CI =& get_instance();
    $CI->load->view('whale/includes/head');
    $CI->load->view('whale/includes/header');
}

/**
 * Init admin aside navigation
 * @param  boolean $aside should include aside
 */
function init_aside($aside = true)
{
    $CI =& get_instance();
    $CI->load->view('whale/includes/aside');
}

/**
 * Init admin statistics
 * @param  boolean $aside should include aside
 */
function init_stats($aside = true)
{
    $CI =& get_instance();
    $CI->load->view('whale/includes/stats');
}

/**
 * Init admin footer/tails
 */
function init_tail()
{
    $CI =& get_instance();
    $CI->load->view('whale/includes/scripts');
}