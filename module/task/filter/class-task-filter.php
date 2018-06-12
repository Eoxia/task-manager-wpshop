<?php
/**
 * Les filtres relatives au dasboard de task-manager quand WPShop est activé..
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux indications.
 */
class Task_Filter {

	/**
	 * Initialise les filtres liées au indications.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_filter( 'tm_task_header_summary', array( $this, 'callback_tm_task_header_summary' ), 10, 2 );
	}

	/**
	 * Fonction de callback permettant d'ajouter des infos sur les affectations des tâches.
	 *
	 * @param string     $current_content Le contenu actuel correspondant au filtre.
	 * @param Task_Model $task            La définition de la tâche sur laquelle on veut afficher des données complémentaires.
	 *
	 * @return string          Le "nouveau" contenu qui a été modifié par le filtre.
	 */
	public function callback_tm_task_header_summary( $current_content, $task ) {
		if ( ! empty( $task ) && ! empty( $task->data ) && ! empty( $task->data['parent_id'] ) ) {
			$task_parent_type = get_post_type( $task->data['parent_id'] );
			if ( WPSHOP_NEWTYPE_IDENTIFIER_CUSTOMERS === $task_parent_type || WPSHOP_NEWTYPE_IDENTIFIER_ORDER === $task_parent_type ) {
				$task_parent  = get_post( $task->data['parent_id'] );
				$parent_icon  = 'dashicons-admin-links';
				$parent_title = '';
				$customer     = null;

				switch ( $task_parent_type ) {
					case WPSHOP_NEWTYPE_IDENTIFIER_ORDER:
						$parent_icon = 'dashicons-cart';
						// Si le parent est une commande on va récupérer les informations concernant le client associé à la commande.
						$customer = get_post( $task_parent->post_parent );
						// Translators: The name of customer who placed the order.
						$parent_title = sprintf( __( 'Order placed by %s', 'task-manager' ), $customer->post_title );
						break;

					case WPSHOP_NEWTYPE_IDENTIFIER_CUSTOMERS:
						$parent_icon  = 'dashicons-admin-users';
						$parent_title = '';
						break;
				}

				ob_start();
				\eoxia\View_Util::exec( 'task-manager-wpshop', 'task', 'backend/parent', array(
					'parent'       => $task_parent,
					'parent_icon'  => $parent_icon,
					'parent_title' => $parent_title,
				) );
				$current_content .= ob_get_clean();
			}
		}

		return $current_content;
	}

}

new Task_Filter();
