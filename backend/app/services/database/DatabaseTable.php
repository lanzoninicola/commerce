<?php

namespace Commerce\Backend\App\Services\Database;

class DatabaseTable {

    /**
     * The full table name with Wordpress and Plugin prefixes .
     *
     * @var string
     */
    protected $table_fullname;

    /**
     * Initialize the table name.
     *
     * @param string $plugin_prefix The prefix choosed for the tables related to the plugin
     * @param string $name The name of the table (choose a name without prefixes)
     */
    public function __construct( string $plugin_prefix, string $name ) {

        global $wpdb;

        $this->table_fullname = "{$wpdb->prefix}{$plugin_prefix}_{$name}";
    }

    /**
     * Returns the full name of the table with prefixes.
     *
     * @return string
     */
    public function get_fullname(): string {
        return $this->table_fullname;
    }

    /**
     * Get the charset collate of table.
     *
     * @return string
     */
    public function get_charset_collate(): string {
        global $wpdb;
        return $wpdb->get_charset_collate();
    }

}