<?php

namespace Commerce\App\Services\RestApi;

interface RestApiSanitizerMiddlewareInterface {

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

}