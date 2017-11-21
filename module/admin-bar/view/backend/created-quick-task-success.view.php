<?php
/**
 * Lorsque la création d'une nouvelle tâche rapide à été prise en compte.
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

<h2><?php echo esc_html_e( 'Your quick task has been created with success', 'task-manager-wpshop' ); ?></h2>

<span class="action-attribute wpeo-button button-primary"
			data-action="load_popup_quick_task"
			data-class="popup-quick-task"
			data-title="<?php esc_html_e( 'Quick task', 'task-manager-wpshop' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_popup_quick_task' ) ); ?>"><?php esc_html_e( 'Add another quick task', 'task-manager-wpshop' ); ?></span>

<span class="wpeo-button button-secondary close"><?php esc_html_e( 'Close', 'task-manager-wpshop' ); ?></span>
