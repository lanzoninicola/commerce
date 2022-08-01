<?php

namespace Commerce\Backend\Modules\Api\V1\Onboarding;

use Commerce\Backend\App\Common\Error;
use Commerce\Backend\App\Services\Database\DatabaseQueryInterface;
use Commerce\Backend\App\Services\Database\DatabaseResponseEmpty;
use Commerce\Backend\App\Services\Database\DatabaseResponseError;
use Commerce\Backend\App\Services\Database\DatabaseTransaction;

class OnboardingRepository {

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
     * @param OnboardingModel $data
     * @return Error | new user id
     */
    public function insert( object $onboarding_model ) {

        $wp_user_id = null;

        /** check if the user exists by email */
        $wp_user_id = get_user_by( 'email', $onboarding_model->email );

        /** if the user exist, get the user id  */

        if ( $wp_user_id instanceof \WP_User ) {
            $wp_user_id = $wp_user_id->ID;
        }

        /** if the user doesn't exist, create it  */
        DatabaseTransaction::start();

        if ( $wp_user_id === false ) {

            $wp_user_id = wp_insert_user( array(
                'user_login' => $onboarding_model->email,
                'user_pass'  => wp_generate_password(),
                'user_email' => $onboarding_model->email,
                'first_name' => $onboarding_model->fullname,
                'role'       => 'subscriber',
            ) );

        }

        if ( is_wp_error( $wp_user_id ) ) {
            DatabaseTransaction::rollback();

            return new Error( 'wp_user_insert_failed', $wp_user_id->get_error_message(), $wp_user_id->get_error_data() );
        } else {

            DatabaseTransaction::commit();
        }

        // TODO check if the user has already given the consents to the terms and privacy policy, if so does not insert the record and do not present in the UI the consent form

        $result = $this->query_service->insert_batch(
            array(
                'wp_comm_user_marketing'         => array(
                    'wp_user_id'         => $wp_user_id,
                    'consent_newsletter' => $onboarding_model->consent_newsletter,
                    'consent_terms'      => $onboarding_model->consent_terms,
                    'consent_privacy'    => $onboarding_model->consent_privacy,
                ),
                'wp_comm_products_installations' => array(
                    'installation_id' => $onboarding_model->installation_id,
                    'wp_user_id'      => $wp_user_id,
                    'product_id'      => $onboarding_model->product_id,
                    'site_url'        => $onboarding_model->site_url,
                    'site_language'   => $onboarding_model->site_language,
                    'site_timezone'   => $onboarding_model->site_timezone,
                ),
            )
        );

        if ( $result instanceof DatabaseResponseError || $result instanceof DatabaseResponseEmpty ) {
            return new Error( 'insert_onboarding_error', 'Error inserting new onboarding', $result->get_payload() );
        }

        return $wp_user_id;

    }

    public function update( array $data, int $id ) {}

    public function delete( int $id ) {}

    public function find_all() {}

    public function find_by_id( int $id ) {}

    public function find_by_conditions( array $conditions ) {}

    public function find_by_installation_id( string $installation_id ) {

        $result = $this->query_service->select(
            'wp_comm_products_installations',
            array(
                'installation_id' => $installation_id,
            )
        );

        if ( $result instanceof DatabaseResponseError || $result instanceof DatabaseResponseEmpty ) {
            return new Error( 'find_by_installation_id_error', 'Error finding by installation id', $result->get_payload() );
        }

        var_dump( $result );

        return $result;
    }

}
