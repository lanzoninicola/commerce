<?php

namespace Commerce\Client\Backend\Api\V1\Onboarding;

use Commerce\App\Common\Error;
use Commerce\App\Common\Helpers;
use Commerce\App\Services\Database\DatabaseResponseEmpty;

class OnboardingService {

    /**
     * @var OnboardingRepository
     */
    protected $repository;

    /**
     * Singleton instance.
     *
     * @var OnboardingService
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return OnboardingService
     */
    public static function singletone( OnboardingRepository $repository ) {

        if ( self::$instance === null ) {
            self::$instance = new OnboardingService( $repository );
        }

        return self::$instance;
    }

    public function __construct( OnboardingRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Create the model and insert a record in the table countdowns.
     *
     * @param array $data
     * @return Error | new user id
     */
    // TODO check if the user has already given the consents to the terms and privacy policy, if so does not insert the record and do not present in the UI the consent form
    public function new_onboarding( array $data ) {

        $wp_user_id = null;

        /** check if the user exists by email */
        $wp_user_id = get_user_by( 'email', $data['email'] );

        /** if the user exist, get the user id  */

        if ( $wp_user_id instanceof \WP_User ) {
            $wp_user_id = $wp_user_id->ID;
        } else {

            /** if the user doesn't exist, create it  */
            $wp_user_id = $this->repository->create_wp_user(
                $data['email'],
                $data['fullname']
            );
        }

        if ( Helpers::is_error( $wp_user_id ) ) {
            return new Error(
                'wp_user_insert_failed',
                $wp_user_id->get_error_message(),
                $wp_user_id->get_error_data()
            );
        }

        /** insert the record in the table onboarding */
        $user_mkt_preference = $this->repository->add_user_marketing_preferences(
            $data['email'],
            $data['consent_newsletter'],
            $data['consent_terms'],
            $data['consent_privacy']
        );

        if ( Helpers::is_error( $user_mkt_preference ) ) {

            return new Error(
                'insert_onboarding_error',
                $user_mkt_preference->get_error_message(),
                $user_mkt_preference->get_error_data()
            );
        }

        return $wp_user_id;

    }

    /**
     * Undocumented function
     *
     * @param string $email
     * @return Error | false if the onboarding is required, true if the user has already onboraded
     */
    public function should_onboarding_required( string $email ) {

        $user_mkt_preference = $this->repository->get_user_marketing_preferences( $email );

        if ( Helpers::is_error( $user_mkt_preference ) ) {

            return new Error(
                'get_onboarding_error',
                $user_mkt_preference->get_error_message(),
                $user_mkt_preference->get_error_data()
            );
        }

        if ( $user_mkt_preference instanceof DatabaseResponseEmpty ) {
            return true;
        }

        return false;
    }

}
