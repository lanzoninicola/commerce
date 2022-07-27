<?php

namespace Clockdown\Backend\PluginCore;

use Clockdown\Backend\Modules\Setup\ClockdownSetup;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Clockdown
 * @subpackage Clockdown/includes
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
