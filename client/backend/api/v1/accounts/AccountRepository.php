<?php

namespace Commerce\Client\Backend\Api\V1\Accounts;

use Commerce\App\Services\Database\DatabaseQueryInterface;

class AccountRepository {
    /**
     * Query service.
     *
     * @var DatabaseQueryInterface
     */
    private $query_service;

    /**
     * Singleton instance.
     *
     * @var AccountRepository
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return AccountRepository
     */
    public static function singletone( DatabaseQueryInterface $query_service ) {

        if ( self::$instance === null ) {
            self::$instance = new AccountRepository( $query_service );
        }

        return self::$instance;
    }

    public function __construct( DatabaseQueryInterface $query_service ) {
        $this->query_service = $query_service;

    }

    public function insert( object $onboarding_model ) {}

    public function update( array $data, int $id ) {}

    public function delete( int $id ) {}

    public function find_all() {}

    public function find_by_id( int $id ) {}

    public function find_by_conditions( array $conditions ) {}

}
