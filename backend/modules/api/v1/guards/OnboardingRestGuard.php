<?php

namespace Commerce\Backend\Modules\Api\V1\Guards;

use Commerce\Backend\App\Traits\SanitizerTrait;
use Commerce\Backend\App\Traits\ValidatorTrait;

class OnboardingRestGuard {

    use ValidatorTrait;
    use SanitizerTrait;

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
        ) );

    /**
     * The request is first validated against the rules.
     * If the request is invalid, an error is returned otherwise the request is sanitized.
     *
     * The request is then sanitized against the rules.
     *
     * @param \WP_REST_Request $request
     * @return void | array of data sanitized
     */
    public function check( \WP_REST_Request $request ) {

        $route_rules   = $this->rules[$request->get_route()];
        $data_to_check = $request->get_params();

// try {

//     $this->required_fields( $route_rules, $data_to_check );

// } catch ( \Exception $e ) {

//     $this->reject = true;

//     $this->error_code    = null;

//     $this->error_message = $e->getMessage();

// }

        $validation_result = $this->required_fields( $route_rules, $data_to_check );

        if ( is_wp_error( $validation_result ) ) {

            $this->reject        = true;
            $this->error_code    = $validation_result->get_error_code();
            $this->error_message = $validation_result->get_error_message();
            $this->error_data    = $validation_result->get_error_data() ?? array();
        }

// $this->validate_data( $route_rules, $data_to_check );

        // return $this->sanitize_data( $route_rules, $data_to_check );

    }

    /**
     * Returns the sanitized data.
     *
     * @param array $rules
     * @param array $data
     * @return void
     */
    private function sanitize_data( array $rules, array $data ) {

        return $this->sanitize(
            $rules,
            $data
        );
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
     * Returns the error message.
     *
     * @return string containing the error message.
     */
    public function get_error_code(): string {

        return $this->error_code;
    }

    /**
     * Returns the error message.
     *
     * @return string containing the error message.
     */
    public function get_error_message(): string {

        return $this->error_message;
    }

    /**
     * Returns the error data.
     *
     * @return array containing the error data.
     */
    public function get_error_data(): array{

        return $this->error_data;
    }

}
