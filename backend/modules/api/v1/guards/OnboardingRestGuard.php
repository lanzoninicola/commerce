<?php

namespace Commerce\Backend\Modules\Api\V1\Guards;

use Commerce\Backend\App\Common\Error;
use Commerce\Backend\App\Common\Helpers;
use Commerce\Backend\App\Traits\SanitizerTrait;
use Commerce\Backend\App\Traits\ValidatorTrait;

class OnboardingRestGuard {

    use ValidatorTrait;
    use SanitizerTrait;

    /**
     * Set to true if the guard reject the request for validation errors.
     *
     * @var boolean
     */
    private bool $reject = false;

    /**
     * Store the Error object if some error occurs.
     *
     * @var Error
     */
    private Error $error;

    /**
     * Validation rules and sanitization info of the request parameters.
     *
     * @var array
     */
    private $rules = array(
        '/commerce/v1/onboarding' => array(
            'fullname'           => array(
                'required' => true,
                'type'     => 'string',
            ),
            'email'              => array(
                'required' => true,
                'type'     => 'email',
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
        ) );

    /**
     * The request is first validated against the rules.
     * If the request is invalid, an error is returned otherwise the request is sanitized.
     *
     * The request is then sanitized against the rules.
     *
     * @param \WP_REST_Request $request
     * @return array of data sanitized
     */
    public function check( \WP_REST_Request $request ) {

        $route_rules   = $this->rules[$request->get_route()];
        $data_to_check = $request->get_params();

        $this->sanitize_data( $route_rules, $data_to_check );

        $this->validate_data( $route_rules, $this->sanitized_data );

    }

    /**
     * Validation process.
     * Register the error if the request is invalid.
     *
     * @return void
     */
    private function validate_data( $route_rules, $data_to_check ) {

        $validation_result = $this->required_fields( $route_rules, $data_to_check );

        if ( Helpers::is_error( $validation_result ) ) {

            $this->reject = true;
            $this->error  = $validation_result;
        }

    }

    /**
     * Sanitization process.
     * Register the error if the request is invalid.
     *
     * @param array $rules
     * @param array $data
     * @return void
     */
    private function sanitize_data( array $rules, array $data ) {

        $sanitized_data = $this->bulk_sanitize(
            $rules,
            $data
        );

        if ( Helpers::is_error( $sanitized_data ) ) {

            $this->reject = true;
            $this->error  = $sanitized_data;
        }

        $this->sanitized_data = $sanitized_data;

    }

    /**
     * Returns the sanitized data.
     *
     * @return array
     */
    public function get_sanitized_data() {

        return $this->sanitized_data;
    }

    /**
     * Returns the result of guard validation.
     *
     * @param array $rules
     * @param array $data
     * @return void
     */
    public function is_rejected(): bool {

        if ( $this->reject ) {
            return true;
        }

        return false;

    }

    /**
     * Returns the error object.
     *
     * @return Error
     */
    public function get_error(): Error {

        return $this->error;

    }

}
