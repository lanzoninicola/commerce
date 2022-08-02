<?php

namespace Commerce\App\Services\RestApi;

/**
 * Class responsible for registering the routes for custom REST-API.
 *
 * @method void add_routes()
 * @method void run()
 */
class RestApiRoutesService {

    /**
     * The active routes.
     *
     * @var RestApiRoutes[]
     */
    private $routes = array();

    /**
     * Adds a new RestApiRoutes collection to the collection of routes.
     *
     * @param RestApiRoutes $route The route to be added.
     */
    public function add_routes( RestApiRoutes $routes ) {

        $this->routes[] = $routes;

    }

    /**
     * Add the wordpress action to register the routes.
     *
     * @return void
     */
    public function run() {

        add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
    }

    /**
     * Register the routes for the custom REST-API.
     *
     * @param string $root_path The root path of the plugin.
     * @param string $api_version The API version.
     *
     * @example https://domain.com/root_path/api_version/leaf
     */
    public function register_api_endpoints() {

        foreach ( $this->routes as $route ) {

            if ( empty( $route->endpoints() ) ) {
                throw new \Exception( 'Endpoints not set. Set the endpoints first.' );
            }

            foreach ( $route->endpoints() as $endpoint ) {

                register_rest_route( $route->full_api_root_path(), $endpoint->url(), array(
                    'methods'             => $endpoint->verb(),
                    'callback'            => $endpoint->callback(),
                    'permission_callback' => $this->get_permission_callback( $endpoint->capability() ),
                    'args'                => $this->get_validation_sanitization_args( $endpoint->guard_object() ),
                ) );

            }

        }

    }

    /**
     * Returns the args for validation and sanitization of the request arguments.
     * https: //developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#arguments
     *
     * Wordpress first executes the validation and then the sanitization.
     *
     * @param string $full_api_root_path The full api root path. "{namespace(aka. plugin-name)}/{version}".)}"
     * @param string $url The url of the endpoint. The part after the root path.
     * @param object $guard_object The guard object.
     *
     * @return array
     */
    private function get_validation_sanitization_args( object $guard_object ) {

        $args = array();

        $rules = $guard_object->rules();

        foreach ( $rules as $arg => $rule ) {

            $args[$arg] = array(
                'sanitize_callback' => array( $guard_object, 'sanitize_request_arg' ),
                'validate_callback' => array( $guard_object, 'validate_request_arg' ),
            );

        }

        return $args;

    }

    /**
     * Get the permission callback function for the register_rest_route() wordpress function.
     *
     * @param string $capability The capability.
     * @return string The permission callback function.
     */
    private function get_permission_callback( string $capability ) {

        if ( 'public' === $capability ) {
            return '__return_true';
        }

        return function () use ( $capability ) {
            return current_user_can( $capability );
        };

    }

}
