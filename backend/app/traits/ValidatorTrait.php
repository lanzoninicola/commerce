<?php

namespace Commerce\Backend\App\Traits;

use Commerce\Backend\App\Common\Error;

trait ValidatorTrait {

    /**
     * Validate the request arguments value based on the rules.
     *
     * @param array $value
     * @param array $rules
     * @return boolean
     */
    public function validate( $value, array $rules ) {

        if ( $rules['required'] && empty( $value ) ) {
            return false;
        }

        return true;

    }

}
