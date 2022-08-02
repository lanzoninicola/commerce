<?php

namespace Commerce\App\Services\RestApi;

class RestApiResponseSuccess {

    public static function success( string $message, array $data ) {

        $success_data = array(
            'code'    => 'success',
            'message' => $message,
            'data'    => array_merge( $data, array(
                'status' => 200,
            ) ),
        );

        return new \WP_REST_Response( $success_data, 200 );

    }

    public static function database_records_empty( string $message, string $operation ) {

        return self::success( $message, array(
            'operation' => $operation,
            'payload'   => array(),
        ) );

    }

}
