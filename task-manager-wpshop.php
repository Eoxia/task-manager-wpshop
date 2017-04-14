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
 * Description:
 * Version:     1.0.0.0
 * Author:      Eoxia
 * Author URI:  http://www.eoxia.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /core/assets/languages
 * Text Domain: task-manager-wpshop
 */

DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_WPSHOP_DIR', basename( __DIR__ ) );

require_once 'core/util/singleton.util.php';
require_once 'core/util/init.util.php';
require_once 'core/helper/model.helper.php';
require_once 'core/external/wpeo_log/class/log.class.php';

Init_util::g()->exec();
