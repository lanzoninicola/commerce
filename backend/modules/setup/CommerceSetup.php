<?php

namespace Commerce\Backend\Modules\Setup;

use Commerce\Backend\App\Services\Setup\PluginSetup;
use Commerce\Backend\App\Services\Setup\PluginSetupInterface;

class CommerceSetup implements PluginSetupInterface {

    /**
     * Object responsible for the plugin setup.
     *
     * @var PluginSetup
     */
    private $plugin_setup;

    public function __construct() {

        $this->plugin_setup = new PluginSetup(
            COMMERCE_PLUGIN_NAME,
            COMMERCE_PLUGIN_DB_PREFIX,
            COMMERCE_PLUGIN_VERSION
        );

        $this->define_db_schema();

    }

    public function define_db_schema(): void {

        $tables = array(
            'user_marketing' => array(
                'id'                 => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
                'wp_user_id'         => 'bigint(20) unsigned NOT NULL',
                'consent_newsletter' => 'TINYINT NULL',
                'consent_privacy'    => 'TINYINT NULL',
                'consent_terms'      => 'TINYINT NULL',
                'created_at'         => 'datetime NOT NULL',
                'updated_at'         => 'datetime NOT NULL',
                'PRIMARY KEY (id)',
            ),
            'user_regioned'  => array(
                'id'            => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
                'wp_user_id'    => 'bigint(20) unsigned NOT NULL',
                'site_language' => 'VARCHAR(100) NULL',
                'timezone'      => 'VARCHAR(100) NULL',
                'created_at'    => 'datetime NOT NULL',
                'updated_at'    => 'datetime NOT NULL',
                'PRIMARY KEY (id)',
            ),
        );

        $this->plugin_setup->define_db_schema( $tables );

    }

    /**
     * Execute the plugin setup.
     *
     * @return void
     */
    public function install(): void {

        $this->plugin_setup->install();
    }

    /**
     * Uninstall the plugin.
     *
     * @return void
     */
    public function uninstall(): void {

        $this->plugin_setup->uninstall();
    }

}
