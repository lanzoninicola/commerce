<?php

namespace Commerce\Core;

use function Commerce\get_plugin_text_domain;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Commerce
 * @subpackage PluginCore\I18n
 *
 * @author     Lanzoni Nicola <lanzoni.nicola@gmail.com>
 *
 * @since      1.0.0
 */
class I18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            get_plugin_text_domain(),
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}
