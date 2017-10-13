<?php
/**
 * Define functions for admin bar support extension for WPShop
 *
 * @package Task Manager WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Classe de gestion des actions
 */
class Admin_Bar_Class extends \eoxia\Singleton_Util {

	/**
	 * Instanciation du module
	 */
	protected function construct() { }

	/**
	 * Construction de la requête permettant de récupèrer la liste des
	 *
	 * @param	string $where_clause The different fields to get from database.
	 *
	 * @return string The db query to launch
	 */
	public function get_new_ask_query( $where_clause ) {
		$query = "SELECT {$where_clause}
		FROM {$GLOBALS['wpdb']->comments} AS POINT
			JOIN {$GLOBALS['wpdb']->posts} AS TASK ON POINT.comment_post_id=TASK.id
			JOIN {$GLOBALS['wpdb']->postmeta} AS TASKMETA ON TASKMETA.post_id=TASK.id
			JOIN {$GLOBALS['wpdb']->commentmeta} AS POINTMETA ON POINT.comment_ID=POINTMETA.comment_id
			JOIN {$GLOBALS['wpdb']->users} AS USER ON POINT.user_id=USER.ID
			JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID=USERMETA.user_id
		WHERE TASK.post_type='wpeo-task'
			AND TASK.post_parent != 0
			AND	POINTMETA.meta_key='wpeo_point'
			AND POINTMETA.meta_value LIKE '%completed\":false%'
			AND USERMETA.meta_key='{$GLOBALS['wpdb']->prefix}user_level'
			AND USERMETA.meta_value=0
			AND TASKMETA.meta_key = 'wpeo_task'
		ORDER BY POINT.comment_date DESC";

		$query = "SELECT {$where_clause}
		FROM {$GLOBALS['wpdb']->posts} AS TASK
			JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_post_id = TASK.ID
			JOIN {$GLOBALS['wpdb']->commentmeta} AS POINTMETA ON POINTMETA.comment_id = POINT.comment_ID
			JOIN {$GLOBALS['wpdb']->posts} AS CUSTOMER ON CUSTOMER.ID = TASK.post_parent
			JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USERMETA.user_id = POINT.user_id
			LEFT JOIN {$GLOBALS['wpdb']->postmeta} AS TASKMETA ON ( TASKMETA.post_id = TASK.ID AND TASKMETA.meta_key = '_wp_old_slug' )

			LEFT JOIN {$GLOBALS['wpdb']->comments} AS COMMENT ON COMMENT.comment_parent = POINT.comment_ID
				LEFT JOIN {$GLOBALS['wpdb']->usermeta} AS USERCOMMENTMETA ON USERCOMMENTMETA.user_id = COMMENT.user_id AND USERCOMMENTMETA.meta_key = '{$GLOBALS['wpdb']->prefix}user_level'
		WHERE
			(
						TASK.post_name LIKE 'ask-task-%'
				OR 	TASKMETA.meta_value LIKE 'ask-task-%'
			)
			AND TASK.post_status != 'trash'
			AND POINT.comment_approved != 'trash'
			AND POINT.comment_parent = 0
			AND POINTMETA.meta_key = 'wpeo_point'
			AND POINTMETA.meta_value LIKE '%\"completed\":false%'
		GROUP BY point_id
		ORDER BY POINT.comment_date DESC, TASK.ID ASC";

		return $query;
	}

	/**
	 * Construction de la requête permettant de récupèrer la liste des
	 *
	 * @param	string $where_clause The different fields to get from database.
	 *
	 * @return string The db query to launch
	 */
	public function get_new_response_query( $where_clause ) {
		$query = "SELECT {$where_clause}
		FROM {$GLOBALS['wpdb']->comments} as TIME
			JOIN {$GLOBALS['wpdb']->comments} AS POINT ON TIME.comment_parent=POINT.comment_ID
			JOIN {$GLOBALS['wpdb']->posts} AS TASK 		ON POINT.comment_post_id=TASK.id
			JOIN {$GLOBALS['wpdb']->commentmeta} AS POINTMETA ON POINT.comment_ID=POINTMETA.comment_id
			JOIN {$GLOBALS['wpdb']->users} AS USER ON TIME.user_id=USER.ID
			JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID=USERMETA.user_id
		WHERE TASK.post_type='wpeo-task'
			AND	POINTMETA.meta_key='wpeo_point'
			AND POINTMETA.meta_value LIKE '%completed\":false%'
			AND TIME.comment_content != ''
			AND TIME.comment_approved != 'trash'
			AND USERMETA.meta_key='wp_user_level'
			AND USERMETA.meta_value=0
		ORDER BY TIME.comment_date DESC";

		return $query;
	}

}

new Admin_Bar_Class();
