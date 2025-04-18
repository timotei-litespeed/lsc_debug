<?php
// LSC Main parameter
!defined('LSCWP_DEBUG_PARAM_ACTION') && define('LSCWP_DEBUG_PARAM_ACTION', 'LSCWP_DEBUG_PARAM' );
!defined('LSCWP_DEBUG_PARAM_BLOG') && define('LSCWP_DEBUG_PARAM_BLOG', 'lsc_debug_blog' );

// User and Pass link parameter
!defined('LSCWP_DEBUG_PARAM_USER') && define('LSCWP_DEBUG_PARAM_USER', 'lsc_debug_user' );
!defined('LSCWP_DEBUG_PARAM_PASS') && define('LSCWP_DEBUG_PARAM_PASS', 'lsc_debug_pass' );

// User and Pass options names
!defined('LSCWP_DEBUG_ACCESS') && define('LSCWP_DEBUG_ACCESS', [
    'user' => '.litespeed-debug.user',
    'password' => '.litespeed-debug.password'
]);

// Actions map
!defined('LSCWP_DEBUG_ACTIONS_FUNCTIONS') && define('LSCWP_DEBUG_ACTIONS_FUNCTIONS', [
    'credentials_generate' => [ 'function' => 'credentials_generate', 'message_ok' => 'Credentials generated', 'message_error' => 'Credentials NOT generated' ],
    'credentials_remove' => [ 'function' => 'credentials_remove', 'message_ok' => 'Credentials removed', 'message_error' => 'Credentials NOT removed' ],
    'generate_server_info' => [ 'function' => 'generate_server_info', 'new_page' => true ],
    'test_async' => [ 'function' => 'test_async', 'new_page' => true ],
    'clear_err_domains' => [ 'function' => 'clear_err_domains', 'message_ok' => 'Error Domains cleared', 'message_error' => 'Error Domains NOT cleared' ],
    'clear_disabled_nodes' => [ 'function' => 'clear_disabled_nodes', 'message_ok' => 'Disabled nodes cleared', 'message_error' => 'Disabled nodes NOT cleared' ],
    'set_options' => [ 'function' => 'set_options' ],
    'clear_settings' => [ 'function' => 'clear_settings', 'message_ok' => 'All settings cleared', 'message_error' => 'Settings NOT cleared' ],
]);