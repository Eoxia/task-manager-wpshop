<?php
/**
 * Classe gérant les actions principales de l'application.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.3.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Task_Manager_WPShop_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 *
	 * @since 0.1.0
	 * @version 1.1.0
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_assets' ), 11 );

		add_action( 'wp_enqueue_scripts', array( $this, 'callback_wp_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_dequeue_bootstrap' ), 99 );

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );

		add_action( 'wp_ajax_load_wpshop_task', array( $this, 'callback_load_wpshop_task' ) );
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 1.2.0
	 */
	public function callback_admin_enqueue_assets() {
		wp_enqueue_script( 'task-manager-global-wpshop-script', TM_WPS_URL . 'core/assets/js/global.min.js', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );

		$screen = get_current_screen();
		if ( ! empty( \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages ) ) {
			foreach ( \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages as $insert_script_page ) {
				if ( false !== strpos( $screen->id, $insert_script_page ) ) {
					wp_enqueue_style( 'task-manage-wpshop-styles', TM_WPS_URL . 'core/assets/css/backend.min.css', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );
					wp_enqueue_script( 'task-manager-wpshop-script', TM_WPS_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );
				}
			}
		}
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function callback_wp_enqueue_scripts() {
		wp_enqueue_style( 'task-manage-wpshop-front-styles', TM_WPS_URL . 'core/assets/css/frontend.css', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );
		wp_enqueue_script( 'task-manager-wpshop-frontend-script', TM_WPS_URL . 'core/assets/js/frontend.min.js', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version, false );
	}

	/**
	 * Dequeue la librairie bootstrap dans la page de support du compte client
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function callback_dequeue_bootstrap() {
		if ( ! empty( $_GET['account_dashboard_part'] ) && 'support' === $_GET['account_dashboard_part'] ) {
			wp_dequeue_style( 'bootstrap-min' );
		}
	}

	/**
	 * Initialise le fichier MO du plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 */
	public function callback_plugins_loaded() {
		$i18n_loaded = load_plugin_textdomain( 'task-manager-wpshop', false, TM_WPS_DIR . '/core/assets/language/' );
	}

}

new Task_Manager_WPShop_Action();
