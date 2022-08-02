<?php

namespace Commerce\Client\Backend\Api\V1\Onboarding;

use Commerce\App\Common\Error;
use Commerce\App\Services\RestApi\RestApiResponseError;
use Commerce\App\Services\RestApi\RestApiResponseSuccess;

class OnboardingController {

    /**
     * @var OnboardingService
     */
    protected $service;

    /**
     * Singleton instance.
     *
     * @var OnboardingController
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return OnboardingController
     */
    public static function singletone( OnboardingService $service ) {

        if ( self::$instance === null ) {
            self::$instance = new OnboardingController( $service );
        }

        return self::$instance;
    }

    public function __construct( OnboardingService $service ) {
        $this->service = $service;
    }

    /**
     * Register a new onboarding request
     *
     * @param \WP_REST_Request $request
     * @return void
     */
    public function new_onboarding( \WP_REST_Request $request ) {

        $operation = 'New onboarding';

        $new_onboarding = $request->get_params();

        $result = $this->service->new_onboarding( $new_onboarding );

        if ( $result instanceof Error ) {

            return RestApiResponseError::error( $result->get_error_message(), $result->get_error_data() );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(

            'operation' => $operation,
            'payload'   => array(
                'user_id' => $result,
            ),
        ) );

    }

}
