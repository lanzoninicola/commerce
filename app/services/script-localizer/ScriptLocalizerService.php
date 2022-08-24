<?php

namespace Commerce\App\Services\ScriptLocalizer;

use Commerce\App\Common\Helpers;
use function Commerce\get_commerce_api_base_url;
use function Commerce\get_plugin_name;

/**
 * The class responsible for localize the script.
 *
 * @package    Commerce
 * @subpackage Commerce/admin
 *
 * @link       https://lanzoninicola.com.br
 * @since      1.0.0
 */

class ScriptLocalizerService {

    /**
     * This is the handle name used to localize the script.
     *
     * @access   private
     * @var string - the handle name
     * @since    1.0.0
     */
    private $handle;

    /**
     * This is the name of Javascript object that will be used to localize the script.
     * Once the script is localized, it will be accessible as `clockdown_localizer.[props]`.
     *
     * @var string
     */
    private $object_name;

    /**
     * This is the content of the Javascript object
     * that will be used to localize the script in the admin area.
     *
     * @var array
     */
    protected $l10n = array();

    public function __construct() {
        $this->handle      = get_plugin_name() . '-localizer';
        $this->object_name = get_plugin_name() . 'Localized';

        /**
         * wp_localize_script() works only if the handle used on
         * wp_enqueue_script() function is the same of
         * the handle used on wp_register_script()
         */
        wp_register_script( $this->handle, '' );
        wp_enqueue_script( $this->handle );

    }

    /**
     * Register the data to be localized only for the public area
     *
     * @param array $l10n - associative array $l10n that will transformed to Javascript object
     * @return void
     */
    public function localize( array $l10n ) {

        $this->is_associative_array( $l10n );

        $next_l10n  = array_merge( $this->l10n, $l10n );
        $this->l10n = $next_l10n;

    }

    /**
     * Localize a script.
     * Works only if the script has already been registered.
     *
     * @since    1.0.0
     */
    public function localize_script() {

        wp_localize_script(
            $this->handle,
            $this->object_name,
            array_merge(
                $this->l10n,
                array(
                    'commerce_api_url' => get_commerce_api_base_url(),
                    'nonce'            => wp_create_nonce( get_plugin_name() . 'nonce' ),
                    'wp_rest_nonce'    => wp_create_nonce( 'wp_rest' ),
                )
            )
        );
    }

    /**
     * Check if the array given is associative, otherwise throw an exception.
     *
     * @param array $l10n
     * @return boolean
     */
    protected function is_associative_array( array $l10n ) {

        if ( !Helpers::is_associative_array( $l10n ) ) {
            throw new \Exception( 'ScriptLocalizerService::localize - The data must be an associative array.' );
        }

    }

}
