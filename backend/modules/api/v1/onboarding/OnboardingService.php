<?php

namespace Commerce\Backend\Modules\Api\V1\Onboarding;

use Commerce\Backend\App\Common\Error;
use Commerce\Backend\App\Common\Helpers;

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

        $user_product_installation_id = $this->repository->match_product_installation_with_user(
            $data['product_id'],
            $data['installation_id'],
            $wp_user_id
        );

        if ( Helpers::is_error( $user_product_installation_id ) ) {
            return new Error(
                'user_product_installation_insert_failed',
                $user_product_installation_id->get_error_message(),
                $user_product_installation_id->get_error_data()
            );
        }

        $user_mkt_preference = $this->repository->add_user_marketing_preferences(
            $wp_user_id,
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

}
