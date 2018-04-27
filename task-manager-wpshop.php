<?php
/**
 * Plugin Name: Task Manager WPShop
 * Plugin URI:
 * Description: Handle client support with Task Manager and WPShop.
 * Version:     1.2.0-alpha
 * Author:      Eoxia
 * Author URI:  http://www.eoxia.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager-wpshop
 * Domain Path: /language
 *
 * @package Eoxia\plugin
 */

namespace task_manager_wpshop;

DEFINE( 'TM_WPS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'TM_WPS_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'TM_WPS_DIR', basename( __DIR__ ) );

// On plugin load change order in order to load WPShop before current plugin.
add_action( 'plugins_loaded', function() {
	$plugins = get_option( 'active_plugins' );
	$sub_plugin_path = 'task-manager-wpshop/' . basename( __FILE__ );
	foreach ( $plugins as $key => $value ) {
		if ( strpos( $value, 'task-manager.php' ) ) {
			$main_plugin_key = $key;
		}
		if ( strpos( $value, 'wpshop.php' ) ) {
			$wps_plugin_key = $key;
		}
		if ( strpos( $value, basename( __FILE__ ) ) ) {
			$sub_plugin_key = $key;
			$sub_plugin_path = $value;
		}
	}

	if ( isset( $main_plugin_key ) ) {
		if ( $main_plugin_key > $sub_plugin_key || $wps_plugin_key > $sub_plugin_key ) {
			array_splice( $plugins, $sub_plugin_key, 1 );
			$plugins[] = $sub_plugin_path;
			update_option( 'active_plugins', $plugins );
		}
	}
} );

if ( class_exists( '\eoxia\Init_Util' ) && class_exists( 'wpshop_products' ) ) {
	\eoxia\Init_Util::g()->exec( TM_WPS_PATH, basename( __FILE__, '.php' ) );

	// Ajout des entrées spécifiques à WPShop pour la gestion des tâches.
	$include_page = array(
		WPSHOP_NEWTYPE_IDENTIFIER_CUSTOMERS,
		WPSHOP_NEWTYPE_IDENTIFIER_ORDER,
	);
	// Type d'éléments ou afficher les tâches.
	\eoxia\Config_Util::$init['task-manager']->associate_post_type = array_merge( \eoxia\Config_Util::$init['task-manager']->associate_post_type, $include_page );
	// Page ou intégrer les scripts et css.
	$query = $GLOBALS['wpdb']->prepare( "SELECT post_name FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
	$include_page[] = $GLOBALS['wpdb']->get_var( $query );
	\eoxia\Config_Util::$init['task-manager']->insert_scripts_pages = array_merge( \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages, $include_page );
}
