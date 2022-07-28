<?php

namespace Commerce\Backend\App\Services\RestApi;

interface RestApiEndpointGuardInterface {

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @param $field_value is the value of the request argument
     * @param $request is the request object
     * @param $field is the name of the request argument
     * @return boolean true if the request argument is valid, false otherwise
     */
    public function validate_request_arg( $field_value, $request, $field ): bool;

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @param $field_value is the value of the request argument
     * @param $request is the request object
     * @param $field is the name of the request argument
     * @return mixed the sanitized value of the request argument
     */
    public function sanitize_request_arg( $field_value, $request, $field );

    /**
     * https: //developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
     * validate_callback: Used to pass a function that will be passed the value of the argument.
     * That function should return true if the value is valid, and false if not
     *
     * @param array $value
     * @param array $rules
     * @return boolean
     */
    public function validate( $value, array $rules ): bool;

}