<?php

namespace Commerce\Backend\App\Services\Database;

class DatabaseResponseSuccess extends DatabaseResponse {

    public function __construct( string $message = 'Database success', $payload = null ) {

        parent::__construct( 'success', $message, $payload );

    }

}
