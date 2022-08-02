<?php

namespace Commerce\Client\Backend\Api\V1\Analytics;

use Commerce\App\Common\Error;
use Commerce\App\Common\Helpers;

class AnalyticsService {

    /**
     * @var AnalyticsRepository
     */
    protected $repository;

    /**
     * Singleton instance.
     *
     * @var AnalyticsService
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return AnalyticsService
     */
    public static function singletone( AnalyticsRepository $repository ) {

        if ( self::$instance === null ) {
            self::$instance = new AnalyticsService( $repository );
        }

        return self::$instance;
    }

    public function __construct( AnalyticsRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Tracking new product installation.
     *
     * @param array $data
     * @return bool|Error True or Error
     */
    public function track_new_product_installation( array $data ) {

        $result = $this->repository->add_new_product_installation(
            $data['product_id'],
            $data['installation_id'],
            $data['site_url'],
            $data['site_language'],
            $data['site_timezone']
        );

        if ( Helpers::is_error( $result ) ) {
            return new Error( 'add_new_product_installation', $result->get_error_message(), $result->get_error_data() );
        }

        return true;

    }

    /**
     * Get the product installation record
     *
     * @param string $installation_id
     * @return array|bool|Error installation record if exists, false if not exists, Error if error
     */
    public function find_by_product_installation_id( string $installation_id ) {

        $result = $this->repository->find_by_product_installation_id( $installation_id );

        if ( Helpers::is_error( $result ) ) {
            return new Error( 'find_by_product_installation_id', $result->get_error_message(), $result->get_error_data() );
        }

        $record_installation_id = $result[0];

        if ( empty( $record_installation_id ) ) {
            return false;
        }

        return $record_installation_id;

    }

}
