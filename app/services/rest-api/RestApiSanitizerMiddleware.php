<?php

namespace Commerce\App\Services\RestApi;

use Commerce\App\Traits\SanitizerTrait;

class RestApiSanitizerMiddleware extends BaseValidationAndSanitizerMiddleware implements RestApiSanitizerMiddlewareInterface {

    use SanitizerTrait;

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
        $type        = $this->get_type_from_rules( $field_rules, true );

        return $this->sanitize( $field_value, $type );
    }

}
