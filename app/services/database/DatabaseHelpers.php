<?php

namespace Commerce\App\Services\Database;

class DatabaseHelpers {

    /**
     * Get prefix for the plugin.
     *
     * @return string
     */
    public static function get_plugin_db_prefix( string $plugin_name ): string {
        $option_prefix = strtolower( $plugin_name );

        // get_option() returns string if found, otherwise returns false.
        $prefix = get_option( "{$option_prefix}_db_tables_prefix" );

        if ( $prefix === false ) {
            throw new \Exception( "The plugin prefix is not defined." );
        }

        return $prefix;
    }

    /**
     * Get the charset collate of table.
     *
     * @return string
     */
    public static function get_charset_collate() {
        global $wpdb;
        return $wpdb->get_charset_collate();
    }

    /**
     * Get the prefix used by Wordpress.
     * It returns the prefix used **with the underscore**.
     *
     * @return string
     */
    public static function get_wp_db_prefix(): string {
        global $wpdb;
        return $wpdb->prefix;
    }

    /**
     * Get the full name of the table with prefixes.
     *
     * @param string $plugin_prefix The prefix choosed for the tables related to the plugin
     * @param string $name The name of the table (choose a name without prefixes)
     * @return string
     */
    public static function get_table_fullname( string $plugin_prefix, string $name ): string {
        $wp_prefix = self::get_wp_db_prefix();

        return "{$wp_prefix}{$plugin_prefix}_{$name}";
    }

    /**
     * Check if the table exists in the database.
     *
     * @param string $table_name The fullname with all prefixes of the table
     * @return bool
     */
    public static function table_exists( string $table_name ): bool {
        global $wpdb;

        $result = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );

        if ( $result === null ) {
            return false;
        }

        return true;
    }

}
