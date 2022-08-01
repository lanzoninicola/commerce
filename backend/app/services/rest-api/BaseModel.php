<?php

namespace Commerce\Backend\App\Services\RestApi;

abstract class BaseModel {

    /**
     * Convert the model to an array.
     *
     * @param array $exlude_fields Fields to exclude from the array.
     * @return void
     */
    abstract protected function to_array( array $exclude_fields = array() );

    /**
     * Convert the array to the model.
     *
     * @param array $array Array of data.
     * @return array
     */
    abstract protected static function from_array( array $array );

    public static function from_json( string $json ) {
        return self::from_array( json_decode( $json, true ) );
    }

    public function to_json(): string {
        return json_encode( $this->to_array() );
    }
}