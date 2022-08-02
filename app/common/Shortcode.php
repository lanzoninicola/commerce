<?php

namespace Commerce\App\Common;

/**
 * This class is used to define the shortcodes used by the plugin.
 *
 * The class that extends this class must define the method `output` that will be used to render the shortcode.
 *
 * @package    Commerce
 * @subpackage Commerce/Backend/App/Common
 *
 * @method add_inline_script( $inline_script = array() ) Let you add a script to the list of scripts to be enqueued.
 * @method add_inline_style( $inline_stylesheet = array() ) Let you add a style to the list of styles to be enqueued.
 * @method output( $atts = array() ) Let you define the code that will be rendered by the shortcode.
 *
 */
abstract class Shortcode {

    /**
     * The name of shortcode
     *
     * @var string
     */
    public $name = '';

    /**
     * This array contains the information to generate the in line <script> tag
     *
     * @var array
     */
    public $inline_script = array();

    /**
     * The name of the object to render
     *
     * @var array
     */
    public $inline_localized_script_object_name = '';

    /**
     * This array contains the information to generate the in line <script type="text/javascript"> tag
     *
     * @var array
     */
    public $inline_localized_script_data = array();

    /**
     * This array contains the information to generate the in line <link rel="stylesheet"> tag
     *
     * @var array
     */
    public $inline_stylesheet = array();

    /**
     * Bootstrap the shortcode.
     *
     * @param string $name The name of shortcode.
     *
     */
    public function __construct( string $name ) {

        $this->name = $name;

        add_shortcode( $this->name, array( $this, 'render' ) );
    }

    /**
     * Register the information for the script tag that will added under the shortcode html code
     *
     * @param array $inline_script
     * $inline_script = array(
     *    'id'      => '', // the identifier of script tag
     *    'src'     => '', // the source of script tag
     *    'ver'     => '', // the version of script tag
     *    'defer'   => false, // the defer attribute of script tag
     *    'async'   => false, // the async attribute of script tag
     * );
     */
    public function register_script( array $inline_script = array() ) {

        $this->inline_script = $inline_script;
    }

    /**
     * Register the information for the script type="text-javascript" tag that will added below the shortcode html code
     * and contains the localized data on support of the shortcode.
     *
     * @param array $localized_script
     * $inline_script = array(
     *    ...data to be localized...
     * );
     */
    public function register_localize_script( string $object_name, array $localized_script ) {

        $this->inline_localized_script_object_name = $object_name;
        $this->inline_localized_script_data        = $localized_script;
    }

    /**
     * Register the information for the link rel="stylesheet" tag that will added above the shortcode html code
     *
     * @param array $inline_stylesheet
     * $inline_stylesheet = array(
     *   'id'      => '', // the identifier of style tag
     *   'href'    => '', // the source of style tag
     *   'ver'     => '', // the version of style tag
     *   'media'   => 'all', // the media attribute of style tag
     * );
     */
    public function register_stylesheet( array $inline_stylesheet = array() ) {

        $this->inline_stylesheet = $inline_stylesheet;
    }

    /**
     * Main function that renders the shortcode.
     *
     * @param  array    $atts     Attributes. Default to empty array.
     * @param  array    $options  Customer options data.
     * @return string
     */
    public function render(
        $atts = array()
    ) {

        ob_start();

        /** add the stylesheet tag */

        if ( !empty( $this->inline_stylesheet ) ) {
            $this->render_stylesheet_tag();
        }

        /** add output of the shortcode */

        if ( is_callable( $this->output( $atts ) ) ) {
            call_user_func( $this->output, $atts );
        }

        /** add the script localized */

        if ( !empty( $this->inline_localized_script_data ) ) {
            $this->render_localize_script_tag();
        }

        /** add the script tag */

        if ( !empty( $this->inline_script ) ) {
            $this->render_script_tag();
        }

        return ob_get_clean();

    }

    /**
     * The code rendered by the shortcode.
     *
     * @return void
     */
    abstract public static function output( $atts = array() );

    /**
     * This function renders the <script> tag.
     *
     * @return void
     */
    private function render_script_tag() {

        $script = "<script
                    id='{$this->inline_script['id']}'
                    src='{$this->inline_script['src']}?ver={$this->inline_script['ver']}'></script>";

        if ( $this->inline_script['defer'] ) {
            $script = str_replace( '<script', '<script defer', $script );
        }

        if ( $this->inline_script['async'] ) {
            $script = str_replace( '<script', '<script async', $script );
        }

        echo $script;

    }

    /**
     * This function renders the <script type="text/javascript"> tag.
     *
     * @return void
     */
    private function render_localize_script_tag() {

        $script = "<script type='text/javascript'>";

        $script .= "/* <![CDATA[ */";
        $script .= "var {$this->inline_localized_script_object_name} = " . wp_json_encode( $this->inline_localized_script_data ) . ';';
        $script .= "/* ]]> */";
        $script .= "</script>";

        echo $script;
    }

    /**
     * This function renders the <link rel="stylesheet"> tag.
     *
     * @return void
     */
    private function render_stylesheet_tag() {

        $style = "<link rel='stylesheet'
            id='{$this->inline_stylesheet['id']}'
            href='{$this->inline_stylesheet['href']}?ver={$this->inline_stylesheet['ver']}'
            media='{$this->inline_stylesheet['media']}'/>";

        echo $style;
    }

}
