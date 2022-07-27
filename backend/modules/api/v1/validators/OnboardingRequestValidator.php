<?php

namespace Commerce\Backend\Modules\Api\V1\Validators;

use Commerce\Backend\App\Services\RestApi\RequestValidatorTrait;

class OnboardingRequestValidator {

    private $request_fields = array(
        'fullname'           => array(
            'required' => true,
            'type'     => 'string',
        ),
        'email'              => array(
            'required' => true,
            'type'     => 'string',
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
    );

    use RequestValidatorTrait;

    public function validate_on_create( \WP_REST_Request $request ) {

        $this->validate_required_fields(
            $this->request_fields,
            $request
        );

    }

    public function getValidationErrors() {
        return $this->validationErrors;
    }

}