<?php

namespace Commerce\App\Services\RestApi;

class RestApiValidatorMiddleware extends BaseValidationAndSanitizerMiddleware implements RestApiValidatorMiddlewareInterface {

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

        return $this->validate( $field_value, $field_rules );

    }

    /**
     * Validate the request arguments value based on the rules.
     *
     * @param array $value
     * @param array $rules
     * @return boolean
     */
    public function validate( $value, array $rules ): bool {

// 'type' validation
        if ( !empty( $rules['type'] )
            && $this->get_type_from_rules( $rules ) !== gettype( $value )
        ) {
            return false;
        }

        // if no rules are found pass the validation
        return true;

    }

}
