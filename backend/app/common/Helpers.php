<?php

namespace Commerce\Backend\App\Common;

class Helpers {

    /**
     * Check if the array given is associative.
     *
     * @param array $array
     * @return boolean
     */
    public static function is_associative_array( array $array ) {
        return (bool) count( array_filter( array_keys( $array ), 'is_string' ) );
    }
}