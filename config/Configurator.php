<?php

namespace Commerce\Config;

use Commerce\App\Services\RestApi\RestApiEndpoint;
use Commerce\App\Services\RestApi\RestApiEndpointGuard;
use Commerce\App\Services\RestApi\RestApiRoutes;
use Commerce\Client\Backend\Api\V1\Accounts\AccountControllerFactory;
use Commerce\Client\Backend\Api\V1\Analytics\AnalyticsControllerFactory;
use Commerce\Client\Backend\Api\V1\Onboarding\OnboardingControllerFactory;
use Commerce\Core\ServiceProvider;

class Configurator {

    public function __construct( ServiceProvider $service_provider ) {

        $this->service_provider = $service_provider;

        $this->define_shortcodes();
        $this->define_scripts();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_localized_script();
        $this->define_rest_api_routes();

    }

    /**
     * Adding the plugin menu to Wordpress admin menu
     *
     */
    public function add_plugin_menu() {
    }

    /**
     * Adding the shortcodes to Wordpress
     *
     */
    private function define_shortcodes() {

    }

    /**
     * Defines all the scripts/styles that will be used in the plugin.
     *
     * The benefit of this method is that it allows to define the scripts/styles in a single place,
     * and controls the version of the scripts/styles.
     *
     * @return void
     */
    private function define_scripts() {

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   private
     * @since    1.0.0
     */
    private function define_admin_hooks() {

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access   private
     * @since    1.0.0
     */
    private function define_public_hooks() {

    }

    /**
     * Define the data to be localized on the frontend html page as Javascript object.
     * Default object name is: ${pluginName}Localized
     *
     * @return void
     */
    private function define_localized_script() {

        $this->service_provider->script_admin_localizer->localize(
            array(
                'apiURL'   => home_url( '/wp-json' ),
                'language' => get_locale(),
            )
        );

    }

    /**
     * Define the rest api routes
     *
     * 1. create an array of enpoints - RestApiEndpoint[]
     * 2. create the RestApiRoutes object passing the root_path, the api version, the array of endpoints to it
     * 3. register the routes with the rest api - $this->routes_service->add_routes( RestApiRoutes $routes )
     *
     * @return void
     */
    private function define_rest_api_routes() {

        $endpoints_v1 = array(
            new RestApiEndpoint( '/analytics/installations/(?P<installation_id>[a-zA-Z0-9-]+)', 'GET',
                array( AnalyticsControllerFactory::create(), 'find_by_product_installation_id' ),
                'public',
                new RestApiEndpointGuard()
            ),
            new RestApiEndpoint( '/analytics/installations', 'POST',
                array( AnalyticsControllerFactory::create(), 'new_product_installation' ),
                'public',
                new RestApiEndpointGuard( array(
                    'product_id'      => array(
                        'required' => true,
                        'type'     => 'integer',
                    ),
                    'installation_id' => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                    'site_url'        => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                    'site_language'   => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                    'site_timezone'   => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                ) )
            ),
            new RestApiEndpoint( '/onboarding', 'POST',
                array( OnboardingControllerFactory::create(), 'new_onboarding' ),
                'public',
                new RestApiEndpointGuard( array(
                    'fullname'           => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                    'email'              => array(
                        'required' => true,
                        'type'     => 'string:email',
                    ),
                    'consent_newsletter' => array(
                        'required' => true,
                        'type'     => 'boolean',
                    ),
                    'consent_privacy'    => array(
                        'required' => true,
                        'type'     => 'boolean',
                    ),
                    'consent_terms'      => array(
                        'required' => true,
                        'type'     => 'boolean',
                    ),
                    'product_id'         => array(
                        'required' => true,
                        'type'     => 'integer',
                    ),
                    'installation_id'    => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                ) )
            ),
            new RestApiEndpoint( '/account', 'GET',
                array( AccountControllerFactory::create(), 'get_account' ),
                'public',
                new RestApiEndpointGuard( array(
                    'email' => array(
                        'required' => true,
                        'type'     => 'string:email',
                    ),
                ) )
            ),
        );

        $routes = new RestApiRoutes( 'commerce', 'v1', $endpoints_v1 );

        $this->service_provider->routes_service->add_routes( $routes );

    }

}
