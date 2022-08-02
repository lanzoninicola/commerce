<?php

namespace Commerce\App\Services;

class AdminMenuService {

    /**
     * Fallback capability if not set
     *
     * @var AdminMenuService
     */
    private $default_capability = 'manage_options';

    /**
     * The menu slug
     *
     * @var AdminMenuService
     */
    private $slug;

    /**
     * Singleton instance.
     *
     * @var AdminMenuService
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return AdminMenuService
     */
    public static function singletone() {

        if ( self::$instance === null ) {
            self::$instance = new AdminMenuService();
        }

        return self::$instance;
    }

    /**
     * Add the menu item in the admin menu
     *
     * @param  string $label
     * @param  string $slug
     * @param  array  $options
     * @return void
     */
    public function create_admin_menu( string $label, string $slug, array $options = array(
        'page_title' => null,
        'capability' => null,
        'icon_url'   => null,
        'wp_icon'    => null,
    ) ) {

        $page_title = $options['page_title'] ?? __( $label, COMMERCE_TEXT_DOMAIN );
        $capability = $options['capability'] ?? $this->default_capability;
        $icon       = $options['icon_url'] ?? $options['wp_icon'] ?? 'dashicons-admin-tools';
        $this->slug = $slug;

        add_menu_page(
            __( $label, COMMERCE_TEXT_DOMAIN ),
            $page_title,
            $capability,
            $slug,
            array( $this, 'page_wrapper' ),
            $icon
        );

    }

    public function create_admin_submenu( string $parent_slug, string $label, string $slug, $options = array(
        'page_title' => null,
        'capability' => null,
        'position'   => null,
    ) ) {

        $page_title = $options['page_title'] ?? __( $label, COMMERCE_TEXT_DOMAIN );
        $capability = $options['capability'] ?? $this->default_capability;
        $position   = $options['position'] ?? null;

        add_submenu_page(
            $parent_slug,
            __( $label, COMMERCE_TEXT_DOMAIN ),
            $page_title,
            $capability,
            $slug,
            array( $this, 'page_wrapper' ),
            $position
        );

        $this->remove_duplicate_submenu_page();
    }

    /**
     * Create the page wrapper using the slug as the page id.
     *
     * @return void
     */
    public function page_wrapper() {

        $html = sprintf( '<div id="%s"></div>', $this->slug );
        echo wp_kses_post( $html );

    }

    /**
     * Remove duplicate submenu
     * Submenu page hack: Remove the duplicate Plugin link on subpages
     *
     */
    public function remove_duplicate_submenu_page() {
        remove_submenu_page( 'commerce', 'commerce' );
    }

}
