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
     * @return Error | \WP_REST_Response
     */
    public function new_onboarding( \WP_REST_Request $request ) {

        $operation = 'New onboarding';

        $new_onboarding = $request->get_params();

        $result = $this->service->new_onboarding( $new_onboarding );

        if ( $result instanceof Error ) {

            return RestApiResponseError::error(
                $result->message(),
                $result->data()
            );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(

            'operation' => $operation,
            'payload'   => null,
        ) );

    }

    /**
     * Checking if the onboarding is required for the user
     *
     * @param \WP_REST_Request $request
     * @return Error | \WP_REST_Response
     */
    public function should_onboarding_required( \WP_REST_Request $request ) {

        $operation = 'Should onboarding required';

        $request_params = $request->get_params();

        $result = $this->service->should_onboarding_required( $request_params['email'] );

        if ( $result instanceof Error ) {

            return RestApiResponseError::error(
                $result->message(),
                $result->data()
            );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(

            'operation' => $operation,
            'payload'   => $result,
        ) );

    }

}
