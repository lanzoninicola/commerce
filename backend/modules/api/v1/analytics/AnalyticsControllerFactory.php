<?php

namespace Commerce\Backend\Modules\Api\V1\Analytics;

use Commerce\Backend\App\Services\Database\DatabaseQuery;

class AnalyticsControllerFactory {

    /**
     * @param \Commerce\Backend\App\Services\Database\DatabaseQueryInterface $query_service
     * @return AnalyticsController
     */
    public static function create() {

        $query_service = DatabaseQuery::singletone();

        $repository = AnalyticsRepository::singletone( $query_service );
        $service    = new AnalyticsService( $repository );
        return AnalyticsController::singletone( $service );
    }
}