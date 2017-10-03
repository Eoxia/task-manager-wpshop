<?php
/**
 * Fichier boot du plugin
 *
 * @package Eoxia\plugin
 */

namespace task_manager_wpshop;
/**
 * Plugin Name: Task Manager WPShop
 * Plugin URI:
 * Description: Handle client support with Task Manager and WPShop.
 * Version:     1.1.0
 * Author:      Eoxia
 * Author URI:  http://www.eoxia.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager-wpshop
 * Domain Path: /language
 */

DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_DIR', basename( __DIR__ ) );

require_once 'core/external/wpeo_util/singleton.util.php';
require_once 'core/external/wpeo_util/init.util.php';
require_once 'core/external/wpeo_log/class/log.class.php';

\eoxia\Init_Util::g()->exec( PLUGIN_TASK_MANAGER_WPSHOP_PATH, basename( __FILE__, '.php' ) );
