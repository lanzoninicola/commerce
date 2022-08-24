<?php

namespace Commerce;

use Commerce\Client\Config\Configurator;
use Commerce\Core\Init;

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

function get_plugin_id() {
    return '999';
}

function get_plugin_name() {
    return 'commerce';
}

function get_plugin_version() {
    return '1.0.1';
}

function get_plugin_db_prefix() {
    return 'ckdo';
}

function get_plugin_base_url_path() {
    return plugin_dir_url( __FILE__ );
}

function get_plugin_text_domain() {
    return 'commerce';
}

function get_plugin_api_base_url() {
    return home_url( '/wp-json/commerce/v1' );
}

function get_commerce_api_base_url() {
    return 'http://localhost/bb-melhor-envio/wp-json/commerce/v1';
}

register_activation_hook( __FILE__, array( 'Commerce\Client\Config\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Commerce\Client\Config\Deactivator', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'Commerce\Client\Config\Uninstaller', 'uninstall' ) );

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

    $config = new Configurator();
    $init   = new Init( $config );
    $init->run();

}

run_plugin();
