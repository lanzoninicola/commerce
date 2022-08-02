<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit57148f123bba56101d5df4eb67b5b2db
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Commerce\\Core\\' => 14,
            'Commerce\\Client\\Backend\\Setup\\' => 30,
            'Commerce\\Client\\Backend\\Api\\V1\\Onboarding\\' => 42,
            'Commerce\\Client\\Backend\\Api\\V1\\Models\\' => 38,
            'Commerce\\Client\\Backend\\Api\\V1\\Analytics\\' => 41,
            'Commerce\\Client\\Backend\\Api\\V1\\Accounts\\' => 40,
            'Commerce\\App\\Traits\\' => 20,
            'Commerce\\App\\Services\\ScriptLocalizer\\' => 38,
            'Commerce\\App\\Services\\RestApi\\' => 30,
            'Commerce\\App\\Services\\Logger\\' => 29,
            'Commerce\\App\\Services\\Database\\' => 31,
            'Commerce\\App\\Services\\' => 22,
            'Commerce\\App\\Interfaces\\' => 24,
            'Commerce\\App\\Functions\\' => 23,
            'Commerce\\App\\Common\\' => 20,
            'Commerce\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Commerce\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'Commerce\\Client\\Backend\\Setup\\' => 
        array (
            0 => __DIR__ . '/../..' . '/.client/backend/setup',
        ),
        'Commerce\\Client\\Backend\\Api\\V1\\Onboarding\\' => 
        array (
            0 => __DIR__ . '/../..' . '/.client/backend/api/v1/onboarding',
        ),
        'Commerce\\Client\\Backend\\Api\\V1\\Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/.client/backend/api/v1/models',
        ),
        'Commerce\\Client\\Backend\\Api\\V1\\Analytics\\' => 
        array (
            0 => __DIR__ . '/../..' . '/.client/backend/api/v1/analytics',
        ),
        'Commerce\\Client\\Backend\\Api\\V1\\Accounts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/.client/backend/api/v1/accounts',
        ),
        'Commerce\\App\\Traits\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/traits',
        ),
        'Commerce\\App\\Services\\ScriptLocalizer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/services/script-localizer',
        ),
        'Commerce\\App\\Services\\RestApi\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/services/rest-api',
        ),
        'Commerce\\App\\Services\\Logger\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/services/logger',
        ),
        'Commerce\\App\\Services\\Database\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/services/database',
        ),
        'Commerce\\App\\Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/services',
        ),
        'Commerce\\App\\Interfaces\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/interfaces',
        ),
        'Commerce\\App\\Functions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/functions',
        ),
        'Commerce\\App\\Common\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/common',
        ),
        'Commerce\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit57148f123bba56101d5df4eb67b5b2db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit57148f123bba56101d5df4eb67b5b2db::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit57148f123bba56101d5df4eb67b5b2db::$classMap;

        }, null, ClassLoader::class);
    }
}
