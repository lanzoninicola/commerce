<?php

namespace Commerce\Backend\App\Services\Database;

/**
 * Class responsible for querying the table passed in the constructor.
 *
 */
class DatabaseQuery implements DatabaseQueryInterface {

    /**
     * Instance of the class.
     *
     * @var DatabaseQuery|null
     */
    public static $instance = null;

    // singletone call
    public static function singletone() {

        if ( self::$instance === null ) {
            self::$instance = new DatabaseQuery();
        }

        return self::$instance;
    }

    /**
     * Insert a row.
     *
     * Use the identity operator (===) to check for errors: (e.g., false === $result),
     * and whether any rows were affected (e.g., 0 === $result).
     *
     * @return DatabaseResponse
     */
    public function insert_row( string $table_name, array $data, $format = null ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $result = $wpdb->insert(
            $table_name,
            $data,
            $format
        );

        $id_generated = $wpdb->insert_id;

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Insert operation failed. Query returns an error.' );
        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 ) {
            return new DatabaseResponseNotAffected();
        }

        return new DatabaseResponseSuccess(
            'Row inserted successfully.',
            array( 'id' => $id_generated ),
        );

    }

    /**
     * Execute multiple insert queries.
     *
     * @param array $tables_inserts array of tables and inserts
     * @param bool $on_error_rollback If true start a transaction and rollback on error.
     *
     */
    public function insert_batch( array $tables_inserts, bool $on_error_rollback = true ): DatabaseResponse {

        $results = array();

        if ( $on_error_rollback ) {
            DatabaseTransaction::start();
        }

        foreach ( $tables_inserts as $table_name => $data ) {

            $result = $this->insert_row( $table_name, $data );

            if ( $result instanceof DatabaseResponseError || $result instanceof DatabaseResponseNotAffected ) {

                if ( $on_error_rollback ) {
                    DatabaseTransaction::rollback();
                }

                return $result;
            }

            $results[$table_name] = $result;

        }

        if ( $on_error_rollback ) {
            DatabaseTransaction::commit();
        }

        return new DatabaseResponseSuccess(
            'Rows inserted successfully.',
            array( 'results' => $results ),
        );

    }

    /**
     * Insert multiple rows.
     *
     * @param [type] $table_name
     * @param [type] $request - Data to insert
     * @return void
     *
     * @example Usage
     * $table_name = 'table_name';
     * $data = array(
    array( 'id' => 1, 'name' => 'John' ),
    array( 'id' => 2, 'name' => 'Doe' ),
    );
    insert_multiple_rows( $table_name, $data );
     */
    public function insert_multiple_rows( $table_name, $request ) {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;
        $column_keys   = '';
        $column_values = '';
        $sql           = '';
        $last_key      = array_key_last( $request );
        $first_key     = array_key_first( $request );

        foreach ( $request as $k => $value ) {
            $keys = array_keys( $value );

            /**  Prepare column keys & values.*/

            foreach ( $keys as $v ) {
                $column_keys .= sanitize_key( $v ) . ',';
                $sanitize_value = sanitize_text_field( $value[$v] );
                $column_values .= is_numeric( $sanitize_value ) ? $sanitize_value . ',' : "'$sanitize_value'" . ',';
            }

            // Trim trailing comma.
            $column_keys   = rtrim( $column_keys, ',' );
            $column_values = rtrim( $column_values, ',' );

            if ( $first_key === $k ) {
                $sql .= "INSERT INTO {$table_name} ($column_keys) VALUES ($column_values),";
            } elseif ( $last_key == $k ) {
                $sql .= "($column_values)";
            } else {
                $sql .= "($column_values),";
            }

            // Reset keys & values to avoid duplication.
            $column_keys   = '';
            $column_values = '';
        }

        $result = $wpdb->query( $sql );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Insert operation failed. Query returns an error.' );
        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 ) {
            return new DatabaseResponseNotAffected();
        }

        return new DatabaseResponseSuccess(
            'Row inserted successfully.',
            array( 'result' => $result ),
        );
    }

    /**
     * Update a row.
     *
     * @return DatabaseResponse
     */
    public function update_row( string $table_name, array $data, array $where, array $data_format = array(), array $where_format = array() ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $result = $wpdb->update(
            $table_name,
            $data,
            $where,
            empty( $data_format ) ? null : $data_format,
            empty( $where_format ) ? null : $where_format,
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Update operation failed. Query returns an error.' );
        }

        /**
         * no rows were affected
         *
         * This result is returned when:
         * - the condition is not met
         * - the update operation is not performed because you are updating a column with the same value as the existing value
         */

        if ( is_array( $result ) && count( $result ) === 0 || $result === 0 ) {
            return new DatabaseResponseNotAffected();
        }

        return new DatabaseResponseSuccess( 'Row updated successfully.' );

    }

    /**
     * Delete a row.
     *
     * @return DatabaseResponse
     */
    public function delete_row( string $table_name, array $where ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $result = $wpdb->delete(
            $table_name,
            $where
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Delete operation failed. Query returns an error.' );

        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 || $result === 0 ) {
            return new DatabaseResponseNotAffected();
        }

        return new DatabaseResponseSuccess( 'Row deleted successfully.' );

    }

    /**
     * Get a row.
     *
     * @return DatabaseResponse
     */
    public function get_row( string $table_name, string $where ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $result = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM `$table_name` WHERE $where" ),
            ARRAY_A
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Get row operation failed. Query returns an error.' );
        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 || $result === null ) {
            return new DatabaseResponseNotFound();
        }

        return new DatabaseResponseSuccess( 'Row retrieved successfully.', $result );

    }

    /**
     * Get all rows.
     *
     * @return DatabaseResponse
     */
    public function get_all_rows( string $table_name ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $result = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM `$table_name`" ),
            ARRAY_A
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Get all rows operation failed. Query returns an error.' );

        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 ) {
            return new DatabaseResponseEmpty( 'No records were found', array() );
        }

        return new DatabaseResponseSuccess( 'All rows retrieved successfully.', $result );

    }

    /**
     * Make a generic query given as parameter.
     *
     * @return DatabaseResponse
     */
    public function query( string $sql, ?string $table_name = null ): DatabaseResponse {

        global $wpdb;

        if ( $table_name !== null ) {
            $sql = str_replace( '%table_name%', $table_name, $sql );
        }

        $result = $wpdb->get_results(
            $wpdb->prepare( $sql ),
            ARRAY_A
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Query operation failed, it returns an error.' );

        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 ) {
            return new DatabaseResponseEmpty( 'No records were affected', array() );
        }

        return new DatabaseResponseSuccess( 'All rows retrieved successfully.', $result );

    }

    /**
     * Make a generic SELECT query given as parameter.
     *
     * @param string $table_name
     * @param array $conditions
     * @return DatabaseResponse
     */
    public function select( string $table_name, array $conditions ): DatabaseResponse {

        if ( DatabaseHelpers::table_exists( $table_name ) === false ) {
            return new DatabaseResponseError( 'Table ' . $table_name . ' does not exist.' );
        }

        global $wpdb;

        $sql = "SELECT * FROM `$table_name` WHERE ";

        foreach ( $conditions as $key => $value ) {
            $sql .= $key . " = '" . $value . "' AND ";
        }

        $sql = rtrim( $sql, ' AND ' );

        $result = $wpdb->get_results(
            $wpdb->prepare( $sql ),
            ARRAY_A
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Query operation failed, it returns an error.' );

        }

        /**
         * no rows were affected
         */

        if ( is_array( $result ) && count( $result ) === 0 ) {
            return new DatabaseResponseEmpty( 'No records were affected', array() );
        }

        return new DatabaseResponseSuccess( 'All rows retrieved successfully.', $result );

    }

}
