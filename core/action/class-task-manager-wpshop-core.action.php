<?php
/**
 * Initialise les actions princiaples de Digirisk EPI
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Task_Manager_Wpshop_Core_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : ''; // WPCS: CSRF ok.

		if ( in_array( $page, Config_Util::$init['task-manager-wpshop']->insert_scripts_pages_css, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_css' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_css' ), 11 );
		}

		if ( in_array( $page, Config_Util::$init['task-manager-wpshop']->insert_scripts_pages_js, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_js' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_js' ), 11 );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts_js' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'callback_wp_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_dequeue_bootstrap' ), 99 );

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );
	}

	/**
	 * Initialise les fichiers CSS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_before_admin_enqueue_scripts_css() {}

	/**
	 * Initialise le fichier style.min.css du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_admin_enqueue_scripts_css() {}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_before_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_admin_enqueue_scripts_js() {}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_wp_enqueue_scripts() {
		wp_enqueue_script( 'task-manager-wpshop-frontend-script', PLUGIN_TASK_MANAGER_WPSHOP_URL . 'core/assets/js/frontend.min.js', array(), Config_Util::$init['task-manager-wpshop']->version, false );

	}

	public function callback_dequeue_bootstrap() {
		if ( $_GET['account_dashboard_part'] == 'support' ) {
			wp_dequeue_style( 'bootstrap-min' );
		}
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_admin_print_scripts_js() {}

	/**
	 * Initialise le fichier MO du plugin
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_plugins_loaded() {}

	/**
	 * Fait le contenu de la metabox
	 *
	 * @param string  $post_type Le type du post.
	 * @param WP_Post $post      Les données du post.
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_add_meta_boxes( $post_type, $post ) {
		if ( 'wpshop_customers' === $post_type || 'wpshop_shop_order' === $post_type ) {
			add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ), array( Task_Manager_Wpshop_Core::g(), 'callback_render_metabox' ), $post_type, 'normal', 'default' );
		}
	}
}

new Task_Manager_Wpshop_Core_Action();
