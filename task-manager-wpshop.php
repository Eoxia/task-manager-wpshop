<?php
/**
 * Fichier boot du plugin
 *
 * @package Eoxia\plugin
 */

namespace task_manager_wpshop;
/**
 * Plugin Name: Task Manager WPShop
 * Plugin URI:
 * Description: Handle client support with Task Manager and WPShop.
 * Version:     1.2.0
 * Author:      Eoxia
 * Author URI:  http://www.eoxia.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager-wpshop
 * Domain Path: /language
 */

DEFINE( 'TM_WPS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'TM_WPS_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'TM_WPS_DIR', basename( __DIR__ ) );

// On plugin load change order in order to load WPShop before current plugin.
add_action( 'plugins_loaded', function() {
	$plugins = get_option( 'active_plugins' );

	$wps_key = array_search( 'task-manager/task-manager.php', $plugins, true );
	$wps_seller_key = array_search( 'task-manager-wpshop/task-manager-wpshop.php', $plugins, true );

	if ( $wps_key > $wps_seller_key ) {
		unset( $plugins[ $wps_seller_key ] );
		$plugins[] = 'task-manager-wpshop/task-manager-wpshop.php';
		update_option( 'active_plugins', $plugins );
	}
} );

if ( class_exists( '\eoxia\Init_Util' ) ) {
	\eoxia\Init_Util::g()->exec( TM_WPS_PATH, basename( __FILE__, '.php' ) );
}
