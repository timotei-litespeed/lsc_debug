<?php
use LiteSpeed\Tag;

function lsc_debug_show_admin_content(){
    $action = lsc_debug_get_action();

    return !lsc_debug_show_back($action);
}

function lsc_debug_test_if_debug_action(){
    return isset($_GET[LSCWP_DEBUG_PARAM_ACTION]);
}

function lsc_debug_get_action(){
    return isset($_GET[LSCWP_DEBUG_PARAM_ACTION]) ? $_GET[LSCWP_DEBUG_PARAM_ACTION] : false;
}

// Test user and password
function lsc_debug_get_access(){
    return [ get_option(LSCWP_DEBUG_ACCESS['user'], ''),  get_option(LSCWP_DEBUG_ACCESS['password'], '')];
}

function lsc_debug_test_if_allowed(){
    $allowed = false;
    $username = isset($_GET[LSCWP_DEBUG_PARAM_USER]) ? $_GET[LSCWP_DEBUG_PARAM_USER] : false;
    $password = isset($_GET[LSCWP_DEBUG_PARAM_PASS]) ? $_GET[LSCWP_DEBUG_PARAM_PASS] : false;

    if( $username && $password ){
        $access = lsc_debug_get_access();

        return $username === $access[0] && $password === $access[1];
    }

    return $allowed;
}

// Action name to function
function lsc_debug_get_action_function($action){
    return isset(LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['function']) ? 'lsc_debug_'.LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['function'] : false;
}

// Admin Create admin link
function lsc_debug_admin_create_link($action = false){
    return '?page=litespeed-debug' . ( $action ? '&'.LSCWP_DEBUG_PARAM_ACTION.'='.$action : '' );
}

// Admin Show Back link
function lsc_debug_show_back($action){
    return isset(LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['new_page']) && LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['new_page'];
}

// Admin Messages
function lsc_debug_function_run_ok($action){
    if(!$action) return false;

    $message = isset(LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['message_ok']) ? LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['message_ok'] : false;
    if($message){
        echo lsc_debug_show_message($message);
    }
}


function lsc_debug_function_run_error($action){
    if(!$action) return false;

    $message = isset(LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['message_error']) ? LSCWP_DEBUG_ACTIONS_FUNCTIONS[$action]['message_error'] : false;
    if($message){
        echo lsc_debug_show_message($message, 'error');
    }
}

// Admin Messages HTML
function lsc_debug_show_message($message = false , $type = 'success'){
    if($message){
        return '<div class="notice notice-'.$type.' is-dismissible">
            <p>'.$message.'</p>
        </div>';
    }
    else return '';
}

// Test if link has LSC Debug data
function lsc_debug_link_parse(){
    $action = lsc_debug_test_if_debug_action();

    if($action && !is_admin()){
        $action = lsc_debug_get_action();
        // Not limit out access
        if($action !== "credentials_generate"){
            $is_allowed = lsc_debug_test_if_allowed();

            if($is_allowed){
                if( $action === "set_options" ){
                    $options = $_GET;
                    $blog = false;
                    if(isset($options[LSCWP_DEBUG_PARAM_ACTION])) unset($options[LSCWP_DEBUG_PARAM_ACTION]);
                    if(isset($options[LSCWP_DEBUG_PARAM_USER])) unset($options[LSCWP_DEBUG_PARAM_USER]);
                    if(isset($options[LSCWP_DEBUG_PARAM_PASS])) unset($options[LSCWP_DEBUG_PARAM_PASS]);
                    if(isset($options[LSCWP_DEBUG_PARAM_BLOG]) && is_multisite()){
                        $blog = (int) $options[LSCWP_DEBUG_PARAM_BLOG];
                        unset($options[LSCWP_DEBUG_PARAM_BLOG]);
                    }

                    if($blog){
                        switch_to_blog($blog);
                    }
                    foreach($options as $option => $value){
                        \LiteSpeed\Config::update_option($option, $value);
                        //update_option('litespeed.conf.'.$option, $value);
                    }
                    if($blog){
                        restore_current_blog();
                    }
                }
                else{
                    $function = lsc_debug_get_action_function($action);
                    if( function_exists($function) ){
                        try{
                            $function();
                            if(lsc_debug_show_back($action)){
                                die();
                            }
                            else{
                                lsc_debug_function_run_ok($action);
                            }
                        }
                        catch(Exception $e){
                            lsc_debug_function_run_error($action);
                        }
                    }
                    else echo 'Incorrect function called.';
                }
            }
            else{
                die('No access');
            }
        }
    }
}