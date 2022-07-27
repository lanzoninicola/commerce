<?php

namespace Clockdown\Backend\App\Services\Logger;

interface LoggerServiceInterface {
    public function log_error( array $errorData = array() );
}
