<?php

namespace Commerce\Backend\App\Services\RestApi;

use Commerce\Backend\App\Traits\SanitizerTrait;

class BaseRestApiEndpointGuard implements RestApiEndpointGuardInterface {

    use SanitizerTrait;

    /**
     * Array of arguments and validation/sanitization rule for each endpoint.
     *
     * array( $route => array( $arg => $rules[]))
     *
     * @var array
     */
    protected $request_args = array();

    /**
     * Return the arguments and its validation rules for a given endpoint.
     *
     * @var array
     */
    public function get_endpoint_arguments( string $endpoint ): array{

        if ( substr( $endpoint, 0, 1 ) !== '/' ) {
            $endpoint = '/' . $endpoint;
        }

        return $this->request_args[$endpoint] ?? array();
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

        $route_rules = (array) $this->request_args[$request->get_route()];

        /** if no rules are found pass the validation (cannot return error due wordpress standard functionality)*/

        if ( empty( $route_rules ) ) {
            return true;
        }

        $field_rules = (array) $route_rules[$field];

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

        $route_rules = (array) $this->request_args[$request->get_route()];

        /** if no rules are found return the value (cannot return error due wordpress standard functionality)*/

        if ( empty( $route_rules ) ) {
            return $field_value;
        }

        $field_rules = $route_rules[$field];

        return $this->sanitize( $field_value, $field_rules["type"] );
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

        return true;

    }

}
