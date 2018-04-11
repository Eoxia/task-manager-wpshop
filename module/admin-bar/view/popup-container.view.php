<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.1.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><div class="wpeo-project-wrap" >
	<div class="popup customer-frontend-request" >
		<div class="container">
			<div class="header">
				<h2 class="title"><?php echo esc_html( $popup_title ); ?></h2>
				<i class="close fa fa-times"></i>
			</div>
			<input type="hidden" class="offset-event" value="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ); ?>" />
			<input type="hidden" class="last-date" value="" />

			<div class="content">
			</div>
		</div>
	</div>
</div>
