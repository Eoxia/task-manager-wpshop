<?php
/**
 * Le bouton pour créer une tâche rapide.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="open-popup-ajax"
			data-parent="js"
			data-target="popup"
			data-action="load_popup_quick_task"
			data-class="popup-quick-task"
			data-title="<?php esc_html_e( 'Quick task', 'task-manager-wpshop' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_popup_quick_task' ) ); ?>"><?php esc_html_e( 'Quick task', 'task-manager-wpshop' ); ?></span>
