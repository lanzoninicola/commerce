<?php

namespace Commerce\Backend\App\Traits;

trait SanitizerTrait {

    /**
     * Returns the array of sanitizes data.
     *
     * @param array $rules Key / value array of rules. Eg. ['name' => 'string', 'email' => 'email']. The key is the field name and the value is the type that determines how the field is sanitized.
     * @param array $data Key / value array of data. Eg. ['name' => 'John', 'email' => 'john.doe@gmail.com']
     * @return array of sanitized strings
     */
    public function sanitize( array $rules, array $data ): array{

        if ( empty( $rules ) ) {
            throw new \Exception( 'No rules has been provided.' );
        }

        if ( count( $rules ) !== count( $data ) ) {
            throw new \Exception( 'The number of rules and data must be the same.' );
        }

        if ( count( array_diff_key( $rules, $data ) ) ) {
            throw new \Exception( 'The rules and data must have the same keys.' );
        }

        $sanitized_fields = array();

        var_dump( $data );

        foreach ( $rules as $field => $rule_details ) {

            $value_to_sanitize = $data[$field];

            var_dump( $rule_details['type'], $data[$field] );

            if ( $rule_details['type'] === 'string' ) {
                $sanitized_fields[$field] = $this->sanitize_string( $value_to_sanitize );
            }

// if ( $rule_details['type'] === 'int' ) {

//     $sanitized_fields[$field] = $this->sanitize_int( $value_to_sanitize );

// }

// if ( $rule_details['type'] === 'bool' || $rule_details['type'] === 'boolean' ) {

//     $sanitized_fields[$field] = $this->sanitize_boolean( $value_to_sanitize );

// }

// if ( $rule_details['type'] === 'datetime' ) {

//     $sanitized_fields[$field] = $this->sanitize_datetime( $value_to_sanitize );

// }

// if ( $rule_details['type'] === 'email' ) {

//     $sanitized_fields[$field] = sanitize_email( $value_to_sanitize );
            // }

            $sanitized_fields[$field] = $value_to_sanitize;
        }

        return $sanitized_fields;

    }

    /**
     * Sanitize the given string.
     *
     * @param string $field The field to sanitize.
     * @return string
     */
    private function sanitize_string( string $value ): string {

        return sanitize_text_field( $value );
    }

    /**
     * Sanitize the given integer.
     *
     * @param string $field The field to sanitize.
     * @return int
     */
    private function sanitize_int( string $value ): int {

        return intval( $value );
    }

    /**
     * Sanitize the given boolean.
     *
     * @param string $field The field to sanitize.
     * @return bool
     */
    private function sanitize_boolean( string $value ): bool {

        return (bool) $value;
    }

    /**
     * Sanitize the given date.
     *
     * @param string $field The field to sanitize.
     * @return string
     */
    private function sanitize_datetime( $value ): string {

        return date( 'Y-m-d H:i:s', strtotime( $value ) );
    }

}
