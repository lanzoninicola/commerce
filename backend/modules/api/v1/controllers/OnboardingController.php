<?php

namespace Commerce\Backend\Modules\Api\V1\Controllers;

use Commerce\Backend\App\Services\Database\DatabaseResponseEmpty;
use Commerce\Backend\App\Services\Database\DatabaseResponseError;
use Commerce\Backend\App\Services\Database\DatabaseResponseNotAffected;
use Commerce\Backend\App\Services\Database\DatabaseResponseNotFound;
use Commerce\Backend\App\Services\RestApi\BaseController;
use Commerce\Backend\App\Services\RestApi\RequestSanitizerTrait;
use Commerce\Backend\App\Services\RestApi\RequestValidatorTrait;
use Commerce\Backend\App\Services\RestApi\RestApiResponseError;
use Commerce\Backend\App\Services\RestApi\RestApiResponseSuccess;
use Commerce\Backend\Modules\Api\V1\Repositories\OnboardingRepository;

class OnboardingController extends BaseController {

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
        parent::__construct( $repository );
    }

    public function create( \WP_REST_Request $request ) {
        $operation = 'New onboarding';

        $expected_fields = array(
            'fullname'           => array(
                'required' => true,
                'type'     => 'string',
            ),
            'email'              => array(
                'required' => true,
                'type'     => 'string',
            ),
            'consent_newsletter' => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'consent_privacy'    => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'consent_terms'      => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'installation_id'    => array(
                'required' => false,
                'type'     => 'string',
            ),
            'site_language'      => array(
                'required' => false,
                'type'     => 'string',
            ),
            'timezone'           => array(
                'required' => false,
                'type'     => 'string',
            ),
        );

        $missing_fields = RequestValidatorTrait::required_fields(
            $expected_fields,
            $request,
            $operation
        );

        if ( $missing_fields !== null ) {
            return RestApiResponseError::missing_multi_parameters( $missing_fields, $operation, $missing_fields );
        }

        $new_onboarding = RequestSanitizerTrait::bulk_sanitize(
            $expected_fields,
            $request
        );

        $result = $this->repository->find_by_id( $new_onboarding['installation_id'] );

        /**
        $result = $this->repository->insert( $new_onboarding );

        if ( !email_exists( $new_onboarding['email'] ) ) {
        $user_id = wp_insert_user(
        array(
        'user_login'   => $new_onboarding['email'],
        'user_email'   => $new_onboarding['email'],
        'first_name'   => $new_onboarding['fullname'],
        'display_name' => $new_onboarding['fullname'],
        'user_pass'    => 'passwordgoeshere',
        'role'         => 'subscriber',
        )
        );
        }

        if ( $user_id instanceof \WP_Error ) {
        return new RestApiResponseError( $user_id->get_error_message(), $operation );
        }

        if ( $result instanceof DatabaseResponseError ) {
        return RestApiResponseError::database_error( $result->get_message(), $operation );
        }

        return RestApiResponseSuccess::success( 'Onboarding success', array(
        'operation' => $operation,
        'payload'   => $result->to_array()['payload'],
        ) );
         */

    }

    /**
    public function update( \WP_REST_Request $request ) {
    $operation    = 'Countdown update';
    $countdown_id = absint( $request->get_param( 'id' ) );

    if ( !is_numeric( $countdown_id ) ) {
    return RestApiResponseError::invalid_parameter( 'id', $operation );
    }

    $name_param        = $request->get_param( 'name' );
    $description_param = $request->get_param( 'description' );

    if ( empty( $name_param ) || $name_param === null ) {
    return RestApiResponseError::missing_parameter( 'name', $operation );
    }

    if ( empty( $description_param ) || $description_param === null ) {
    return RestApiResponseError::missing_parameter( 'description', $operation );
    }

    $next_countdown = array(
    'name'        => sanitize_text_field( $name_param ),
    'description' => sanitize_text_field( $description_param ),
    );

    $result = $this->repository->update( $next_countdown, $countdown_id );

    if ( $result instanceof DatabaseResponseError ) {
    return RestApiResponseError::database_error( $result->get_message(), $operation );
    }

    if ( $result instanceof DatabaseResponseNotAffected ) {
    return RestApiResponseError::database_records_not_affected( $result->get_message(), $operation );
    }

    return RestApiResponseSuccess::success( 'Countdown updated', array(
    'operation' => $operation,
    'payload'   => $result->to_array()['payload'],
    ) );

    }

    public function delete( \WP_REST_Request $request ) {
    $operation    = 'Countdown deletion';
    $countdown_id = absint( $request->get_param( 'id' ) );

    if ( !is_numeric( $countdown_id ) ) {
    return RestApiResponseError::invalid_parameter( 'id', $operation );
    }

    $result = $this->repository->delete( $countdown_id );

    if ( $result instanceof DatabaseResponseError ) {
    return RestApiResponseError::database_error( $result->get_message(), $operation );
    }

    if ( $result instanceof DatabaseResponseNotAffected ) {
    return RestApiResponseError::database_records_not_affected( $result->get_message(), $operation );
    }

    return RestApiResponseSuccess::success( 'Countdown deleted', array(
    'operation' => $operation,
    'payload'   => $result->to_array()['payload'],
    ) );

    }

    public function find_all() {
    $operation = 'Countdown find all';

    $result = $this->repository->find_all();

    if ( $result instanceof DatabaseResponseError ) {
    return RestApiResponseError::database_error( $result->get_message(), $operation );
    }

    if ( $result instanceof DatabaseResponseEmpty ) {
    return RestApiResponseSuccess::database_records_empty( $result->get_message(), $operation );
    }

    return RestApiResponseSuccess::success( 'Countdowns found', array(
    'operation' => $operation,
    'payload'   => $result->to_array()['payload'],
    ) );

    }

    public function find_by_id( \WP_REST_Request $request ) {

    $operation = 'Countdown find by id';

    $countdown_id = absint( $request->get_param( 'id' ) );

    if ( !is_numeric( $countdown_id ) ) {
    return RestApiResponseError::invalid_parameter( 'id', $operation );
    }

    $result = $this->repository->find_by_id( $countdown_id );

    if ( $result instanceof DatabaseResponseError ) {
    return RestApiResponseError::database_error( $result->get_message(), $operation );
    }

    if ( $result instanceof DatabaseResponseNotFound ) {
    return RestApiResponseError::database_record_not_found( $result->get_message(), $operation );
    }

    return RestApiResponseSuccess::success( 'Countdown found', array(
    'operation' => $operation,
    'payload'   => $result->to_array()['payload'],
    ) );

    }
     */

}
