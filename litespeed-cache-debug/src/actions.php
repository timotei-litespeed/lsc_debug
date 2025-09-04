<?php

function lsc_debug_credentials_generate(){
    $random_pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $username = 'debug_' . substr(str_shuffle($random_pool), 0, 10);
    $password = 'debug_' . substr(str_shuffle($random_pool), 0, 10);

    update_option(LSCWP_DEBUG_ACCESS['user'], $username);
    update_option(LSCWP_DEBUG_ACCESS['password'], $password);
}
function lsc_debug_credentials_remove(){
    delete_option(LSCWP_DEBUG_ACCESS['user']);
    delete_option(LSCWP_DEBUG_ACCESS['password']);
}

function lsc_debug_generate_server_info($echo = true){
    $html = '<style>
        .lsc_info-container { background-color: #f5f5f5; padding: 20px; border-radius: 5px; }
        .lsc_info-item { margin-bottom: 15px; }
        .lsc_info-label { font-weight: bold; margin-bottom: 5px; }
        .lsc_info-value { background: #fff; padding: 10px; border-left: 4px solid #0073aa; }

        .phpinfo { color: #222; font-family: sans-serif; font-size: 1.1em; }
        .phpinfo pre { margin: 0; font-family: monospace; }
        .phpinfo a:link { color: #009; text-decoration: none; background-color: #fff; }
        .phpinfo a:hover { text-decoration: underline; }
        .phpinfo table { border-collapse: collapse; border: 0; max-width: 934px; width: 100%;  box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.2); }
        .phpinfo .center { text-align: center; }
        .phpinfo .center table { margin: 1em auto; text-align: left; }
        .phpinfo .center th { text-align: center !important; }
        .phpinfo td,
        .phpinfo th { border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px; }
        .phpinfo th { position: sticky; top: 0; background: inherit; }
        .phpinfo h1 { font-size: 150%; }
        .phpinfo h2 { font-size: 125%; }
        .phpinfo h2 a:link,
        .phpinfo h2 a:visited { color: inherit;  background: inherit; }
        .phpinfo .p { text-align: left; }
        .phpinfo .e { background-color: #ccf; width: 300px; font-weight: bold; }
        .phpinfo .h { background-color: #99c; font-weight: bold; }
        .phpinfo .v { background-color: #ddd; max-width: 300px; overflow-x: auto;  word-wrap: break-word; }
        .phpinfo .v i { color: #999; }
        .phpinfo img { float: right;  border: 0; }
        .phpinfo hr { width: 100%; max-width: 934px; background-color: #ccc; border: 0; height: 1px; }
    </style>
    <h1>Server Information</h1>
    <div class="lsc_info-container">
        <div class="lsc_info-item">
            <div class="lsc_info-label">$_SERVER["REMOTE_ADDR"]:</div>
            <div class="lsc_info-value">'.(isset($_SERVER['REMOTE_ADDR']) ? htmlspecialchars($_SERVER['REMOTE_ADDR']) : 'Not set') . '</div>
        </div>
        <div class="lsc_info-item">
            <div class="lsc_info-label">$_SERVER["HTTP_X_FORWARDED_FOR"]:</div>
            <div class="lsc_info-value">'.(isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']) : 'Not set').'</div>
        </div>
        <div class="lsc_info-item">
            <div class="lsc_info-label">$_SERVER["PROXY_REMOTE_ADDR"]:</div>
            <div class="lsc_info-value">'.(isset($_SERVER['PROXY_REMOTE_ADDR']) ? htmlspecialchars($_SERVER['PROXY_REMOTE_ADDR']) : 'Not set').'</div>
        </div>
        <div class="lsc_info-item">
            <div class="lsc_info-label">PHP Information:</div>
            <div class="lsc_info-value phpinfo">';
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        $html .= $phpinfo .'
            </div>
        </div>
    </div>';

    if($echo) echo $html;
    else return $html;
}

function lsc_debug_test_async($echo = true){
    $html = '<h1>Async LiteSpeed Test Results</h1>';

    $args = array(
        'timeout'   => 100,
        'blocking'  => true,
        'sslverify' => false,
        // 'cookies'   => $_COOKIE,
    );

    $url = admin_url('admin-ajax.php') . '?action=async_litespeed&nonce=123123123&litespeed_type=imgoptm_force';
    $result = wp_remote_post(esc_url_raw($url), $args);

    $status_code = wp_remote_retrieve_response_code($result);

    $status_explanation = '';

    if ($status_code == 200) {
        $status_explanation = '<div style="padding: 10px; background-color: #e7f7e3; border-left: 4px solid #46b450; margin-bottom: 15px;">
                <p>✅ Success: The server responded properly to the async request.</p>
            </div>';
    } else {
        $status_explanation = '<div style="padding: 10px; background-color: #ffeaea; border-left: 4px solid #dc3232; margin-bottom: 15px;">
                <p>❌ Error: The server returned a non-success status code: ' . $status_code . '</p>
                <p>This is most likely due to a security plugin blocking the request.</p>
                <p>Common causes: Wordfence, Sucuri, or other security plugins that block internal AJAX calls.</p>
                <p>Try temporarily disabling security plugins and test again.</p>
            </div>';
    }

    $html .= $status_explanation;
    $html .= '<p><strong>Response Status Code:</strong> ' . $status_code . '</p>
        <p><strong>Full Response:</strong></p>
        <pre style="background-color: #fff; padding: 10px; overflow: auto; max-height: 300px;">'.print_r($result, true).'</pre>';

    if($echo) echo $html;
    else return $html;
}

function lsc_debug_clear_err_domains(){
    \LiteSpeed\Cloud::save_summary(['err_domains' => []]);
}

function lsc_debug_clear_disabled_nodes(){
    \LiteSpeed\Cloud::save_summary(['disabled_node' => []]);
}

function lsc_debug_clear_settings(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'options';
    $table_name = "{$wpdb->prefix}options";
    $wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE `option_name` like '%litespeed.%' " ) );


    if ( defined( 'LSCWP_V' ) ) {
        do_action( 'litespeed_purge_all' );
    }
}

// ---------- Reset TTL ----------
function lsc_debug_reset_ttl() {
    if ( ! is_admin() || ! current_user_can('manage_options') ) {
        return;
    }
    if (
        empty($_POST['litespeed_debug_nonce']) ||
        ! wp_verify_nonce($_POST['litespeed_debug_nonce'], 'litespeed_debug_reset_ttl')
    ) {
        return;
    }
    if ( ! class_exists('\LiteSpeed\Cloud') ) {
        return;
    }
    \LiteSpeed\Cloud::save_summary([
        'ttl.ccss' => 0,
        'ttl.ucss' => 0,
        'ttl.vpi'  => 0,
    ]);
}

// ---------- Image node ----------
function lsc_debug_redetect_image_node($node = null){
    if ( ! is_admin() || ! current_user_can('manage_options') ) {
        return;
    }
    // Allow mapped call
    if ($node === null) {
        if (
            empty($_POST['litespeed_debug_nonce']) ||
            ! wp_verify_nonce($_POST['litespeed_debug_nonce'], 'litespeed_debug_redetect_image')
        ) {
            return;
        }
        $node = isset($_POST['image_node']) ? sanitize_text_field($_POST['image_node']) : '';
    }

    if ( ! $node || ! class_exists('\LiteSpeed\Cloud') ) {
        return;
    }

    // normalize to lowercase value
    $node = strtolower($node);
    \LiteSpeed\Cloud::save_summary([
        'server.img_optm' => 'https://' . $node . '.quic.cloud',
    ]);
}

// ---------- Page node ----------
function lsc_debug_redetect_page_node($service = null, $node = null){
    if ( ! is_admin() || ! current_user_can('manage_options') ) {
        return;
    }
    // Allow mapped call
    if ($service === null || $node === null) {
        if (
            empty($_POST['litespeed_debug_nonce']) ||
            ! wp_verify_nonce($_POST['litespeed_debug_nonce'], 'litespeed_debug_redetect_page')
        ) { 
            return;
        }
        $service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '';
        $node    = isset($_POST['page_node']) ? sanitize_text_field($_POST['page_node']) : '';
    }

    if ( ! $service || ! $node || ! class_exists('\LiteSpeed\Cloud') ) {
        return;
    }

    $map = [
        'UCSS'             => 'server.ucss',
        'CCSS'             => 'server.ccss',
        'VPI'              => 'server.vpi',
        'LQIP'             => 'server.lqip',
        'Page Load Time'   => 'server.health',
        'PageSpeed Score'  => 'server.health',
    ];

    if ( ! isset($map[$service]) ) {
        return;
    }

    $node = strtolower($node);
    \LiteSpeed\Cloud::save_summary([
        $map[$service] => 'https://' . $node . '.quic.cloud',
    ]);
}

// ---------- Import Report settings ----------
function lsc_debug_import_report() {
    if ( ! is_admin() || ! current_user_can('manage_options') ) {
        return;
    }
    if (
        empty($_POST['litespeed_debug_nonce']) ||
        ! wp_verify_nonce($_POST['litespeed_debug_nonce'], 'litespeed_debug_import_settings')
    ) {
        return;
    }
    if ( ! class_exists('\LiteSpeed\Conf') ) {
        throw new Exception('LSC not active');
        return;
    }
    
    if( isset($_POST['report_settings']) && !empty( $_POST['report_settings'] ) ){
        $settings = lsc_debug_parse_settings($_POST['report_settings']);
        if( isset( $settings['_version'] ) ) unset($settings['_version']);
        if( isset( $settings['hash'] ) ) unset($settings['hash']);
        if( isset( $settings['api_key'] ) ) unset($settings['api_key']);
        if( isset( $settings['news'] ) ) unset($settings['api_key']);
        if( isset( $settings['server_ip'] ) ) unset($settings['server_ip']);

        lsc_debug_save_settings($settings);
    }
    else{
        throw new Exception('Error');
    }
}