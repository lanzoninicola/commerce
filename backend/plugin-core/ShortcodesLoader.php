<?php

namespace Commerce\Backend\PluginCore;

/**
 * Class to manage the registration of the shortcodes.
 *
 * @package    Commerce
 * @subpackage Commerce/plugin-core
 *
 * @method add() Add a shortcode to the list of registered shortcodes.
 * @method add_inline_script() Add the inline script tag for the shortcode.
 * @method add_inline_style() Add the inline style tag for the shortcode.
 * @method add_localized_script_data() Add the localized script data for the shortcode.
 */
class ShortcodesLoader {

    /**
     * The array that contains the shortcodes.
     *
     * @var array
     *
     * @example - $shortcodes  = array(
     *    shortcode_name => related_class_contains_output(),
     * );
     */
    private $shortcodes = array();

    /**
     * The array that contains the information of the inline script for each shortcode.
     *
     * @example - $inline_scripts = array(
     *    shortcode_name => script_info[],
     * );
     *
     * @since    1.0.0
     */
    private $scripts = array();

    /**
     * The array that contains the information of the script localized for each shortcode.
     *
     * @example - $localized_scripts = array(
     *   shortcode_name => localized_script_info[],
     * );
     */
    private $localized_scripts = array();

    /**
     * The array that contains the information of the inline stylesheet for each shortcode.
     *
     * @example - $inline_scripts = array(
     *    shortcode_name => stylesheet_info[],
     * );
     *
     * @since    1.0.0
     */
    private $stylesheets = array();

    /**
     * Add the shortcode to the collection.
     *
     * @param string $name The name of the shortcode.
     * @param string $class_name The class name of the shortcode containing the output() method.
     * The class must be passed in this format Classname::class
     * @return void
     */
    public function add( string $name, string $class_name ) {

        $this->shortcodes[$name] = $class_name;
    }

    /**
     * Register the information for the script tag that will added under the shortcode html code
     *
     * @param string $name The name of the shortcode.
     * @param array $inline_script
     * $inline_script = array(
     *    'id'      => '', // the identifier of script tag
     *    'src'     => '', // the source of script tag
     *    'ver'     => '', // the version of script tag
     *    'defer'   => false, // the defer attribute of script tag
     *    'async'   => false, // the async attribute of script tag
     * );
     */
    public function add_inline_script( string $name, array $inline_script = array() ) {

        $this->scripts[$name] = array(
            'id'    => $inline_script['id'],
            'src'   => $inline_script['src'],
            'ver'   => isset( $inline_script['ver'] ) ? $inline_script['ver'] : '1.0.0',
            'defer' => isset( $inline_script['defer'] ) ? $inline_script['defer'] : false,
            'async' => isset( $inline_script['async'] ) ? $inline_script['async'] : false,
        );
    }

    /**
     * Register the information for the script type="text-javascript" tag that will added below the shortcode html code
     * and contains the localized data on support of the shortcode.
     *
     * @param string $name The name of the shortcode.
     * @param array $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
     * @param array $inline_localized_script The data itself. The data must be a  multi-dimensional array.
     */
    public function add_localize_script( string $name, string $object_name, array $localize_script ) {

        $this->localized_scripts[$name][$object_name] = $localize_script;

    }

    /**
     * Register the information for the link rel="stylesheet" tag that will added above the shortcode html code
     *
     * @param string $name The name of the shortcode.
     * @param array $inline_stylesheet
     * $inline_stylesheet = array(
     *   'id'      => '', // the identifier of style tag
     *   'href'    => '', // the source of style tag
     *   'ver'     => '', // the version of style tag
     *   'media'   => 'all', // the media attribute of style tag
     * );
     */
    public function add_inline_stylesheet( string $name, array $inline_stylesheet = array() ) {

        $this->stylesheets[$name] = array(
            'id'    => $inline_stylesheet['id'],
            'href'  => $inline_stylesheet['href'],
            'ver'   => isset( $inline_stylesheet['ver'] ) ? $inline_stylesheet['ver'] : '1.0.0',
            'media' => isset( $inline_stylesheet['media'] ) ? $inline_stylesheet['media'] : 'all',
        );

    }

    /**
     * Bootstraps the shortcodes registered.
     *
     * @return void
     */
    public function run() {

        foreach ( $this->shortcodes as $name => $class_name ) {

            // Instantiate the shortcode class that contain the add_shortcode() wp method
            $shortcode = new $class_name( $name );

            if ( !method_exists( $shortcode, 'output' ) ) {
                throw new \Exception( 'The class ' . $class_name . ' must extends the Shortcode class and contain the output() method.' );
            }

            if ( isset( $this->scripts[$name] ) ) {

                $shortcode->register_script( $this->scripts[$name] );
            }

            if ( isset( $this->localized_scripts[$name] ) ) {

                foreach ( $this->localized_scripts[$name] as $object_name => $localize_script ) {
                    $shortcode->register_localize_script( $object_name, $localize_script );
                }

            }

            if ( isset( $this->stylesheets[$name] ) ) {

                $shortcode->register_stylesheet( $this->stylesheets[$name] );
            }

        }

    }

}
