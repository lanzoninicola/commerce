<?php

namespace Commerce;

use Commerce\Config\Configurator;
use Commerce\Core\ServiceProvider;

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

define( 'COMMERCE_PLUGIN_ID', '1' );
define( 'COMMERCE_PLUGIN_NAME', 'commerce' );
define( 'COMMERCE_PLUGIN_VERSION', '1.0.1' );
define( 'COMMERCE_PLUGIN_DB_PREFIX', 'comm' );
define( 'COMMERCE_PLUGIN_BASE_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'COMMERCE_TEXT_DOMAIN', 'commerce' );

register_activation_hook( __FILE__, array( 'Commerce\Client\Setup\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Commerce\Client\Setup\Deactivator', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'Commerce\Client\Setup\Uninstaller', 'uninstall' ) );

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

    $service_provider = new ServiceProvider();

    new Configurator( $service_provider );
    $service_provider->run();

}

run_plugin();
