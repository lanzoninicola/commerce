<?php

namespace Commerce\App\Services\ScriptLocalizer;

/**
 * The class responsible for localize the script.
 *
 * @package    Commerce
 * @subpackage Commerce/admin
 *
 * @link       https://lanzoninicola.com.br
 * @since      1.0.0
 */

class ScriptPublicLocalizerService extends ScriptLocalizerService {

    /**
     * Singleton instance.
     *
     * @var ScriptPublicLocalizerService
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return ScriptPublicLocalizerService
     */
    public static function singletone() {

        if ( self::$instance === null ) {
            self::$instance = new ScriptPublicLocalizerService();
        }

        return self::$instance;
    }

    public function __construct() {
        parent::__construct();

        $this->enqueue_scripts();
    }

    /**
     * Enqueue the script for the public area
     *
     * The 'localize_script()' method is owned by the parent class.
     *
     * @return void
     */
    private function enqueue_scripts() {

        add_action( 'wp_enqueue_scripts', array( $this, 'localize_script' ) );
    }

}
