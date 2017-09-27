<?php
/**
 * Le menu dans la page "mon-compte" de WPShop.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package support
 * @subpackage view
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li class="<?php echo ! empty( $_GET['account_dashboard_part'] ) && $_GET['account_dashboard_part'] === 'support' ? 'wps-activ' : ''; ?>">
	<a data-target="menu1" href='?account_dashboard_part=support'>
		<i class="dashicons dashicons-layout"></i>
		<span><?php esc_html_e( 'Support', 'task-manager' ); ?></span>
	</a>
</li>
