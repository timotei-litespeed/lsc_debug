<h1>LiteSpeed Cache Debug Helper</h1>

<?php
if( !lsc_debug_show_admin_content() ){ ?>
    <a href="<?php echo lsc_debug_admin_create_link(); ?>">Go back</a>
    <br />
<?php
}

if(lsc_debug_test_if_debug_action()){
    $action = lsc_debug_get_action();
    if($action){
        $function = lsc_debug_get_action_function($action);
        if( function_exists($function) ){
            try{
                $function();
                lsc_debug_function_run_ok($action);
            }
            catch(Exception $e){
                lsc_debug_function_run_error($action);
            }
        }
        else echo 'Incorrect function called.';
    }
    else echo 'Incorrect action added.';
}

if( lsc_debug_show_admin_content() ){
?>
    <br />
    <h2>Debug Credentials</h2>
    <?php
        $credentials = lsc_debug_get_access();
        $username = $credentials[0];
        $password = $credentials[1];

        if ($username !== '' && $password !== ''): ?>
        <p style="color: red;">Please do remember to disable and delete the helper plugin after troubleshoot is completed</p>
        <p>the debug user/pass will be deleted upon helper plugin deactivation</p>
        <p>These credentials can be used for debugging purposes:</p>
        <p><strong>Username:</strong> <span style="color: red; font-weight: bold; font-size: 18px;"><?php echo esc_html($username); ?></span></p>
        <p><strong>Password:</strong> <span style="color: red; font-weight: bold; font-size: 18px;"><?php echo esc_html($password); ?></span></p>
        <p class="description">Keep these credentials secure and do not share them publicly.</p>
        <p>You can put them Toolbox -> Report -> Report note , to carry these credentials to support staff when you need help.</p>
    <?php else: ?>
        <p>No credentials are currently set.</p>
    <?php endif; ?>
    <form method="post" action="<?php echo lsc_debug_admin_create_link('credentials_generate'); ?>" style="display: inline-block; margin-right: 10px;">
        <?php wp_nonce_field('litespeed_debug_credentials', 'litespeed_debug_nonce'); ?>
        <input type="hidden" name="action" value="generate_credentials">
        <button type="submit" class="button button-primary">Generate Credentials</button>
    </form>

    <div style="margin-top: 30px; padding: 15px; background-color: #f8f8f8; border-left: 4px solid #46b450;">
        <h3 style="cursor: pointer;" onclick="jQuery('#how_plugin_works').toggle();">How This Plugin Works (<small>i</small>)</h3>
        <div id="how_plugin_works" style="display: none;">
            <p>This plugin helps you (as well as support staff) troubleshoot <strong>LiteSpeed Cache</strong> by allowing you to temporarily disable specific cache features without affecting other users.</p>

            <h4>How to Use:</h4>
            <ol>
                <li>Generate credentials using the button above</li>
                <li>Use these credentials in your browser URL with specific parameters to disable cache features:<br>
                    <code id="lsc_debug_code_single" style="display: block; padding: 10px; background: #f0f0f0; margin: 10px 0; font-family: monospace; cursor: pointer;" onClick="litespeed_copy_to_clipboard('lsc_debug_code_single')">
                        <?php echo get_site_url(); ?>/?<?php echo LSCWP_DEBUG_PARAM_USER; ?>=<?php echo !empty($username) ? esc_html($username) : 'YOUR_USERNAME'; ?>&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_ACTION; ?>=set_options&optm-css_min=1
                    </code>
                </li>
                <li>You can combine multiple parameters:<br>
                    <code id="lsc_debug_code_multiple" style="display: block; padding: 10px; background: #f0f0f0; margin: 10px 0; font-family: monospace; cursor: pointer;" onClick="litespeed_copy_to_clipboard('lsc_debug_code_multiple')">
                        <?php echo get_site_url(); ?>/?<?php echo LSCWP_DEBUG_PARAM_USER; ?>=<?php echo !empty($username) ? esc_html($username) : 'YOUR_USERNAME'; ?>&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_ACTION; ?>=set_options&optm-css_min=0&optm-js_defer=2
                    </code>
                </li>
                <li>You can change parameter for another blog:<br>
                    <code id="lsc_debug_code_blog" style="display: block; padding: 10px; background: #f0f0f0; margin: 10px 0; font-family: monospace; cursor: pointer;" onClick="litespeed_copy_to_clipboard('lsc_debug_code_blog')">
                        <?php echo get_site_url(); ?>/?<?php echo LSCWP_DEBUG_PARAM_USER; ?>=<?php echo !empty($username) ? esc_html($username) : 'YOUR_USERNAME'; ?>&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_ACTION; ?>=set_options&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_BLOG; ?>=1&optm-css_min=0&optm-js_defer=2
                    </code>
                </li>
                <li>You can run actions:<br>
                    <code id="lsc_debug_code_action" style="display: block; padding: 10px; background: #f0f0f0; margin: 10px 0; font-family: monospace; cursor: pointer;" onClick="litespeed_copy_to_clipboard('lsc_debug_code_action')">
                        <?php echo get_site_url(); ?>/?<?php echo LSCWP_DEBUG_PARAM_USER; ?>=<?php echo !empty($username) ? esc_html($username) : 'YOUR_USERNAME'; ?>&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_ACTION; ?>=generate_server_info
                    </code>
                </li>
            </ol>
        </div>

        <p><strong>Important:</strong> Remember to deactivate this plugin after troubleshooting is complete for security reasons.</p>
    </div>
    <h2>Info</h2>
    <ul>
        <li><a href="<?php echo lsc_debug_admin_create_link('generate_server_info'); ?>">View Server Info</a></li>
        <li><a href="<?php echo lsc_debug_admin_create_link('test_async'); ?>">Run Async Test</a></li>
    </ul>
    <br />
    <h2>Clears</h2>
    <ul>
        <li><a href="<?php echo lsc_debug_admin_create_link('clear_err_domains'); ?>">Cloud Error Domains</a></li>
        <li><a href="<?php echo lsc_debug_admin_create_link('clear_disabled_nodes'); ?>">Cloud Disabled Nodes</a></li>
    </ul>
<?php } ?>