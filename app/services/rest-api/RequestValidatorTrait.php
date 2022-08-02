<?php

namespace Commerce\App\Services\RestApi;

trait RequestValidatorTrait {

    /**
     * Check if the given parameter is set and not empty.
     *
     * @param array $fields
     * @param \WP_REST_Request $request
     * @param string $operation
     * @return null | array $missing_fields
     */
    public function validate_required_fields( array $fields, \WP_REST_Request $request ) {

        $missing_fields = array();

        foreach ( $fields as $field => $details ) {

            if ( $details['required'] ) {

                if ( !$request->get_param( $field ) ) {
                    $missing_fields[] = $field;
                }

            }

        }

        return empty( $missing_fields ) ? null : $missing_fields;

    }

}
