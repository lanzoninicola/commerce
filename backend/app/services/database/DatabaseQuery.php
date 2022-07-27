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
    public function insert_row( string $table_name, array $data ): DatabaseResponse {

        global $wpdb;

        $result = $wpdb->insert(
            $table_name,
            $data
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
     * Update a row.
     *
     * @return DatabaseResponse
     */
    public function update_row( string $table_name, array $data, array $where ): DatabaseResponse {

        global $wpdb;

        $result = $wpdb->update(
            $table_name,
            $data,
            $where
        );

        /**
         * query returns an error
         */

        if ( $result === false ) {
            return new DatabaseResponseError( 'Update operation failed. Query returns an error.' );
        }

        /**
         * no rows were affected
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

}
