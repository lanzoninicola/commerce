<?php

namespace Commerce\Client\Backend\Api\V1\Onboarding;

use Commerce\App\Common\Error;
use Commerce\App\Services\Database\DatabaseQueryInterface;
use Commerce\App\Services\Database\DatabaseResponseError;

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
     * Create a new WP User
     *
     * @param string $email
     * @param string $fullname
     * @return int|Error The newly created user's ID or a WP_Error object if the user could not
     *                      be created.
     */
    public function create_wp_user( string $email, string $fullname ) {

        $wp_user_id = wp_insert_user( array(
            'user_login' => $email,
            'user_pass'  => wp_generate_password(),
            'user_email' => $email,
            'first_name' => $fullname,
            'role'       => 'subscriber',
        ) );

        if ( is_wp_error( $wp_user_id ) ) {
            return new Error(
                'wp_user_insert_failed',
                $wp_user_id->get_error_message(),
                $wp_user_id->get_error_data()
            );
        }

        return $wp_user_id;

    }

    /**
     * Match the product installation with the user.
     *
     * @param integer $product_id
     * @param string $installation_id
     * @param integer $wp_user_id
     * @return Error|true True if the record was inserted, Error otherwise.
     */
    public function match_product_installation_with_user(
        int $product_id,
        string $installation_id,
        int $wp_user_id
    ) {

        $result = $this->query_service->update_row(
            'wp_comm_products_installations',
            array(
                'wp_user_id' => $wp_user_id,
            ),
            array(
                'installation_id' => $installation_id,
                'product_id'      => $product_id,
            ),
            array(),
            array( '%s', '%d' )
        );

        if ( $result instanceof DatabaseResponseError ) {
            return new Error(
                'update_product_installation_error',
                'Error updating the product installation with the wp_user_id',
                $result->get_payload()
            );
        }

        return true;

    }

    public function add_user_marketing_preferences(
        int $wp_user_id,
        int $consent_newsletter,
        int $consent_terms,
        int $consent_privacy
    ) {

        $result = $this->query_service->insert_row(
            'wp_comm_user_marketing_preferences',
            array(
                'wp_user_id'         => $wp_user_id,
                'consent_newsletter' => $consent_newsletter,
                'consent_terms'      => $consent_terms,
                'consent_privacy'    => $consent_privacy,
            )
        );

        if ( $result instanceof DatabaseResponseError ) {
            return new Error(
                'insert_user_marketing_preferences_error',
                'Error adding new user marketing preferences',
                $result->get_payload()
            );
        }

        return $result;

    }

}
