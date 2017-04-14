<?php
/**
 * La classe principale de l'application.
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Task_Manager_Wpshop_Core extends Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}
}

new Task_Manager_Wpshop_Core();
