<?php

namespace Commerce\Backend\Modules\Api\V1\Analytics;

use Commerce\Backend\App\Common\Error;
use Commerce\Backend\App\Services\Database\DatabaseQueryInterface;
use Commerce\Backend\App\Services\Database\DatabaseResponseEmpty;
use Commerce\Backend\App\Services\Database\DatabaseResponseError;

class AnalyticsRepository {

    /**
     * Query service.
     *
     * @var DatabaseQueryInterface
     */
    protected $query_service;

    /**
     * Singleton instance.
     *
     * @var self
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return self
     */
    public static function singletone( DatabaseQueryInterface $query_service ) {

        if ( self::$instance === null ) {
            self::$instance = new self( $query_service );
        }

        return self::$instance;
    }

    public function __construct( DatabaseQueryInterface $query_service ) {
        $this->query_service = $query_service;

    }

    /**
     * Create the model and insert a record in the table countdowns.
     *
     * @param AnalyticsModel $data
     * @return Error|int Error or new record of installation id
     */
    public function add_new_product_installation(
        int $product_id,
        string $installation_id,
        string $site_url,
        string $site_language,
        string $site_timezone
    ) {

        $result = $this->query_service->insert_row( 'wp_comm_products_installations', array(
            'product_id'      => $product_id,
            'installation_id' => $installation_id,
            'site_url'        => $site_url,
            'site_language'   => $site_language,
            'site_timezone'   => $site_timezone,
            'wp_user_id'      => null,
        ), );

        if ( $result instanceof DatabaseResponseError || $result instanceof DatabaseResponseEmpty ) {
            return new Error( 'insert_product_installation_error', 'Error adding new product installation', $result->get_payload() );
        }

        return $result->get_payload();

    }

    public function find_by_product_installation_id( string $installation_id ) {

        $result = $this->query_service->select(
            'wp_comm_products_installations',
            array(
                'installation_id' => $installation_id,
            )
        );

        if ( $result instanceof DatabaseResponseError || $result instanceof DatabaseResponseEmpty ) {
            return new Error( 'find_by_installation_id_error', 'Error finding by installation id', $result->get_payload() );
        }

        return $result->get_payload();
    }

}
