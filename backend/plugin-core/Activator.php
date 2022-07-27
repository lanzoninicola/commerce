<?php

namespace Commerce\Backend\PluginCore;

use Commerce\Backend\Modules\Setup\ClockdownSetup;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Commerce
 * @subpackage Commerce/includes
 *
 * @author     Lanzoni Nicola <lanzoni.nicola@gmail.com>
 *
 * @since      1.0.0
 */
class Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        $clockdown_setup = new ClockdownSetup();
        $clockdown_setup->install();

    }

}
