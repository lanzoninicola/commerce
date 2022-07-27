<?php

namespace Commerce\Backend\Modules\Api\V1\Repositories;

use Commerce\Backend\App\Services\Database\DatabaseHelpers;
use Commerce\Backend\App\Services\Database\DatabaseQueryInterface;
use Commerce\Backend\App\Services\RestApi\RepositoryInterface;

class OnboardingRepository implements RepositoryInterface {

    /**
     * Query service.
     *
     * @var DatabaseQueryInterface
     */
    private $query_service;

    /**
     * The name of the table
     *
     * @param string $table_name
     */
    private $table_name;

    /**
     * Singleton instance.
     *
     * @var OnboardingRepository
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return OnboardingRepository
     */
    public static function singletone( DatabaseQueryInterface $query_service ) {

        if ( self::$instance === null ) {
            self::$instance = new OnboardingRepository( $query_service );
        }

        return self::$instance;
    }

    public function __construct( DatabaseQueryInterface $query_service ) {
        $this->query_service = $query_service;
        $this->table_name    = DatabaseHelpers::get_table_fullname( COMMERCE_PLUGIN_DB_PREFIX, 'countdowns' );

    }

    /**
     * Create the model and insert a record in the table countdowns.
     *
     * @param array $data
     * @return DatabaseResponse
     */
    public function insert( array $data ) {

        $countdown = array(
            'id'          => 0,
            'name'        => $data['name'],
            'description' => $data['description'],
            'created_at'  => date( 'Y-m-d H:i:s' ),
        );

        return $this->query_service->insert_row( $this->table_name, $countdown );

    }

    public function update( array $data, int $id ) {

        $updated_countdown = array(
            'name'        => $data['name'],
            'description' => $data['description'],
            'updated_at'  => date( 'Y-m-d H:i:s' ),
        );

        return $this->query_service->update_row( $this->table_name, $updated_countdown, array( 'id' => $id ) );

    }

    public function delete( int $id ) {

        return $this->query_service->delete_row( $this->table_name, array( 'id' => $id ) );

    }

    /**
     * Get the list of countdowns.
     * If found return the DatabaseResponseSuccess object that contains the Countdown model.
     * If not found return the DatabaseResponseError object.
     *
     * @param int The id of the countdown to find.
     * @return DatabaseResponse
     */
    public function find_all() {

        return $this->query_service->get_all_rows( $this->table_name );

    }

    /**
     * Search a countdown by id.
     * If found return the DatabaseResponseSuccess object that contains the Countdown model.
     * If not found return the DatabaseResponseError object.
     *
     * @param int The id of the countdown to find.
     * @return DatabaseResponse
     */
    public function find_by_id( int $id ) {

        return $this->query_service->get_row( $this->table_name, "id = {$id}" );

    }

    public function find_by_conditions( array $conditions ) {

        return $this->query_service->get_row( $this->table_name, $conditions );

    }

}
