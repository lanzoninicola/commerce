<?php

namespace Commerce\App\Traits;

use Commerce\App\Common\Error;

trait SanitizerTrait {

    /**
     * Sanitize the given array of data using the given sanitization rules.
     *
     * @param array $rules Key / value array of rules. Eg. ['name' => 'string', 'email' => 'email']. The key is the field name and the value is the type that determines how the field is sanitized.
     * @param array $data Key / value array of data. Eg. ['name' => 'John', 'email' => 'john.doe@gmail.com']
     * @return Error | array of sanitized strings
     */
    public function bulk_sanitize( array $rules, array $data ) {

        if ( empty( $rules ) ) {
            return new Error(
                'no_rules',
                'SanitizerTrait - sanitize() - No rules has been provided.'
            );
        }

        if ( count( $rules ) !== count( $data ) ) {
            return new Error(
                'mismatching_rules',
                'SanitizerTrait - sanitize() - The number of rules and data must be the same.'
            );
        }

        if ( count( array_diff_key( $rules, $data ) ) ) {
            return new Error(
                'invalid_data',
                'SanitizerTrait - required_fields() - The rules and data must have the same keys.'
            );
        }

        $sanitized_fields = array();

        foreach ( $rules as $field => $rule_details ) {

            $value_to_sanitize        = $data[$field];
            $data_type                = $rule_details['type'];
            $sanitized_fields[$field] = $this->sanitize( $value_to_sanitize, $data_type );

        }

        return $sanitized_fields;

    }

    /**
     * Depending on the data type, sanitize the value.
     *
     * @param mixed $value  The value to sanitize.
     * @param string $type The data type.
     * @return void
     */
    public function sanitize( $value, string $type ) {

        if ( $type === 'string' ) {
            return $this->sanitize_string( $value );
        } else

        if ( $type === 'int' || $type === 'integer' || $type === 'number' || $type === 'numeric' ) {
            return $this->sanitize_int( $value );
        } else

        if ( $type === 'bool' || $type === 'boolean' ) {
            return $this->sanitize_boolean( $value );
        } else

        if ( $type === 'datetime' ) {
            return $this->sanitize_datetime( $value );
        } else

        if ( $type === 'email' ) {
            return sanitize_email( $value );
        } else

        if ( $type === 'json_encode' ) {
            return $this->sanitize_json( $value, 'encode' );
        } else

        if ( $type === 'json_decode' ) {
            return $this->sanitize_json( $value, 'decode' );
        } else {

            return $value;
        }

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

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @param string $action Either 'encode' or 'decode'.
     * @return void
     */
    private function sanitize_json( $value, string $action ) {

        if ( $action === 'encode' ) {
            return json_encode( $value );
        }

        return json_decode( $value );

    }

}
