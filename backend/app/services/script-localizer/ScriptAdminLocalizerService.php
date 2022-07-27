<?php

namespace Clockdown\Backend\App\Services\ScriptLocalizer;

/**
 * The class responsible for localize the script.
 *
 * @package    Clockdown
 * @subpackage Clockdown/admin
 *
 * @link       https://lanzoninicola.com.br
 * @since      1.0.0
 */

class ScriptAdminLocalizerService extends ScriptLocalizerService {

    /**
     * Singleton instance.
     *
     * @var ScriptAdminLocalizerService
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return ScriptAdminLocalizerService
     */
    public static function singletone() {

        if ( self::$instance === null ) {
            self::$instance = new ScriptAdminLocalizerService();
        }

        return self::$instance;
    }

    public function __construct() {
        parent::__construct();

        $this->admin_enqueue_scripts();
    }

    /**
     * Enqueue the script for the admin area
     *
     * The 'localize_script()' method is owned by the parent class.
     *
     * @return void
     */
    private function admin_enqueue_scripts() {

        add_action( 'admin_enqueue_scripts', array( $this, 'localize_script' ) );
    }

}
