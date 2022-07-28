<?php

namespace Commerce\Backend\App\Traits;

use Commerce\Backend\App\Common\Error;

trait ValidatorTrait {

    /**
     * Check if the given parameter is set and not empty.
     *
     * @param array $rules Key / value array of rules. Eg. ['name' => [required => true], 'email' => [required => false]]. The key is the field name and the value is the type that determines how the field is sanitized.
     * @param array $data Key / value array of data. Eg. ['name' => 'John', 'email' => '
     * @return Error | null Returns WP_Error if the request is invalid, null otherwise. The error contains the field name and the error message.
     */
    public function required_fields( array $rules, array $data ) {

        if ( empty( $rules ) ) {
            return new Error( 'no_rules', 'ValidatorTrait - required_fields() - No rules has been provided.' );
        }

        if ( count( $rules ) !== count( $data ) ) {
            return new Error( 'invalid_data', 'ValidatorTrait - required_fields() - The number of rules and data must be the same.' );
        }

        if ( count( array_diff_key( $rules, $data ) ) ) {
            return new Error( 'invalid_data', 'ValidatorTrait - required_fields() - The rules and data must have the same keys.' );
        }

        foreach ( $rules as $field => $rule_details ) {

            if ( $rule_details['required'] === true && !isset( $data[$field] ) ) {
                $missing_fields[] = $field;
            }

            if ( $rule_details['required'] === true && isset( $data[$field] ) && empty( $data[$field] ) ) {
                $missing_fields[] = $field;
            }

        }

        if ( !empty( $missing_fields ) ) {

            return new Error(
                'missing_fields',
                'ValidatorTrait - required_fields() - The following fields are required: ' . implode( ', ', $missing_fields ),
                $missing_fields
            );
        }

        return null;

    }

}
