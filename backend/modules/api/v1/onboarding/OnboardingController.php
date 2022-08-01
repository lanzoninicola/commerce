<?php

namespace Commerce\Backend\Modules\Api\V1\Onboarding;

use Commerce\Backend\App\Common\Error;
use Commerce\Backend\App\Services\RestApi\RestApiResponseError;
use Commerce\Backend\App\Services\RestApi\RestApiResponseSuccess;

class OnboardingController {

    /**
     * @var OnboardingRepository
     */
    protected $repository;

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
    public static function singletone( OnboardingRepository $repository ) {

        if ( self::$instance === null ) {
            self::$instance = new OnboardingController( $repository );
        }

        return self::$instance;
    }

    public function __construct( OnboardingRepository $repository ) {
        $this->repository = $repository;
    }

    public function create( \WP_REST_Request $request ) {

        $operation = 'New onboarding';

        $new_onboarding_params = $request->get_params();

        $new_onboarding = new OnboardingModel(
            $new_onboarding_params['fullname'],
            $new_onboarding_params['email'],
            $new_onboarding_params['consent_newsletter'],
            $new_onboarding_params['consent_terms'],
            $new_onboarding_params['consent_privacy'],
            $new_onboarding_params['product_id'],
            $new_onboarding_params['installation_id'],
            $new_onboarding_params['site_url'],
            $new_onboarding_params['site_language'],
            $new_onboarding_params['site_timezone']
        );

        $result = $this->repository->insert( $new_onboarding );

        if ( $result instanceof Error ) {

            return new RestApiResponseError( $result->get_error_message(), $operation );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(

            'operation' => $operation,
            'payload'   => array(
                'user_id' => $result,
            ),
        ) );

    }

    public function find_by_installation_id( \WP_REST_Request $request ) {

        $operation       = 'Find onboarding by installation id';
        $installation_id = $request->get_param( 'installation_id' );

        $result = $this->repository->find_by_installation_id( $installation_id );

        if ( $result instanceof Error ) {
            return new RestApiResponseError( $result->get_error_message(), $operation );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(
            'operation' => $operation,
            'payload'   => array(
                'onboarding' => 'cucu',
            ),
        ) );
    }

}
