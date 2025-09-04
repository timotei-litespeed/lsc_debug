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
                        <?php echo get_site_url(); ?>/?<?php echo LSCWP_DEBUG_PARAM_USER; ?>=<?php echo !empty($username) ? esc_html($username) : 'YOUR_USERNAME'; ?>&<?php echo LSCWP_DEBUG_PARAM_PASS; ?>=<?php echo !empty($password) ? esc_html($password) : 'YOUR_PASSWORD'; ?>&<?php echo LSCWP_DEBUG_PARAM_ACTION; ?>=set_options&<?php echo LSCWP_DEBUG_PARAM_BLOG; ?>=1&optm-css_min=0&optm-js_defer=2
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
        <li><a href="<?php echo lsc_debug_admin_create_link('clear_settings'); ?>">All settings !!!Make a backup!!!</a></li>
    </ul>
<?php } ?>

<br />
<h2>Redetect Nodes</h2>
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Image Optimization Box -->
    <div style="flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0;">Image Optimization</h3>
    
    <?php 
        // Get current node
        $current_image_node = '';
        if (class_exists('\LiteSpeed\Cloud')) {
            $summary = \LiteSpeed\Cloud::get_summary();
            $current_image_node = isset($summary['server.img_optm']) ? $summary['server.img_optm'] : 'Not set';
        }
        ?>
            <div style="margin-bottom: 10px; padding: 5px 10px; background-color: #d4edda; color: #155724; border-radius: 4px; font-weight: bold; width: 72.5%;">
                Current Node: <?php echo esc_html($current_image_node); ?>
            </div>
        
        <form method="post" action=""><?php wp_nonce_field('litespeed_debug_redetect_image', 'litespeed_debug_nonce'); ?>
            <input type="hidden" name="<?php echo esc_attr(LSCWP_DEBUG_PARAM_ACTION); ?>" value="redetect_image_node">
            <label for="image_node_select">Select Node:</label><br>
            <select id="image_node_select" name="image_node" style="width: 100%; margin-bottom: 10px;">
            <option value="node117">Node117</option>
            <option value="node119">Node119</option>
            <option value="node693">Node693</option>
            </select>
            <button type="submit" class="button button-primary" style="width: 75.5%;">Update Node</button>
        </form>
    </div>

    <!-- Page Optimization Box -->
    <div style="flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0;">Page Optimization</h3>

        <?php
        // Get current nodes for each service
        $current_summary = [];
        if (class_exists('\LiteSpeed\Cloud')) {
            $current_summary = \LiteSpeed\Cloud::get_summary();
        }
        ?>

        <div id="page_current_node" style="margin-bottom: 10px; padding: 5px 10px; background-color: #d4edda; color: #155724; border-radius: 4px; font-weight: bold; width: 72.5%;">
            Current Node: <?php
            $service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : 'UCSS';
            $service_key_map = [
                'UCSS' => 'server.ucss',
                'CCSS' => 'server.ccss',
                'VPI' => 'server.vpi',
                'LQIP' => 'server.lqip',
                'Page Load Time' => 'server.health',
                'PageSpeed Score' => 'server.health'
            ];
            $key = isset($service_key_map[$service]) ? $service_key_map[$service] : '';
            echo isset($current_summary[$key]) ? esc_html($current_summary[$key]) : 'Not set';
            ?></div>

            <form method="post" action="">
            <?php wp_nonce_field('litespeed_debug_redetect_page', 'litespeed_debug_nonce'); ?>
            <input type="hidden" name="<?php echo esc_attr(LSCWP_DEBUG_PARAM_ACTION); ?>" value="redetect_page_node">
            <label for="service_select">Select Service:</label><br>
            <select id="service_select" name="service" style="width: 100%; margin-bottom: 10px;">
            <option value="UCSS" <?php selected($service, 'UCSS'); ?>>UCSS</option>
            <option value="CCSS" <?php selected($service, 'CCSS'); ?>>CCSS</option>
            <option value="VPI" <?php selected($service, 'VPI'); ?>>VPI</option>
            <option value="LQIP" <?php selected($service, 'LQIP'); ?>>LQIP</option>
            <option value="Page Load Time" <?php selected($service, 'Page Load Time'); ?>>Page Load Time</option>
            <option value="PageSpeed Score" <?php selected($service, 'PageSpeed Score'); ?>>PageSpeed Score</option>
            </select>

            <br><label for="page_node_select">Select Node:</label><br>
            <select id="page_node_select" name="page_node" style="width: 100%; margin-bottom: 10px;"></select>

            <button type="submit" class="button button-primary" style="width: 75.5%;">Update Node</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    const serviceNodesMap = {
        "UCSS": ["node19","node123","eu-service-ctr2","saw35-hyb-worker"],
        "CCSS": ["node19","node123","eu-service-ctr2","saw35-hyb-worker"],
        "VPI": ["node19","node123","eu-service-ctr2","saw35-hyb-worker"],
        "LQIP": ["node13","node449","node3","node394"],
        "Page Load Time": ["node13","node449","node3","node394"],
        "PageSpeed Score": ["node13","node449","node3","node394"]
    };

    const serviceSelect = document.getElementById('service_select');
    const pageNodeSelect = document.getElementById('page_node_select');
    const pageCurrentNode = document.getElementById('page_current_node');

    const keyMap = {
        "UCSS":"server.ucss",
        "CCSS":"server.ccss",
        "VPI":"server.vpi",
        "LQIP":"server.lqip",
        "Page Load Time":"server.health",
        "PageSpeed Score":"server.health"
    };

    // This comes from PHP (LiteSpeed summary)
    let summary = <?php echo json_encode($current_summary); ?>;

    function updateNodeOptions(){
        const selectedService = serviceSelect.value;
        const nodes = serviceNodesMap[selectedService] || [];

        // Populate node dropdown
        pageNodeSelect.innerHTML = '';
        nodes.forEach(node => {
            const opt = document.createElement('option');
            opt.value = node;
            opt.textContent = node.replace(/^node/, 'Node'); // nice display
            pageNodeSelect.appendChild(opt);
        });

        // Update current node display
        let key = keyMap[selectedService] || '';
        let currentNode = summary[key] || 'Not set';
        pageCurrentNode.textContent = "Current Node: " + currentNode;
    }

    // Initialize on load
    serviceSelect.addEventListener('change', updateNodeOptions);
    updateNodeOptions();
</script>


<h2>Reset Page Optimization Services TTL</h2>
<hr>

<div style="margin-top: 30px; flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 30%;">
    <h3 style="margin-top: 0;">Reset TTL</h3>

    <?php
    $ttl_ucss = $ttl_ccss = $ttl_vpi = 'Not set';
    if (class_exists('\LiteSpeed\Cloud')) {
        $summary = \LiteSpeed\Cloud::get_summary();
        $ttl_ucss = isset($summary['ttl.ucss']) ? intval($summary['ttl.ucss']) : 'Not set';
        $ttl_ccss = isset($summary['ttl.ccss']) ? intval($summary['ttl.ccss']) : 'Not set';
        $ttl_vpi  = isset($summary['ttl.vpi'])  ? intval($summary['ttl.vpi']) : 'Not set';
    }
    ?>

    <ul style="margin-bottom: 15px;">
        <li><strong>UCSS TTL:</strong> <?php echo esc_html($ttl_ucss); ?></li>
        <li><strong>CCSS TTL:</strong> <?php echo esc_html($ttl_ccss); ?></li>
        <li><strong>VPI TTL:</strong> <?php echo esc_html($ttl_vpi); ?></li>
    </ul>

    <form method="post" action="">
        <?php wp_nonce_field('litespeed_debug_reset_ttl', 'litespeed_debug_nonce'); ?>
        <input type="hidden" name="<?php echo esc_attr(LSCWP_DEBUG_PARAM_ACTION); ?>" value="reset_ttl">
        <button type="submit" class="button button-primary" style="width: 100%;">Reset TTL</button>
    </form>
</div>
