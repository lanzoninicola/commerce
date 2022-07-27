<?php

namespace Commerce\Backend\App\Services\RestApi;

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

            $full_api_root_path = "{$route->root_path()}/{$route->api_version()}";

            if ( empty( $route->endpoints() ) ) {
                throw new \Exception( 'Endpoints not set. Set the endpoints first.' );
            }

            foreach ( $route->endpoints() as $endpoint ) {

                register_rest_route( $full_api_root_path, $endpoint->endpoint(), array(
                    'methods'             => $endpoint->verb(),
                    'callback'            => $endpoint->callback(),
                    'permission_callback' => $this->get_permission_callback( $endpoint->capability() ),
                ) );

            }

        }

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
