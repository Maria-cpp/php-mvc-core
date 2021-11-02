<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit458c816311563f9ce191bee1c5214bdb
{
    public static $prefixLengthsPsr4 = array (
        'z' => 
        array (
            'zum\\phpmvc\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'zum\\phpmvc\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit458c816311563f9ce191bee1c5214bdb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit458c816311563f9ce191bee1c5214bdb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit458c816311563f9ce191bee1c5214bdb::$classMap;

        }, null, ClassLoader::class);
    }
}
