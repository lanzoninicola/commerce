<?php

namespace Commerce\App\Services\Database;

/**
 * Response for not affected records.
 */
class DatabaseResponseNotAffected extends DatabaseResponse {

    public function __construct( string $message = 'No records were affected', $payload = null ) {

        parent::__construct( 'error', $message, $payload );

    }

}
