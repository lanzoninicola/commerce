<?php

namespace Commerce\Backend\Modules\Api\V1\Controllers;

use Commerce\Backend\App\Services\Database\DatabaseQuery;
use Commerce\Backend\Modules\Api\V1\Repositories\OnboardingRepository;

class OnboardingControllerFactory {

    /**
     * @param \Commerce\Backend\App\Services\Database\DatabaseQueryInterface $query_service
     * @return OnboardingController
     */
    public static function create() {

        $query_service = DatabaseQuery::singletone();

        $repository = new OnboardingRepository( $query_service );
        return OnboardingController::singletone( $repository );
    }
}