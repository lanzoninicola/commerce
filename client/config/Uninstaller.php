<?php

namespace Commerce\Client\Config;

use Commerce\Core\PluginSetup;
use function Commerce\get_plugin_name;

class Uninstaller {

    public static function uninstall() {

        $plugin_setup = new PluginSetup(
            get_plugin_name(),
            COMMERCE_PLUGIN_DB_PREFIX,
            COMMERCE_PLUGIN_VERSION,
            COMMERCE_PLUGIN_ID
        );

        $plugin_setup->uninstall();

    }

}
