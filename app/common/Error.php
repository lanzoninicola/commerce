<?php

namespace Commerce\App\Common;

/**
 * Abstraction of the Wordpress error class.
 */
class Error extends \WP_Error {

    public function __construct( $code = '', $message = '', $data = '' ) {
        parent::__construct( $code, $message, $data );
    }

    public function code() {
        return $this->get_error_code();
    }

    public function message() {
        return $this->get_error_message();
    }

    public function data() {
        return $this->get_error_data() ?? array();
    }

}
