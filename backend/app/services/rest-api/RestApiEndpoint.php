<?php

namespace Commerce\Backend\App\Services\RestApi;

class RestApiEndpoint {

    private $verbs = array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' );

    public function __construct( string $endpoint, string $verb, array $callback, string $capability ) {

        if ( !in_array( $verb, $this->verbs ) ) {
            throw new \Exception( 'Invalid verb.' );
        }

        $this->endpoint   = $endpoint;
        $this->verb       = $verb;
        $this->callback   = $callback;
        $this->capability = $capability;

    }

    public function endpoint(): string {
        return $this->endpoint;
    }

    public function verb(): string {
        return $this->verb;
    }

    public function callback(): array{
        return $this->callback;
    }

    public function capability(): string {
        return $this->capability;
    }

}
