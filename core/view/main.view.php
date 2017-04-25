<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap wpeo-project-wrap">

	<div class="wpeo-project-dashboard">
		<h2>
			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>

			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
					data-tag="sav"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "SAV"', 'task-manager' ); ?></a>

			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
					data-tag="ref"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "reférencement"', 'task-manager' ); ?></a>

			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
					data-tag="com"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'Nouvelle tâche "commercial"', 'task-manager' ); ?></a>
		</h2>

		<p class="alignright">Temps total passé: <?php echo esc_html( $total_time_elapsed ); ?></p>
	</div>

	<?php if ( ! empty( $tasks ) ) :
		foreach ( $tasks as $key => $data ) :
			?>
				<h2><?php echo esc_html( $data['title'] ); ?></h2>
				<div class="list-task">
					<?php \task_manager\Task_Class::g()->display_tasks( $data['data'] ); ?>
				</div>
			<?php
		endforeach;
	endif;
	?>
</div>
