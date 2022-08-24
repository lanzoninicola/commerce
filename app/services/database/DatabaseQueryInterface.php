<?php

namespace Commerce\App\Services\Database;

use Commerce\App\Services\Database\DatabaseResponse;

interface DatabaseQueryInterface {

    /**
     * Insert a row.
     *
     * Use the identity operator (===) to check for errors: (e.g., false === $result),
     * and whether any rows were affected (e.g., 0 === $result).
     *
     * The payload is an associative array of key 'id' => the value of ID of new record insereted.
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotAffected|DatabaseResponseSuccess
     */
    public function insert_row( string $table_name, array $data, $format = null ): DatabaseResponse;

    /**
     * Executes multiple insert queries.
     *
     * @param array $tables_inserts
     * @param boolean $on_error_rollback
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotAffected|DatabaseResponseSuccess
     */
    public function insert_batch( array $tables_inserts, bool $on_error_rollback = false ): DatabaseResponse;

    /**
     * Insert multiple rows.
     *
     * @param [type] $table_name
     * @param [type] $request - Data to insert
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotAffected|DatabaseResponseSuccess
     *
     * @example Usage
     * $table_name = 'table_name';
     * $data = array(
    array( 'id' => 1, 'name' => 'John' ),
    array( 'id' => 2, 'name' => 'Doe' ),
    );
    insert_multiple_rows( $table_name, $data );
     */
    public function insert_multiple_rows( $table_name, $request );

    /**
     * Update a row.
     *
     * The payload is NULL when successful.
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotAffected|DatabaseResponseSuccess
     */
    public function update_row( string $table_name, array $data, array $where, array $data_format, array $where_format ): DatabaseResponse;

    /**
     * Delete a row.
     *
     * The payload is NULL when successful.
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotAffected|DatabaseResponseSuccess
     */
    public function delete_row( string $table_name, array $where ): DatabaseResponse;

    /**
     * Get a row.
     *
     * Returns null if no result is found,
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseNotFound|DatabaseResponseSuccess
     */
    public function get_row( string $table_name, string $where ): DatabaseResponse;

    /**
     * Get all rows.
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseEmpty|DatabaseResponseSuccess
     */
    public function get_all_rows( string $table_name ): DatabaseResponse;

    /**
     * Make a generic query given as parameter.
     *
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseEmpty|DatabaseResponseSuccess
     */
    public function query( string $sql, ?string $table_name = null ): DatabaseResponse;

    /**
     * Make a generic SELECT query given as parameter.
     *
     * @param string $table_name
     * @param array $conditions
     * @return DatabaseResponse DatabaseResponseError|DatabaseResponseEmpty|DatabaseResponseSuccess
     */
    public function select( string $table_name, array $conditions ): DatabaseResponse;

    /**
     * Get the last record mutated. The last record mutated is the last record inserted or updated.
     *
     * The table name must have the created_at and updated_at columns.
     *
     * @param string $table_name
     * @return DatabaseResponse
     */
    public function get_last_mutated_row( string $table_name ): DatabaseResponse;

}