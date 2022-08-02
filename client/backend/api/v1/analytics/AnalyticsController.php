<?php

namespace Commerce\Client\Backend\Api\V1\Analytics;

use Commerce\App\Common\Error;
use Commerce\App\Services\RestApi\RestApiResponseError;
use Commerce\App\Services\RestApi\RestApiResponseSuccess;

class AnalyticsController {

    /**
     * @var AnalyticsService
     */
    protected $service;

    /**
     * Singleton instance.
     *
     * @var AnalyticsController
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return AnalyticsController
     */
    public static function singletone( AnalyticsService $service ) {

        if ( self::$instance === null ) {
            self::$instance = new AnalyticsController( $service );
        }

        return self::$instance;
    }

    public function __construct( AnalyticsService $service ) {
        $this->service = $service;
    }

    public function new_product_installation( \WP_REST_Request $request ) {

        $operation = 'Tracking new product installation';

        $result = $this->service->track_new_product_installation( $request->get_params() );

        if ( $result instanceof Error ) {

            return RestApiResponseError::error(
                $result->get_error_message(),
                $result->get_error_data()
            );
        }

        return RestApiResponseSuccess::success(
            'Analytics success',
            array(
                'operation' => $operation,
                'payload'   => array(
                    'user_id' => $result,
                ),
            )
        );

    }

    public function find_by_product_installation_id( \WP_REST_Request $request ) {

        $operation       = 'Find product by installation id';
        $installation_id = $request->get_param( 'installation_id' );

        $result = $this->service->find_by_product_installation_id( $installation_id );

        if ( $result instanceof Error ) {
            return RestApiResponseError::error(
                $result->get_error_message(),
                $result->get_error_data()
            );
        }

        if ( $result === false ) {
            return RestApiResponseError::database_record_not_found(
                'Product installation not found',
                $operation,
                array(
                    'installation_id' => $installation_id,
                )
            );
        }

        return RestApiResponseSuccess::success(
            'Installation id found',
            array(
                'operation' => $operation,
                'payload'   => array(
                    'product_installation' => $result,
                ),
            )
        );
    }

}
