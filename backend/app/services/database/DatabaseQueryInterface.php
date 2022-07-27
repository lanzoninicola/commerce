<?php

namespace Commerce\Backend\App\Services\Database;

use Commerce\Backend\App\Services\Database\DatabaseResponse;

interface DatabaseQueryInterface {

    /**
     * Insert a row.
     *
     * Use the identity operator (===) to check for errors: (e.g., false === $result),
     * and whether any rows were affected (e.g., 0 === $result).
     *
     * @return DatabaseResponse
     */
    public function insert_row( string $table_name, array $data ): DatabaseResponse;

    /**
     * Update a row.
     *
     * @return DatabaseResponse
     */
    public function update_row( string $table_name, array $data, array $where ): DatabaseResponse;

    /**
     * Delete a row.
     *
     * @return DatabaseResponse
     */
    public function delete_row( string $table_name, array $where ): DatabaseResponse;

    /**
     * Get a row.
     *
     * Returns null if no result is found,
     *
     * @return DatabaseResponse
     */
    public function get_row( string $table_name, string $where ): DatabaseResponse;

    /**
     * Get all rows.
     *
     * @return DatabaseResponse
     */
    public function get_all_rows( string $table_name ): DatabaseResponse;

}