<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * PyroCMS Date Helpers
 * 
 * This overrides Codeigniter's helpers/date_helper.php
 *
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 * @package		PyroCMS\Core\Helpers
 */


if (!function_exists('get_username_by_id')) {
    function get_username_by_id($id){
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $param = array('id'=>$id);
        $usn = $CI->user_m->get($param);
        if(!empty($usn->username)){
            return $usn->username;
        }else{
            return '';
        }
    }
}

if (!function_exists('get_email_by_id')) {
    function get_email_by_id($id){
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $param = array('id'=>$id);
        $ttt = $CI->user_m->get($param);
        if(!empty($ttt->email)){
            return $ttt->email;
        }else{
            return '';
        }
    }
}

if (!function_exists('truncate')) {
    function truncate($text, $length) {
        $tail = max(0, $length-10);
        $trunk = substr($text, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($text, $tail, $length-$tail))));
        return $trunk;
    }
}

if (!function_exists('check_user_permission')) {
    function check_user_permission($current_user, $module, $permissions){
        if ($current_user){
            // Admins can go straight in
            if ($current_user->group === 'admin'){
                return true;
            }else if(array_key_exists($module, $permissions)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
