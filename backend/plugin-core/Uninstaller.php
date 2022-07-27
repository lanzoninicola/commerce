<?php

namespace Commerce\Backend\PluginCore;

use Commerce\Backend\Modules\Setup\CommerceSetup;

class Uninstaller {

    public static function uninstall() {

        $clockdown_setup = new CommerceSetup();
        $clockdown_setup->uninstall();

    }

}
