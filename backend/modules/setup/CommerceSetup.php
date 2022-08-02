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
            COMMERCE_PLUGIN_VERSION,
            COMMERCE_PLUGIN_ID
        );

        $this->define_db_schema();

    }

    public function define_db_schema(): void {

        $tables = array(
            'user_marketing_preferences'  => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                wp_user_id bigint(20) unsigned NOT NULL,
                consent_newsletter TINYINT NULL,
                consent_privacy TINYINT NULL,
                consent_terms TINYINT NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            'products'                    => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NULL,
                description VARCHAR(255) NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            'products_installations'      => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                product_id bigint(20) unsigned NOT NULL,
                installation_id VARCHAR(255) NOT NULL,
                site_url VARCHAR(255) NULL,
                site_language VARCHAR(100) NULL,
                site_timezone VARCHAR(100) NULL,
                wp_user_id bigint(20) unsigned NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            'product_license'             => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                license_id BINARY(16) NOT NULL,
                wp_user_id bigint(20) unsigned NOT NULL,
                product_id bigint(20) unsigned NOT NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            'product_license_activations' => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                license_id BINARY(16) NOT NULL,
                site_url VARCHAR(255) NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
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
