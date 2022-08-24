<?php

namespace Commerce\App\Services;

use Commerce\App\Common\EventListenable;
use Commerce\App\Services\Analytics\Analytics;
use function Commerce\get_plugin_name;

class Onboarding implements EventListenable {

    public function __construct() {
        self::pending();
    }

    /**
     * Attach an event listener to the given action name.
     * This method is executed when the plugin run.
     *
     * @param string $event_name
     * @return void
     */
    public function on( string $event ) {

        add_action( $event, array( $this, 'remote_check' ) );
    }

    /**
     * Get the onboarding status
     *
     * @return string 'pending'|'completed'
     */
    public static function get_status() {
        return get_option( strtolower( get_plugin_name() ) . '_onboarding_status', 'pending' );
    }

    /**
     * Set the option "_onboarding_status" to 'pending' in the database.
     *
     * @return void
     */
    public static function pending() {

        $option_name = strtolower( get_plugin_name() ) . '_onboarding_status';
        update_option( $option_name, 'pending' );
    }

    /**
     * Set the option "_onboarding_status" to 'completed' in the database.
     *
     * @return void
     */
    public static function completed() {

        $option_name = strtolower( get_plugin_name() ) . '_onboarding_status';
        update_option( $option_name, 'completed' );
    }

    /**
     * Check if the option is set to true in the database.
     * If true require the user to complete the onboarding.
     *
     * @return boolean
     */
    public static function is_pending(): bool {
        $status = get_option( strtolower( get_plugin_name() ) . '_onboarding_status' );

        return $status === 'pending';
    }

    /**
     * Check if the option is set to true in the database.
     * If true the user has completed the onboarding.
     *
     * @return boolean
     */
    public static function is_completed(): bool {
        $status = get_option( strtolower( get_plugin_name() ) . '_onboarding_status' );

        return $status === 'completed';
    }

    // TODO: handling technical errors with third party services.
    public static function remote_check() {

        $installation_id   = get_option( get_plugin_name() . '_installation_id' );
        $onboarding_status = get_option( strtolower( get_plugin_name() ) . '_onboarding_status' );

        if ( $installation_id === false ) {
            return;
        }

        if ( $onboarding_status === 'completed' ) {
            return;
        }

        $url = '/installations/' . $installation_id;

        $response = Analytics::get_data( $url );

        if ( is_wp_error( $response ) ) {
            return;
        }

        $body            = json_decode( $response['body'] );
        $response_status = $body->data->status;

        if ( $response_status >= 400 ) {
            return;
        }

        $response_payload = $body->data->payload;

        if ( $response_payload->wp_user_id === null ) {
            self::pending();
        } else {
            self::completed();
        }

    }

}
