<?php

namespace Commerce\Backend\App\Services\Logger;

interface LoggerServiceInterface {
    public function log_error( array $errorData = array() );
}
