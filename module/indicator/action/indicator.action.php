<?php
/**
 * Les actions relatives aux indications.
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
}

/**
 * Les actions relatives aux indications.
 */
class Indicator_Action {

	/**
	 * Initialise les actions liées au indications.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_meta_box( 'tm-indicator-support', __( 'Customer support', 'task-manager' ), array( Indicator_Class::g(), 'callback_customer_support' ), 'task-manager-indicator-support', 'normal' );

		add_action( 'tm_customer_add_entry_customer_ask', array( $this, 'callback_tm_add_entry_customer_ask' ) );
		add_action( 'tm_customer_remove_entry_customer_ask', array( $this, 'callback_tm_remove_entry_customer_ask' ) );
	}

	/**
	 * Ajoutes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $id   L'ID du point ou du commentaire.
	 *
	 * @return void
	 */
	public function callback_tm_add_entry_customer_ask( $id ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! in_array( $id, $ids, true ) ) {
			$ids[] = (int) $id;
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
	}

	/**
	 * Supprimes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $id   L'ID du point ou du commentaire.
	 *
	 * @return void
	 */
	public function callback_tm_remove_entry_customer_ask( $id ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$key = array_search( $id, $ids, true );
		if ( $key ) {
			array_splice( $ids, $key, 1 );
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
	}
}

new Indicator_Action();
