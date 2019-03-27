<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4f6caf19d5a0d5860c1fc7295167731a
{
    public static $files = array (
        '94ae8b18c09057a60f786a0eb5b0b906' => __DIR__ . '/../..' . '/Modules/Ziroom/Http/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Ziroom\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Ziroom\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4f6caf19d5a0d5860c1fc7295167731a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4f6caf19d5a0d5860c1fc7295167731a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
