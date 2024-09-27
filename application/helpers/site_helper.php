<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('is_localhost')){

    /**
     *
     * Return TRUE if app is running locally
     *
     */
    function is_localhost(){
        return $_SERVER['SERVER_NAME'] === 'localhost';
    }
}

if(!function_exists('generate_ref')){

    /**
     *
     * Generate unique alphanumeric ref
     *
     */
    function generate_ref($length=8){
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

if(!function_exists('return_json')){

    /**
     *
     * Response as JSON
     *
     */
    function return_json($data){
        $ci =& get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}

if(!function_exists('flash_messages')){

    /**
     *
     * Render flashdata messages
     * 
     * @param flashdataKey: Session key for flash data
     *
     */
    function flash_messages($flashdataKey){
        $ci =& get_instance();
        $success = $ci->session->flashdata($flashdataKey . '_success');
        $fail = $ci->session->flashdata($flashdataKey . '_fail');
        $status = $ci->session->flashdata($flashdataKey . '_status');

        if($success != ''){
            echo '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    ' . $success . '
                </div>';
        }

        if($fail != ''){
            echo '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    ' . $fail . '
                </div>';
        }

        if($status != ''){
            echo '<div class="alert alert-info">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    ' . $status . '
                </div>';
        }

        $ci->session->unset_userdata($flashdataKey . '_success');
        $ci->session->unset_userdata($flashdataKey . '_fail');
        $ci->session->unset_userdata($flashdataKey . '_status');
    }
}

if(!function_exists('ci_post')){

    /**
     *
     * Get post object value. Default to empty
     * 
     * @param key: Key of post object
     *
     */
    function ci_post($key=''){
        $ci =& get_instance();
        return !empty($key) ? $ci->input->post($key) : $ci->input->post();
    }
}

if (!function_exists('get_api_vars')){

    /* Check if value isset, add null value if not */
    function get_api_vars($field=''){
        
        if($field != ''){
            return isset($_REQUEST[$field]) ? $_REQUEST[$field] : '';
        }
        else{
            return $_REQUEST;
        }
        /*
        parse_str($_SERVER['QUERY_STRING'], $query);

        return !empty($query[$field]) ? $query[$field] : '';
        */
    }
}

if (!function_exists('get_json_api_vars')){

    /* Check if value isset, add null value if not */
    function get_json_api_vars($field, $defaultValue=''){
        $postVars = (object) json_decode(file_get_contents('php://input'));
        return isset($postVars->$field) ? $postVars->$field : $defaultValue;
    }
}

if(!function_exists('pad_num')){

    /**
     *
     * Add leading zeros to number
     *
     */
    function pad_num($number){
        return str_pad($number, 4, 0, STR_PAD_LEFT);
    }
}

if(!function_exists('date_str')){

    /*
     * Create a long/short format
     * Ref: http://www.w3schools.com/php/func_date_date.asp
     * 
     * @format
     * short (default): January 1st, 2015
     * mini: 1/1/2015
     * long: Saturday 18th of April 2015 05:39:58 AM
     *
     * @date_add: how much time after added date
     *
     */
    function date_str($date, $format='mini', $dateAdd=''){
        if($format == 'long'){
            $fmt = 'l jS \of F Y h:i:s A';
        }
        else if($format == 'mini'){
            $fmt = 'Y-m-d';
        }
        else{
            $fmt = 'M jS, Y';
        }

        $dateStr = strtotime($date);

        if($dateAdd != ''){
            $dateStr = strtotime($date . $dateAdd);
        }
        return date($fmt, $dateStr);
    }
}