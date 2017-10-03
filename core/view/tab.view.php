<?php
/**
 * L'onglet supplémentaire pour Task Manager.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.1.0
 * @version 1.1.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="action-attribute" data-action="load_wpshop_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_wpshop_task' ) ); ?>"><?php esc_html_e( 'Customer task', 'task-manager-wpshop' ); ?></li>
