<?php

namespace Commerce\App\Services\Database;

/**
 * Class responsible for handling database responses.
 *
 * Shape of the response:
 * - **CODE**: string    (success, error, warning)
 * - **MESSAGE**: string (message to be displayed)
 * - **PAYLOAD**: mixed  (* *optional* * payload)
 *
 * Class DatabaseResponse
 * @package Commerce\App\Services\Database
 */
class DatabaseResponse {

    /**
     * @var string $code (success, error, warning)
     */
    private $code = 'success';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var mixed
     */
    private $payload = null;

    /**
     * DatabaseResponse constructor.
     *
     * @param string $code
     * @param string $message
     * @param mixed $payload
     */
    public function __construct( string $code, string $message, $payload = null ) {

        if ( !in_array( $code, array( 'success', 'error', 'warning' ) ) ) {
            throw new \Exception( 'Invalid code' );
        }

        $this->code    = $code;
        $this->message = $message;
        $this->payload = $payload;
    }

    /**
     * Return the code of the response: success, warning, error.
     *
     * @return string
     */
    public function get_code(): string {
        return $this->code;
    }

    /**
     * Return the message of the response.
     *
     * @return string
     */
    public function get_message(): string {
        return $this->message;
    }

    /**
     * Return the payload of the response.
     * The payload can be any type of data.
     *
     * @return array|null
     */
    public function get_payload() {
        return $this->payload;
    }

    /**
     * Set the code of the response: success, warning, error.
     *
     * @param string $code
     */
    protected function set_code( string $code ): void {
        $this->code = $code;
    }

    /**
     * Set the message of the response.
     *
     * @param string $message
     */
    protected function set_message( string $message ): void {
        $this->message = $message;
    }

    /**
     * Set the payload of the response.
     *
     * @param mixed $payload
     */
    public function set_payload( $payload ): void {
        $this->payload = $payload;
    }

    /**
     * Return the response as an array.
     *
     * @return array
     */
    public function to_array(): array{
        return array(
            'code'    => $this->code,
            'message' => $this->message,
            'payload' => $this->payload,
        );
    }

}
