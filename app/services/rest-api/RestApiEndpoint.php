<?php

namespace Commerce\App\Services\RestApi;

class RestApiEndpoint {

    private $verbs = array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' );

    /**
     * Describe the endpoint.
     *
     * @param string $url The URL of the endpoint.
     * @param string $verb The verb of the endpoint.
     * @param string $callback The callback of the endpoint.
     * @param string $capability The capability of the endpoint.
     * @param string $guard_object The instance of the RestApiEndpointGuard class for validationa and sanitization.
     */
    public function __construct( string $url, string $verb, array $callback, string $capability, RestApiEndpointGuard $guard_object ) {

        if ( !in_array( $verb, $this->verbs ) ) {
            throw new \Exception( 'Invalid verb.' );

        }

        if ( empty( $guard_object ) ) {
            throw new \Exception( 'Guard not set.' );
        }

        $this->url          = $url;
        $this->verb         = $verb;
        $this->callback     = $callback;
        $this->capability   = $capability;
        $this->guard_object = $guard_object;

    }

    /**
     * Returns the url.
     *
     * @return string
     */
    public function url(): string {
        return $this->url;
    }

    /**
     * The verb of the endpoint.
     *
     * @return string
     */
    public function verb(): string {
        return $this->verb;
    }

    /**
     * Returns the callback array. The callback array is an array of two elements: [class, method].
     * The method is run when the endpoint is called.
     *
     * @return array
     */
    public function callback(): array{
        return $this->callback;
    }

    /**
     * Returns the capability required to access this endpoint.
     *
     * @return string
     */
    public function capability(): string {
        return $this->capability;
    }

    /**
     * Returns the name of the guard class that contains the methods
     * to run to validation and sanitization of the request arguments.
     *
     * @return object
     */
    public function guard_object(): object {
        return $this->guard_object;
    }

}
