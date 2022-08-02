<?php

namespace Commerce\App\Services\RestApi;

class RestApiResponseError {

    public static function error( string $message, array $data, int $status = 500 ) {

        $error_data = array_merge( $data, array(
            'status' => $status,
        ) );

        return new \WP_Error( 'error', $message, $error_data );

    }

    public static function missing_parameter( string $parameter, string $operation, $payload = null ) {

        return self::error( "Missing parameter - Expected '$parameter' parameter", array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 400 );

    }

    public static function missing_multi_parameters( array $parameters, string $operation, $payload = null ) {

        $parameters_str = implode( ', ', $parameters );
        return self::error( "Missing parameter - Expected '$parameters_str' parameter", array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 400 );

    }

    public static function invalid_parameter( string $parameter, string $operation, $payload = null ) {

        return self::error( "Invalid parameter - '$parameter' is not in the expected format", array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 400 );

    }

    public static function database_error( string $message, string $operation, $payload = null ) {

        return self::error( $message, array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 500 );

    }

    public static function database_records_not_affected( string $message, string $operation, $payload = null ) {

        return self::error( $message, array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 400 );

    }

    public static function database_record_not_found( string $message, string $operation, $payload = null ) {

        return self::error( $message, array(
            'operation' => $operation,
            'payload'   => $payload,
        ), 404 );

    }

}
