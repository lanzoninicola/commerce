<?php

namespace Commerce\App\Services\Database;

/**
 * Response for empty result.
 */
class DatabaseResponseEmpty extends DatabaseResponse {

    public function __construct( string $message = 'No records were found', $payload = null ) {

        parent::__construct( 'warning', $message, $payload );

    }

}
