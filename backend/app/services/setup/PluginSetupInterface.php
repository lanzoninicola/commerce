<?php

namespace Clockdown\Backend\App\Services\Setup;

interface PluginSetupInterface {

    public function __construct();

    public function define_db_schema(): void;

    public function install(): void;

    public function uninstall(): void;

}