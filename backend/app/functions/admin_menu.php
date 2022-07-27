<?php

namespace Clockdown\Backend\App\Functions {

    use Clockdown\Backend\App\Services\AdminMenuService;

    function add_menu( string $label, string $slug, array $options = array() ) {
        $admin_menu = AdminMenuService::singletone();
        $admin_menu->create_admin_menu( $label, $slug, $options );
    }

    function add_submenu( string $parent_slug, string $label, string $slug, array $options = array() ) {
        $admin_menu = AdminMenuService::singletone();
        $admin_menu->create_admin_submenu( $parent_slug, $label, $slug, $options );
    }
}
