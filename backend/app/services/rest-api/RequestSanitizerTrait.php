<?php

namespace Commerce\Backend\App\Services\RestApi;

trait RequestSanitizerTrait {

    /**
     * Returns the array of sanitizes data.
     *
     * @param array $fields The fields to sanitize in the format array( field_name => type).
     * @return array of sanitized strings
     */
    public function bulk_sanitize( array $fields, \WP_REST_Request $request ): array{

        if ( empty( $fields ) ) {
            throw new \Exception( 'The array of fields is empty.' );
        }

        $sanitized_fields = array();

        foreach ( $fields as $field => $details ) {

            $value = $request->get_param( $field );

            if ( $details['type'] === 'string' ) {
                $sanitized_fields[$field] = $this->sanitize_string( $value );
            }

            if ( $details['type'] === 'int' ) {
                $sanitized_fields[$field] = $this->sanitize_int( $value );
            }

            if ( $details['type'] === 'bool' || $details['type'] === 'boolean' ) {
                $sanitized_fields[$field] = $this->sanitize_boolean( $value );
            }

            if ( $details['type'] === 'datetime' ) {
                $sanitized_fields[$field] = $this->sanitize_datetime( $value );
            }

            $sanitized_fields[$field] = $value;
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
