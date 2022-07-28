<?php

namespace Commerce\Backend\App\Services\RestApi;

/**
 * The class that describe the custom REST API end-points for the countdown.
 *
 * @package    Commerce
 * @subpackage Commerce/admin
 *
 * @link       https://lanzoninicola.com.br
 * @since      1.0.0
 */

class RestApiRoutes {

    /**
     * Collection of routes for the custom REST-API.
     *
     * @param string $root_path The root path of api endpoint (e.g. /wp-json/{plugin-name}). Wordpress calls it "namespace".
     * @param string $api_version The API version (eg. v1, v2, v3, etc.)
     * @param array $endpoints The endpoints to be registered.
     *
     * @example -
     * https://domain.com/wp-json/root_path/api_version/endpoint_1;
     * https: //domain.com/wp-json/root_path/api_version/endpoint_2;
     */
    public function __construct( string $root_path, string $api_version, array $endpoints ) {
        $this->root_path   = $root_path;
        $this->api_version = $api_version;
        $this->endpoints   = $endpoints;

    }

    /**
     * Returns the root path of REST-API.
     *
     * @return string
     */
    public function root_path(): string {
        return $this->root_path;
    }

    /**
     * Returns the api version
     *
     * @return string
     */
    public function api_version(): string {
        return $this->api_version;
    }

    /**
     * Returns the full root path of endpoints that contain the namespace and the api version.
     * (e.g. /wp-json/{plugin-name}/{rest-api-version}/)
     *
     * @return string
     */
    public function full_api_root_path(): string {
        return "{$this->root_path()}/{$this->api_version()}";
    }

    /**
     * Returns the array of endpoints.
     *
     * @return RestApiEndpoint[]
     * @example array(
     *    'endpoint' => array(
     *      'method' => array(     // GET, POST, PUT, DELETE
     *               'callback' => callback // class name, method name
     *               'capability' => 'capability', // wordpress 'manage_options'
     *       )
     * )
     */
    public function endpoints(): array{
        return $this->endpoints;
    }

}
