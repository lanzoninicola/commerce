<?php

namespace Commerce\Client\Config;

use Commerce\Core\PluginSetup;

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

        $plugin_setup = new PluginSetup();

        $tables = array(
            'user_marketing_preferences' => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                user_email VARCHAR(255) NULL,
                consent_newsletter TINYINT NULL,
                consent_privacy TINYINT NULL,
                consent_terms TINYINT NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            'products_installations'     => "CREATE TABLE `%table_name%` (
                id INT NOT NULL AUTO_INCREMENT,
                product_id bigint(20) unsigned NOT NULL,
                user_email VARCHAR(255) NULL,
                site_url VARCHAR(255) NULL,
                site_language VARCHAR(100) NULL,
                site_timezone VARCHAR(100) NULL,
                created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
                ) %charset_collate%;",
            // 'products'                   => "CREATE TABLE `%table_name%` (
            //     id INT NOT NULL AUTO_INCREMENT,
            //     name VARCHAR(255) NULL,
            //     description VARCHAR(255) NULL,
            //     created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
            //     updated_at datetime NULL,
            //     PRIMARY KEY  (id)
            //     ) %charset_collate%;",
            // 'product_license'             => "CREATE TABLE `%table_name%` (
            //     id INT NOT NULL AUTO_INCREMENT,
            //     wp_user_id bigint(20) unsigned NOT NULL,
            //     product_id bigint(20) unsigned NOT NULL,
            //     license_id BINARY(16) NOT NULL,
            //     seats INT NOT NULL,
            //     created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
            //     updated_at datetime NULL,
            //     PRIMARY KEY  (id)
            //     ) %charset_collate%;",
            // 'product_license_activations' => "CREATE TABLE `%table_name%` (
            //     id INT NOT NULL AUTO_INCREMENT,
            //     license_id BINARY(16) NOT NULL,
            //     site_url VARCHAR(255) NULL,
            //     created_at datetime NULL DEFAULT CURRENT_TIMESTAMP,
            //     updated_at datetime NULL,
            //     PRIMARY KEY  (id)
            //     ) %charset_collate%;",
        );

        $plugin_setup->define_db_schema( $tables );

        $plugin_setup->install();

    }

}
