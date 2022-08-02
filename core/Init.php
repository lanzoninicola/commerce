<?php

namespace Commerce\Core;

use Commerce\App\Services\RestApi\RestApiRoutesService;
use Commerce\App\Services\ScriptLocalizer\ScriptAdminLocalizerService;
use Commerce\App\Services\ScriptLocalizer\ScriptPublicLocalizerService;
use Commerce\Core\I18n;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Commerce
 * @subpackage Commerce/plugin-core
 *
 * @author     Lanzoni Nicola <lanzoni.nicola@gmail.com>
 *
 * @since      1.0.0
 */
class Init {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @access   public
     * @var HooksLoader $hooks_loader Maintains and registers all hooks for the plugin.
     * @since    1.0.0
     */
    public $hooks_loader;

    /**
     * The enqueuer responsible for adding the JavaScript and CSS files to Wordpress queue.
     *
     * @access public
     * @var ScriptsEnqueuer $enqueuer Enqueues the JavaScript and CSS files.
     */
    public $scripts_enqueuer;

    /**
     * The class responsible for defining the shortcodes
     *
     * @access public
     * @var ShortcodesLoader $shortcodes_loader
     */
    public $shortcodes_loader;

    /**
     * The class responsible for defining the REST-API routes.
     *
     * @since    1.0.0
     * @access   public
     * @var      RestApiRoutesService $routes_service
     */
    public $routes_service;

    /**
     * The class responsible for localized admin script into HTML.
     *
     * @since    1.0.0
     * @access   public
     * @var      ScriptAdminLocalizerService $rest_api_endpoint
     */
    public $script_admin_localizer;

    /**
     * The class responsible for localized public script into HTML.
     *
     * @since    1.0.0
     * @access   public
     * @var      ScriptPublicLocalizerService $rest_api_endpoint
     */
    public $script_public_localizer;

    /**
     * The configurator responsible for configuring the plugin.
     *
     * @var PluginConfigurable
     */
    public $configurator;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct( PluginConfigurable $configurator ) {

        $this->load_services();
        $this->set_locale();

        $this->configurator = $configurator;

    }

    /**
     * Load the services.
     *
     * @since    1.0.0
     */
    public function load_services() {

        $this->hooks_loader            = new HooksLoader();
        $this->scripts_enqueuer        = new ScriptsEnqueuer();
        $this->shortcodes_loader       = new ShortcodesLoader();
        $this->routes_service          = new RestApiRoutesService();
        $this->script_admin_localizer  = ScriptAdminLocalizerService::singletone();
        $this->script_public_localizer = ScriptPublicLocalizerService::singletone();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @access   private
     * @since    1.0.0
     */
    public function set_locale() {

        $plugin_i18n = new I18n();

        $this->hooks_loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    public function run_configuration() {

        $this->configurator->define_shortcodes( $this->shortcodes_loader );
        $this->configurator->define_scripts( $this->scripts_enqueuer );
        $this->configurator->define_admin_hooks( $this->hooks_loader );
        $this->configurator->define_public_hooks( $this->hooks_loader );
        $this->configurator->define_localized_script( $this->script_admin_localizer, $this->script_public_localizer );
        $this->configurator->define_rest_api_routes( $this->routes_service );
    }

    /**
     * Run:
     * 1. The configuration of the plugin.
     * 2. The Shortcodes loader to instanciate the shortcodes classes and register the shortcode.
     * 3. The Scripts enqueuer to execute all of the hooks related to javascript and css files.
     * 4. The hooks loader to execute all of the hooks with WordPress.
     * 5. The RestApiRoutes service to register the routes for the rest api.
     *
     * @since    1.0.0
     */
    public function run() {

        $this->run_configuration();

        $this->shortcodes_loader->run();

        $this->scripts_enqueuer->run();

        $this->hooks_loader->run();

        $this->routes_service->run();
    }

}
