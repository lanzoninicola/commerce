<?php

namespace Commerce\Core;

use Commerce\App\Services\RestApi\RestApiRoutesService;
use Commerce\App\Services\ScriptLocalizer\ScriptAdminLocalizerService;
use Commerce\App\Services\ScriptLocalizer\ScriptPublicLocalizerService;

interface PluginConfigurable {

    /**
     * Adding the plugin menu to Wordpress admin menu
     *
     */
    public function add_plugin_menu();

    /**
     * Adding the shortcodes to Wordpress
     *
     */
    public function define_shortcodes( ShortcodesLoader $shortcodes_loader );

    /**
     * Defines all the scripts/styles that will be used in the plugin.
     *
     * The benefit of this method is that it allows to define the scripts/styles in a single place,
     * and controls the version of the scripts/styles.
     *
     * @return void
     */
    public function define_scripts( ScriptsEnqueuer $scripts_enqueuer );

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   public
     * @since    1.0.0
     */
    public function define_admin_hooks( HooksLoader $hooks_loader );

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access   public
     * @since    1.0.0
     */
    public function define_public_hooks( HooksLoader $hooks_loader );

    /**
     * Define the data to be localized on the frontend html page as Javascript object.
     * Default object name is: ${pluginName}Localized
     *
     * @return void
     */
    public function define_localized_script( ScriptAdminLocalizerService $script_admin_localizer, ScriptPublicLocalizerService $script_public_localizer );

    /**
     * Define the rest api routes
     *
     * 1. create an array of enpoints - RestApiEndpoint[]
     * 2. create the RestApiRoutes object passing the root_path, the api version, the array of endpoints to it
     * 3. register the routes with the rest api - $this->routes_service->add_routes( RestApiRoutes $routes )
     *
     * @return void
     */
    public function define_rest_api_routes( RestApiRoutesService $routes_service );

}
