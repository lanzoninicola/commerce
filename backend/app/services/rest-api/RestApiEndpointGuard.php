<?php

namespace Commerce\Backend\App\Services\RestApi;

use Commerce\Backend\App\Traits\SanitizerTrait;

class RestApiEndpointGuard implements RestApiEndpointGuardInterface {

    use SanitizerTrait;

    /**
     * Array of validation/sanitization rule.
     *
     * @var array
     */
    protected $rules = array();

    /**
     * RestApiEndpointGuard constructor.
     * The rules is the collection of the rest api enpoint argument
     * and the validation/sanitization rules.
     *
     * $rule = array( 'arg_name' => array( 'validation_rules' ) );
     *
     *
     * @param array $rules
     */
    public function __construct( array $rules = array() ) {
        $this->rules = $rules;
    }

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @return array of validation/sanitization rule.
     */
    public function rules(): array{
        return $this->rules;
    }

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @param $field_value is the value of the request argument
     * @param $request is the request object
     * @param $field is the name of the request argument
     * @return boolean true if the request argument is valid, false otherwise
     */
    public function validate_request_arg( $field_value, $request, $field ): bool {

        /** if no rules are found pass the validation (cannot return error due wordpress standard functionality)*/

        if ( empty( $this->rules ) ) {
            return true;
        }

        $field_rules = (array) $this->rules[$field];

        var_dump( $this->validate( $field_value, $field_rules ) );

        return $this->validate( $field_value, $field_rules );

    }

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @param $field_value is the value of the request argument
     * @param $request is the request object
     * @param $field is the name of the request argument
     * @return mixed the sanitized value of the request argument
     */
    public function sanitize_request_arg( $field_value, $request, $field ) {

        /** if no rules are found return the value (cannot return error due wordpress standard functionality)*/

        if ( empty( $this->rules ) ) {
            return $field_value;
        }

        $field_rules = $this->rules[$field];
        $type        = $this->get_type( $field_rules );

        return $this->sanitize( $field_value, $type );
    }

    /**
     * Validate the request arguments value based on the rules.
     *
     * @param array $value
     * @param array $rules
     * @return boolean
     */
    public function validate( $value, array $rules ): bool {

        if ( $rules['required'] && empty( $value ) ) {
            return false;
        }

        if ( !empty( $rules['type'] ) && $rules['type'] !== gettype( $value ) ) {
            return false;
        }

        return true;

    }

    /**
     * Return the type rule.
     *
     * Example of type descriptor: 'string:email'
     *
     * @param array $rules
     * @param bool $descriptor if true and if exists the function must returns the type descriptor instead of the global type     *
     * @return string
     */
    private function get_type( array $rules, bool $descriptor = true ): string {

        $type_descriptor = explode( ':', $rules['type'] );

        if ( $descriptor ) {
            return isset( $type_descriptor[1] ) ? $type_descriptor[1] : $type_descriptor[0];
        }

        return $rules['type'];

    }

}
