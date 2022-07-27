<?php

namespace Clockdown\Backend\PluginCore;

use Clockdown\Backend\Modules\Setup\ClockdownSetup;

class Uninstaller {

    public static function uninstall() {

        $clockdown_setup = new ClockdownSetup();
        $clockdown_setup->uninstall();

    }

}
