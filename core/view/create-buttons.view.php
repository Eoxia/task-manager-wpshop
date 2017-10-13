<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.1.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h2>
	<a 	href="#"
		class="action-attribute page-title-action"
		data-action="create_task"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager-wpshop' ); ?></a>

	<a 	href="#"
		class="action-attribute page-title-action"
		data-action="create_task"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
		data-tag="sav"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "SAV"', 'task-manager-wpshop' ); ?></a>

	<a 	href="#"
		class="action-attribute page-title-action"
		data-action="create_task"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
		data-tag="ref"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "reférencement"', 'task-manager-wpshop' ); ?></a>

	<a 	href="#"
		class="action-attribute page-title-action"
		data-action="create_task"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
		data-tag="com"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "commercial"', 'task-manager-wpshop' ); ?></a>
</h2>
