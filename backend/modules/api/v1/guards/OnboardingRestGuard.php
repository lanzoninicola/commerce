<?php

namespace Commerce\Backend\Modules\Api\V1\Guards;

use Commerce\Backend\App\Services\RestApi\BaseRestApiEndpointGuard;
use Commerce\Backend\App\Services\RestApi\RestApiEndpointGuardInterface;

class OnboardingRestGuard extends BaseRestApiEndpointGuard implements RestApiEndpointGuardInterface {

    /**
     * Array of arguments and validation/sanitization rule for each endpoint.
     *
     * array( $route => array( $arg => $rules[]))
     *
     * @var array
     */
    protected $request_args = array(
        '/commerce/v1/onboarding'             => array(
            'fullname'           => array(
                'required' => true,
                'type'     => 'string',
            ),
            'email'              => array(
                'required' => true,
                'type'     => 'email',
            ),
            'consent_newsletter' => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'consent_privacy'    => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'consent_terms'      => array(
                'required' => false,
                'type'     => 'boolean',
            ),
            'installation_id'    => array(
                'required' => false,
                'type'     => 'string',
            ),
            'site_language'      => array(
                'required' => false,
                'type'     => 'string',
            ),
            'timezone'           => array(
                'required' => false,
                'type'     => 'string',
            ),
        ),
        "/commerce/v1/onboarding/(?P<id>\d+)" => array(
            // 'id'                 => array(
            //     'required' => true,
            //     'type'     => 'number',
            // ),
            'fullname' => array(
                'required' => true,
                'type'     => 'string',
            ),

        ),
    );

}
