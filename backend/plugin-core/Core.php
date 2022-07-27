<?php

namespace Clockdown\Backend\PluginCore;

use Clockdown\Backend\App\Services\RestApi\RestApiEndpoint;
use Clockdown\Backend\App\Services\RestApi\RestApiRoutes;
use Clockdown\Backend\App\Services\RestApi\RestApiRoutesService;
use Clockdown\Backend\App\Services\ScriptLocalizer\ScriptAdminLocalizerService;
use Clockdown\Backend\App\Services\ScriptLocalizer\ScriptPublicLocalizerService;
use Clockdown\Backend\Modules\Api\V1\Factories\ControllersFactory;
use Clockdown\Backend\Modules\CountdownWidget\CountdownWidgetShortcode;
use Clockdown\Backend\Modules\TemplatesEditor\TemplatesEditor;
use Clockdown\Backend\PluginCore\I18n;
use function Clockdown\Backend\App\Functions\add_menu;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Clockdown
 * @subpackage Clockdown/plugin-core
 *
 * @author     Lanzoni Nicola <lanzoni.nicola@gmail.com>
 *
 * @since      1.0.0
 */
class Core {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @access   protected
     * @var HooksLoader $hooks_loader Maintains and registers all hooks for the plugin.
     * @since    1.0.0
     */
    protected $hooks_loader;

    /**
     * The enqueuer responsible for adding the JavaScript and CSS files to Wordpress queue.
     *
     * @access protected
     * @var ScriptsEnqueuer $enqueuer Enqueues the JavaScript and CSS files.
     */
    protected $scripts_enqueuer;

    /**
     * The class responsible for defining the shortcodes
     *
     * @access protected
     * @var ShortcodesLoader $shortcodes_loader
     */
    protected $shortcodes_loader;

    /**
     * The class responsible for defining the REST-API routes.
     *
     * @since    1.0.0
     * @access   protected
     * @var      RestApiRoutesService $routes_service
     */
    protected $routes_service;

    /**
     * The class responsible for localized admin script into HTML.
     *
     * @since    1.0.0
     * @access   protected
     * @var      ScriptAdminLocalizerService $rest_api_endpoint
     */
    protected $script_admin_localizer;

    /**
     * The class responsible for localized public script into HTML.
     *
     * @since    1.0.0
     * @access   protected
     * @var      ScriptPublicLocalizerService $rest_api_endpoint
     */
    protected $script_public_localizer;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->load_services();
        $this->set_locale();
        $this->define_shortcodes();
        $this->define_scripts();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_localized_script();
        $this->define_rest_api_routes();

    }

    /**
     * Load the services.
     *
     * @since    1.0.0
     */
    protected function load_services() {

        $this->hooks_loader            = new HooksLoader();
        $this->scripts_enqueuer        = new ScriptsEnqueuer();
        $this->shortcodes_loader       = new ShortcodesLoader();
        $this->routes_service          = new RestApiRoutesService();
        $this->script_admin_localizer  = ScriptAdminLocalizerService::singletone();
        $this->script_public_localizer = ScriptPublicLocalizerService::singletone();
    }

    /**
     * Adding the plugin menu to Wordpress admin menu
     *
     */
    public function add_plugin_menu() {
        add_menu( 'Clockdown', 'clockdown' );
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
    private function set_locale() {

        $plugin_i18n = new I18n();

        $this->hooks_loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Adding the shortcodes to Wordpress
     *
     */
    private function define_shortcodes() {

        $this->shortcodes_loader->add(
            'clockdown',
            CountdownWidgetShortcode::class
        );

        $this->shortcodes_loader->add_localize_script(
            'clockdown',
            'clockdownLocalized',
            array(
                'apiURL'   => home_url( '/wp-json' ),
                'language' => get_locale(),
            )
        );

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

        $this->scripts_enqueuer->add_admin_style(
            'templates-editor-style',
            CLOCKDOWN_PLUGIN_BASE_URL_PATH . 'public/templates-editor/assets/index.css',
            array(),
            '1.0.0'
        );

        $this->scripts_enqueuer->add_admin_script(
            'templates-editor-script',
            CLOCKDOWN_PLUGIN_BASE_URL_PATH . 'public/templates-editor/assets/index.js',
            array(),
            '1.0.0',
            false
        );

        $this->shortcodes_loader->add_inline_script(
            'clockdown',
            array(
                'id'  => 'clockdown-widget-script',
                'src' => CLOCKDOWN_PLUGIN_BASE_URL_PATH . 'public/clockdown-widget/assets/index.js',
                'ver' => '1.0.0',
            )
        );

        $this->shortcodes_loader->add_inline_stylesheet(
            'clockdown',
            array(
                'id'   => 'clockdown-widget-style',
                'href' => CLOCKDOWN_PLUGIN_BASE_URL_PATH . 'public/clockdown-widget/assets/index.css',
                'ver'  => '1.0.0',
            )
        );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   private
     * @since    1.0.0
     */
    private function define_admin_hooks() {

        // Adding the plugin menu to Wordpress admin menu
        $this->hooks_loader->add_action( 'admin_menu', $this, 'add_plugin_menu' );

        // Adding the menu in the admin area for the templates editor
        $templates = new TemplatesEditor();
        $this->hooks_loader->add_action( 'admin_menu', $templates, 'add_menu' );

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

        $this->script_admin_localizer->localize(
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

        $countdowns_endpoint   = 'countdowns';
        $countdown_id_endpoint = '/countdowns/(?P<id>\d+)';
        $settings_enpoint      = '/countdowns/(?P<id>\d+)/settings';

        $countdown_controller = ControllersFactory::get_instance_by_class_name( 'CountdownsController' );
        $settings_controller  = ControllersFactory::get_instance_by_class_name( 'CountdownsSettingsController' );

        $endpoints_v1 = array(
            new RestApiEndpoint( $countdowns_endpoint, 'GET',
                array( $countdown_controller, 'find_all' ),
                'public'
            ),
            new RestApiEndpoint( $countdowns_endpoint, 'POST',
                array( $countdown_controller, 'create' ),
                'public'
            ),
            new RestApiEndpoint( $countdown_id_endpoint, 'GET',
                array( $countdown_controller, 'find_by_id' ),
                'public'
            ),
            new RestApiEndpoint( $countdown_id_endpoint, 'PUT',
                array( $countdown_controller, 'update' ),
                'public'
            ),
            new RestApiEndpoint( $countdown_id_endpoint, 'DELETE',
                array( $countdown_controller, 'delete' ),
                'public'
            ),
            new RestApiEndpoint( $settings_enpoint, 'GET',
                array( $settings_controller, 'find_by_id' ),
                'public'
            ),
            new RestApiEndpoint( $settings_enpoint, 'POST',
                array( $settings_controller, 'create' ),
                'public'
            ),
            new RestApiEndpoint( $settings_enpoint, 'PUT',
                array( $settings_controller, 'update' ),
                'public'
            ),
            new RestApiEndpoint( $settings_enpoint, 'DELETE',
                array( $settings_controller, 'delete' ),
                'public'
            ),

        );

        $routes = new RestApiRoutes( 'clockdown', 'v1', $endpoints_v1 );

        $this->routes_service->add_routes( $routes );

    }

    /**
     * Run:
     * 1. The Shortcodes loader to instanciate the shortcodes classes and register the shortcode.
     * 2. The Scripts enqueuer to execute all of the hooks related to javascript and css files.
     * 3. The hooks loader to execute all of the hooks with WordPress.
     * 4. The RestApiRoutes service to register the routes for the rest api.
     *
     * @since    1.0.0
     */
    public function run() {

        $this->shortcodes_loader->run();

        $this->scripts_enqueuer->run();

        $this->hooks_loader->run();

        $this->routes_service->run();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     *
     * @return Clockdown_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->hooks_loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     *
     * @return string The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
