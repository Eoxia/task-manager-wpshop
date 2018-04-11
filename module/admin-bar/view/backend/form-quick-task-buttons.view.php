<?php
/**
 * Le formulaire pour créer une tâche rapide.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="action-input wpeo-button button-primary"
			data-loader="modal-container"
			data-parent="modal-container"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_quick_task' ) ); ?>"><?php esc_html_e( 'Create quick task', 'task-manager-wpshop' ); ?></span>
