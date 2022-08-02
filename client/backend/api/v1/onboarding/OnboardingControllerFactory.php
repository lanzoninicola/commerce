<?php

namespace Commerce\Client\Backend\Api\V1\Onboarding;

use Commerce\App\Services\Database\DatabaseQuery;

class OnboardingControllerFactory {

    /**
     * @param \Commerce\App\Services\Database\DatabaseQueryInterface $query_service
     * @return OnboardingController
     */
    public static function create() {

        $query_service = DatabaseQuery::singletone();

        $repository = OnboardingRepository::singletone( $query_service );
        $service    = new OnboardingService( $repository );
        return OnboardingController::singletone( $service );
    }
}