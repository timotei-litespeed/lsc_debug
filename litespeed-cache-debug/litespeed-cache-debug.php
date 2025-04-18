<?php

/**
 * Plugin Name:       LiteSpeed Cache Debug
 * Plugin URI:        https://www.litespeedtech.com/products/cache-plugins/wordpress-acceleration
 * Description:       Debug plugin for LiteSpeed
 * Version:           1.0.0
 * Author:            LiteSpeed Technologies
 * Author URI:        https://www.litespeedtech.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl.html
 * Text Domain:       litespeed-cache
 * Domain Path:       /lang
 *
 * Copyright (C) 2015-2025 LiteSpeed Technologies, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */
defined('WPINC') || exit();

add_action('init', 'lsc_debug_start', 99);

function lsc_debug_start(){
	!defined('LSCWP_DEBUG_DIR') && define('LSCWP_DEBUG_DIR', __DIR__ . '/');

	require_once LSCWP_DEBUG_DIR.'src/const.php';
	require_once LSCWP_DEBUG_DIR.'src/utils.php';
	require_once LSCWP_DEBUG_DIR.'src/actions.php';
	
	// Add admin menu and show content
	add_action('admin_menu', 'lsc_debug_admin_menu', 9999);
}

// Link logic
add_action('init', 'lsc_debug_link_parse', 100);

// Deactivation procedure
register_deactivation_hook(__FILE__, function() {
    lsc_debug_credentials_remove();
});