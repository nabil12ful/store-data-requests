<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit87182f6a5e23bd3b02e1dd98308b46a8
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Nabil\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Nabil\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit87182f6a5e23bd3b02e1dd98308b46a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit87182f6a5e23bd3b02e1dd98308b46a8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit87182f6a5e23bd3b02e1dd98308b46a8::$classMap;

        }, null, ClassLoader::class);
    }
}
