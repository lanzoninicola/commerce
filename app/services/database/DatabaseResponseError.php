<?php

namespace Commerce\App\Services\Database;

/**
 * Response for error.
 */
class DatabaseResponseError extends DatabaseResponse {

    public function __construct( string $message = 'Database error', $payload = null ) {

        parent::__construct( 'error', $message, $payload );

    }

}
