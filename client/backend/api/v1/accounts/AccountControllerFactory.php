<?php

namespace Commerce\Client\Backend\Api\V1\Accounts;

use Commerce\App\Services\Database\DatabaseQuery;

class AccountControllerFactory {

    /**
     * @param \Commerce\App\Services\Database\DatabaseQueryInterface $query_service
     * @return AccountController
     */
    public static function create() {

        $query_service = DatabaseQuery::singletone();

        $repository = new AccountRepository( $query_service );
        return AccountController::singletone( $repository );
    }
}