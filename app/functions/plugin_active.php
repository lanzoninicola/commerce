<?php

// TODO: Implements this

function wego_custom_wpmenu_check_activation() {

    $pods_plugin            = 'pods/init.php';
    $current_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

// is this plugin active?
    if ( !in_array( $pods_plugin, $current_active_plugins ) ) {
        // deactivate the plugin
        deactivate_plugins( plugin_basename( __FILE__ ) );
        // unset activation notice
        unset( $_GET['activate'] );

        // display notice
        add_action(
            'admin_notices',
            function () {
                $message = sprintf(
                    esc_html__( '%s requires %s to be installed and activated: %s', WEGO_CUSTOM_WPMENU_DOMAIN ),
                    '<strong>Webigo Custom WPAdmin Menu</strong>',
                    '<strong>Pods Framework</strong>',
                    '<strong>https://wordpress.org/plugins/pods/</strong>',
                );
                $html = sprintf( '<div class="notice notice-warning">%s</div>', wpautop( $message ) );
                echo wp_kses_post( $html );
            }
        );
    }

}
