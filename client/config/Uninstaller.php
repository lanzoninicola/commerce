<?php

namespace Commerce\Client\Config;

use Commerce\Core\PluginSetup;

class Uninstaller {

    public static function uninstall() {

        $plugin_setup = new PluginSetup(
            COMMERCE_PLUGIN_NAME,
            COMMERCE_PLUGIN_DB_PREFIX,
            COMMERCE_PLUGIN_VERSION,
            COMMERCE_PLUGIN_ID
        );

        $plugin_setup->uninstall();

    }

}
