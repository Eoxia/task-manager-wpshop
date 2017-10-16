<?php
/**
 * Classe gérant les actions principales de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 0.1.0
 * @version 1.1.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	 * @since 0.1.0
	 * @version 1.1.0
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : ''; // WPCS: CSRF ok.

		// if ( in_array( $page, \eoxia\Config_Util::$init['task-manager-wpshop']->insert_scripts_pages_js, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_assets' ), 11 );
		// }

		add_action( 'wp_enqueue_scripts', array( $this, 'callback_wp_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_dequeue_bootstrap' ), 99 );

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );

		add_action( 'wp_ajax_load_wpshop_task', array( $this, 'callback_load_wpshop_task' ) );
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 1.1.0
	 */
	public function callback_admin_enqueue_assets() {
		wp_enqueue_media();
		wp_enqueue_style( 'task-manage-wpshop-styles', TM_WPS_URL . 'core/assets/css/backend.min.css', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );
		wp_enqueue_script( 'task-manager-wpshop-script', TM_WPS_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version );
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_wp_enqueue_scripts() {
		wp_enqueue_script( 'task-manager-wpshop-frontend-script', TM_WPS_URL . 'core/assets/js/frontend.min.js', array(), \eoxia\Config_Util::$init['task-manager-wpshop']->version, false );
	}

	/**
	 * Dequeue la librairie bootstrap dans la page de support du compte client
	 *
	 * @return void nothing
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
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
			$parent_id = $post->ID;
			ob_start();
			require( TM_WPS_PATH . '/core/view/create-buttons.view.php' );
			$buttons = ob_get_clean();
			add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ) . $buttons, array( Task_Manager_Wpshop_Core::g(), 'callback_render_metabox' ), $post_type, 'normal', 'default' );
		}
	}

	/**
	 * Récupères les tâches des clients WPShop et renvoies la vue.
	 *
	 * @since 1.1.0
	 * @version 1.1.0
	 *
	 * @return void
	 */
	public function callback_load_wpshop_task() {
		check_ajax_referer( 'load_wpshop_task' );

		$customers_post_id = get_posts( array(
			'post_type' => 'wpshop_customers',
			'post_status' => 'any',
			'posts_per_page' => \eoxia\Config_util::$init['task-manager']->task->posts_per_page,
			'fields' => 'ids',
		) );

		if ( ! empty( $customers_post_id ) ) {
			$customers_post_id = implode( ',', $customers_post_id );
		}

		ob_start();
		echo do_shortcode( '[task post_parent="' . $customers_post_id . '" with_wrapper="0"]' );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManagerWPShop',
			'module' => 'core',
			'callback_success' => 'loadedWPShopTask',
		) );
	}

}

new Task_Manager_Wpshop_Core_Action();
