<?php

namespace Commerce\App\Services\RestApi;

class BaseValidationAndSanitizerMiddleware {

    /**
     * Array of validation/sanitizer rules.
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Set the validation/sanitizer rules for the endpoint.
     *
     * @param array $rules The rules is the collection of the rest api enpoint argument
     * and the validation rules.
     * @return void
     */
    public function set_rules( array $rules = array() ) {
        $this->rules = $rules;
    }

    /**
     * This method is called by RestApiRouteService::register_api_endpoints()
     * and it is executed for each argument of the endpoint.
     *
     * @return array of validation/sanitizer rule.
     */
    public function get_rules(): array{
        return $this->rules;
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
    protected function get_type_from_rules( array $rules, bool $descriptor = false ): string {

        $global_type     = explode( ':', $rules['type'] )[0];
        $type_descriptor = explode( ':', $rules['type'] )[1];

        if ( $descriptor ) {
            return isset( $type_descriptor ) ? $type_descriptor : $global_type;
        }

        return $global_type;

    }

}
