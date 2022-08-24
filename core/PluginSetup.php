<?php

namespace Commerce\Core;

use Commerce\App\Services\Analytics\ProductActivationTracking;
use Commerce\App\Services\Database\DatabaseHelpers;
use function Commerce\get_plugin_db_prefix;
use function Commerce\get_plugin_name;

/**
 * Class responsible for the plugin setup.
 *
 * @method define_db_prefix()
 * @method define_schema()
 */
class PluginSetup {

    /**
     * Array of SQL CREATE script that will be used
     * for create/update database via dbDelta() function.
     *
     * @var array
     */
    private $schema = array();

    /**
     * Array of the plugin tables with all prefixes.
     *
     * @var array
     */
    private $tables = array();

    /**
     * The options to be saved in the database.
     * Associative array with the name of the option as key and the value as value.
     *
     * @var array
     */
    private $options = array();

    /**
     * Instanciate the object responsible for the setup.
     *
     */
    public function __construct() {

        $this->define_plugin_options();

        $this->define_installation_id();

    }

    /**
     * Define the plugin options.
     * Save in the local array the options to be saved in the database.
     *
     * @return void
     */
    private function define_plugin_options() {
        $this->options = array(
            'db_tables_prefix'       => get_plugin_db_prefix(),
            'is_activation_tracked'  => false,
            'is_onboarding_required' => false,
        );
    }

    /**
     * Define the installation id.
     * Save in the local array the installation id option to be saved in the database.
     *
     * @return void
     */
    private function define_installation_id() {
        $installation_id_option = (bool) get_option( get_plugin_name() . "_installation_id" );

        if ( $installation_id_option === false ) {
            $this->options['installation_id'] = DatabaseHelpers::generate_uuid();
        }

    }

    /**
     * Set the array of SQL CREATE script that will be used
     * for create/update database via dbDelta() function.
     *
     * PRIMARY KEY must be followed by TWO SPACES then
     * the open parenthesis then the field name and a closing parenthesis.
     *
     * KEY must be followed by a SINGLE SPACE then the key name then a space
     * then open parenthesis with the field name then a closed parenthesis.
     *
     * Note for the CREATE TABLE statement:
     * - The table name must be enclosed in backticks.
     * - The table name must be a placeholder like this %table_name% (it is replaced by the key of array).
     * - The charset collation a placeholder like this %charset_collate% (it is replaced by the charset collation).
     *
     * @param array $tables Associative array( table_name => SQL CREATE script ).
     * @return void
     */
    public function define_db_schema( array $tables ): void {
        $charset_collate = DatabaseHelpers::get_charset_collate();
        $wp_prefix       = DatabaseHelpers::get_wp_db_prefix();

        if ( empty( $tables ) ) {
            return;
        }

        foreach ( $tables as $name => $sql ) {

            $table_name = "{$wp_prefix}" . get_plugin_db_prefix() . "_{$name}";

            $sql            = str_replace( '%table_name%', $table_name, $sql );
            $sql            = str_replace( '%charset_collate%', $charset_collate, $sql );
            $this->schema[] = $sql;
            $this->tables[] = $table_name;
        }

    }

    /**
     * Add the options to the database.
     *
     * @return void
     */
    public function add_options(): void {

        foreach ( $this->options as $option_name => $option_value ) {
            add_option( get_plugin_name() . "_{$option_name}", $option_value );
        }

    }

    /**
     * Remove the options from the database.
     *
     * @return void
     */
    public function remove_options(): void {

        foreach ( $this->options as $option_name => $_ ) {
            delete_option( get_plugin_name() . "_{$option_name}" );
        }

    }

    /**
     * Create/update database tables via dbDelta() function.
     *
     * @return array
     */
    private function sync_db(): array{

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        return dbDelta( $this->schema );
    }

    /**
     * Remove database tables.
     *
     * @return void
     */
    private function remove_db_tables() {

        global $wpdb;

        foreach ( $this->tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
        }

    }

    /**
     * Add the tables to the database.
     *
     * @return array The result of the dbDelta() function.
     */
    public function install(): array{

        $this->add_options();

        ProductActivationTracking::send_activation_data();

        $result = $this->sync_db();

        return $result;

    }

    public function uninstall() {
        $this->remove_options();
        $this->remove_db_tables();
    }

}
