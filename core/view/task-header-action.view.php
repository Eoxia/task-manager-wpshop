<?php
/**
 * Les propriétés d'une tâche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.4.0-ford
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li class="action-attribute tooltip hover"
		aria-label="<?php esc_html_e( 'Notify customer', 'task-manager' ); ?>"
		data-action="notify_customer"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'notify_customer' ) ); ?>"
		data-id="<?php echo esc_attr( $task_id ); ?>"
		data-loader="actions">
	<span><i class="fa fa-bell-o" aria-hidden="true"></i></span>
</li>
