<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       Commerce
 * Plugin URI:        https://lanzoninicola.com.br
 * Description:       The countdown timer heats the sense of urgency and scarcity, forcing the users as quickly as possible to make a purchase decision. Set the end date, customize it, put the widget in your e-commerce pages and your timer is ready.
 * Version:           1.0.1
 * Author:            Lanzoni Nicola
 * Author URI:        https://lanzoninicola.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       commerce
 * Domain Path:       /languages
 * @package           Commerce
 *
 * @link              https://lanzoninicola.com.br
 * @since             1.0.0
 */

// If this file is called directly, abort.

if ( !defined( 'WPINC' ) ) {
    return;
}

/**
 * Autoload files
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use Commerce\Backend\PluginCore\Activator;
use Commerce\Backend\PluginCore\Core;
use Commerce\Backend\PluginCore\Deactivator;
use Commerce\Backend\PluginCore\Uninstaller;

define( 'COMMERCE_PLUGIN_NAME', 'commerce' );
define( 'COMMERCE_PLUGIN_VERSION', '1.0.1' );
define( 'COMMERCE_PLUGIN_DB_PREFIX', 'comm' );
define( 'COMMERCE_PLUGIN_BASE_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'COMMERCE_TEXT_DOMAIN', 'commerce' );

/**
 * The code that runs during plugin activation.
 * This action is documented in plugin-core/Activator.php
 */
function activate() {
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in plugin-core/Deactivator.php
 */
function deactivate() {
    Deactivator::deactivate();
}

/**
 * The code that runs when the plugin is uninstalled.
 * This action is documented in plugin-core/Uninstaller.php
 */
function uninstall() {
    Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate' );
register_deactivation_hook( __FILE__, 'deactivate' );
register_uninstall_hook( __FILE__, 'uninstall' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin() {

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */

    $plugin = new Core();
    $plugin->run();

}

run_plugin();
