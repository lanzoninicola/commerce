<?php

namespace Commerce\Backend\App\Services\RestApi;

interface RepositoryInterface {

    /**
     * Get all records.
     *
     * @return array
     */
    public function find_all();

    /**
     * Get record by id.
     *
     * @param int $id
     * @return array
     */
    public function find_by_id( int $id );

    /**
     * Insert a new record.
     *
     * @param object $data
     * @return int
     */
    public function insert( object $data );

    /**
     * Update a record.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update( array $data, int $id );

    /**
     * Delete a record.
     *
     * @param int $id
     * @return int
     */
    public function delete( int $id );

}