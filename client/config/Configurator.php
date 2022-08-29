<?php

namespace Commerce\Client\Config;

use Commerce\App\Services\RestApi\RestApiEndpoint;
use Commerce\App\Services\RestApi\RestApiRoutes;
use Commerce\App\Services\RestApi\RestApiRoutesService;
use Commerce\App\Services\ScriptLocalizer\ScriptAdminLocalizerService;
use Commerce\App\Services\ScriptLocalizer\ScriptPublicLocalizerService;
use Commerce\Client\Backend\Api\V1\Accounts\AccountControllerFactory;
use Commerce\Client\Backend\Api\V1\Analytics\AnalyticsControllerFactory;
use Commerce\Client\Backend\Api\V1\Onboarding\OnboardingControllerFactory;
use Commerce\Core\HooksLoader;
use Commerce\Core\PluginConfigurable;
use Commerce\Core\ScriptsEnqueuer;
use Commerce\Core\ShortcodesLoader;

class Configurator implements PluginConfigurable {

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
    public function define_shortcodes( ShortcodesLoader $shortcodes_loader ) {

    }

    /**
     * Defines all the scripts/styles that will be used in the plugin.
     *
     * The benefit of this method is that it allows to define the scripts/styles in a single place,
     * and controls the version of the scripts/styles.
     *
     * @return void
     */
    public function define_scripts( ScriptsEnqueuer $scripts_enqueuer ) {

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   public
     * @since    1.0.0
     */
    public function define_admin_hooks( HooksLoader $hooks_loader ) {

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access   public
     * @since    1.0.0
     */
    public function define_public_hooks( HooksLoader $hooks_loader ) {

    }

    /**
     * Define the data to be localized on the frontend html page as Javascript object.
     * Default object name is: ${pluginName}Localized
     *
     * @return void
     */
    public function define_localized_script( ScriptAdminLocalizerService $script_admin_localizer, ScriptPublicLocalizerService $script_public_localizer ) {

        $script_admin_localizer->localize(
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
    public function define_rest_api_routes( RestApiRoutesService $routes_service ) {

        $endpoints_v1 = array(
            new RestApiEndpoint( '/analytics/installations/(?P<installation_id>[a-zA-Z0-9-]+)', 'GET',
                array( AnalyticsControllerFactory::create(), 'find_by_product_installation_id' ),
                'public',
                array()
            ),
            new RestApiEndpoint( '/analytics/installations', 'POST',
                array( AnalyticsControllerFactory::create(), 'new_product_installation' ),
                'public',
                array(
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
                )
            ),
            new RestApiEndpoint( '/onboarding', 'POST',
                array( OnboardingControllerFactory::create(), 'new_onboarding' ),
                'public',
                array(
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
                    ) )
            ),
            new RestApiEndpoint( '/onboarding', 'GET',
                array( OnboardingControllerFactory::create(), 'should_onboarding_required' ),
                'public',
                array(
                    'email' => array(
                        'required' => true,
                        'type'     => 'string:email',
                    ),
                )
            ),
            new RestApiEndpoint( '/account', 'GET',
                array( AccountControllerFactory::create(), 'get_account' ),
                'public',
                array(
                    'email' => array(
                        'required' => true,
                        'type'     => 'string:email',
                    ),
                )
            ),
        );

        $routes = new RestApiRoutes( 'commerce', 'v1', $endpoints_v1 );

        $routes_service->add_routes( $routes );

    }

}
