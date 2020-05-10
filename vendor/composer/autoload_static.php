<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1af5f3b1f036cdeb6f1c8a2b01c641f6
{
    public static $files = array (
        '1cfd2761b63b0a29ed23657ea394cb2d' => __DIR__ . '/..' . '/topthink/think-captcha/src/helper.php',
        'b4e9a54277284cb575d047d358a20396' => __DIR__ . '/..' . '/topthink/think-worker/src/config.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\worker\\' => 13,
            'think\\composer\\' => 15,
            'think\\captcha\\' => 14,
        ),
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\worker\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-worker/src',
        ),
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\captcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-captcha/src',
        ),
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
            1 => __DIR__ . '/..' . '/workerman/workerman-for-win',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1af5f3b1f036cdeb6f1c8a2b01c641f6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1af5f3b1f036cdeb6f1c8a2b01c641f6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
