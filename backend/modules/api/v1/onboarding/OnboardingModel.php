<?php

namespace Commerce\Backend\Modules\Api\V1\Onboarding;

use Commerce\Backend\App\Services\RestApi\BaseModel;

class OnboardingModel extends BaseModel {

    public string $fullname;

    public string $email;

    public bool $consent_newsletter;

    public bool $consent_terms;

    public bool $consent_privacy;

    public int $product_id;

    public string $installation_id;

    public string $site_url;

    public string $site_language;

    public string $site_timezone;

    public function __construct(
        string $fullname,
        string $email,
        bool $consent_newsletter,
        bool $consent_terms,
        bool $consent_privacy,
        int $product_id,
        string $installation_id,
        string $site_url,
        string $site_language,
        string $site_timezone
    ) {

        $this->fullname           = $fullname;
        $this->email              = $email;
        $this->consent_newsletter = $consent_newsletter;
        $this->consent_terms      = $consent_terms;
        $this->consent_privacy    = $consent_privacy;
        $this->product_id         = $product_id;
        $this->installation_id    = $installation_id;
        $this->site_url           = $site_url;
        $this->site_language      = $site_language;
        $this->site_timezone      = $site_timezone;

    }

    /**
     * Convert the model to an array.
     *
     * @param array $exlude_fields Fields to exclude from the array.
     * @return void
     */
    protected function to_array( array $exclude_fields = array() ) {

        $array = array(
            'fullname'           => $this->fullname,
            'email'              => $this->email,
            'consent_newsletter' => $this->consent_newsletter,
            'consent_terms'      => $this->consent_terms,
            'consent_privacy'    => $this->consent_privacy,
            'product_id'         => $this->product_id,
            'installation_id'    => $this->installation_id,
            'site_url'           => $this->site_url,
            'site_language'      => $this->site_language,
            'site_timezone'      => $this->site_timezone,
        );

        return array_diff_key( $array, array_flip( $exclude_fields ) );
    }

    /**
     * Convert the array to the model.
     *
     * @param array $array Array of data.
     * @return array
     */
    protected static function from_array( array $array ) {
        return new OnboardingModel(
            $array['fullname'],
            $array['email'],
            $array['consent_newsletter'],
            $array['consent_terms'],
            $array['consent_privacy'],
            $array['product_id'],
            $array['installation_id'],
            $array['site_url'],
            $array['site_language'],
            $array['site_timezone'],
        );
    }

}