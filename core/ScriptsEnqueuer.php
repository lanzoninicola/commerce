<?php

namespace Commerce\Core;

class ScriptsEnqueuer {

    /**
     * Contains the data of all style to register.
     *
     * @var array
     */
    protected $public_styles = array();

    /**
     * Contains the data of all admin style to register.
     *
     * @var array
     */
    protected $admin_styles = array();

    /**
     * Contains the data of all script to register.
     *
     * @var array
     */
    protected $public_scripts = array();

    /**
     * Contains the data of all admin script to register.
     *
     * @var array
     */
    protected $admin_scripts = array();

    /**
     * Contains the name of handles to deregister.
     *
     * @var array
     */
    protected $deregister_scripts = array();

    /**
     * Add a new style to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param string $media
     * @return void
     */
    public function add_public_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
        $this->public_styles[$handle] = array(
            'handle'  => $handle,
            'src'     => $src,
            'deps'    => $deps,
            'version' => $ver,
            'media'   => $media,
        );
    }

    /**
     * Add a new style to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param string $media
     * @return void
     */
    public function add_admin_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
        $this->admin_styles[$handle] = array(
            'handle'  => $handle,
            'src'     => $src,
            'deps'    => $deps,
            'version' => $ver,
            'media'   => $media,
        );
    }

    /**
     * Add a new ***public*** script to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param boolean $in_footer
     * @return void
     */
    public function add_public_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
        $this->public_scripts[$handle] = array(
            'handle'    => $handle,
            'src'       => $src,
            'deps'      => $deps,
            'version'   => $ver,
            'in_footer' => $in_footer,
        );
    }

    /**
     * Add a new ***admin*** script to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param boolean $in_footer
     * @return void
     */
    public function add_admin_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
        $this->admin_scripts[$handle] = array(
            'handle'    => $handle,
            'src'       => $src,
            'deps'      => $deps,
            'version'   => $ver,
            'in_footer' => $in_footer,
        );
    }

    /**
     * Add a new script to the collection to be deregistered with WordPress.
     *
     * @param [type] $handle
     * @return void
     */
    public function deregister_script( $handle ) {
        $this->deregister_scripts[] = $handle;
    }

    /**
     * Register the stylesheets for the public side
     */
    public function enqueue_styles() {

        foreach ( $this->public_styles as $style ) {

            wp_enqueue_style(
                $style['handle'],
                $style['src'],
                $style['deps'],
                $style['version']
            );

        }

    }

    /**
     * Register the stylesheets for the admin area
     */
    public function admin_enqueue_styles() {

        foreach ( $this->admin_styles as $style ) {

            wp_enqueue_style(
                $style['handle'],
                $style['src'],
                $style['deps'],
                $style['version']
            );

        }

    }

    /**
     * Register the scripts
     */
    public function enqueue_scripts() {

        foreach ( $this->public_scripts as $script ) {

            wp_enqueue_script(
                $script['handle'],
                $script['src'],
                $script['deps'],
                $script['version'],
                $script['in_footer']
            );

        }

    }

    /**
     * Register the scripts for the admin area
     */
    public function admin_enqueue_scripts() {

        foreach ( $this->admin_scripts as $script ) {

            wp_enqueue_script(
                $script['handle'],
                $script['src'],
                $script['deps'],
                $script['version'],
                $script['in_footer']
            );

        }

    }

    /**
     * Deregister the scripts
     */
    public function deregister_scripts() {

        foreach ( $this->deregister_scripts as $script ) {

            wp_deregister_script( $script );

        }

    }

    /**
     * Add the related actions to load the stylesheet and javascript.
     *
     * Currently the priority is set default to 10, but this might be implemented in the future.
     *
     * @return void
     */
    public function run() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

    }

}
