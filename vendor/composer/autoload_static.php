<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb7619f916a8c7393bface545fff0e173
{
    public static $files = array (
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
    );

    public static $prefixLengthsPsr4 = array (
        'y' => 
        array (
            'yii\\composer\\' => 13,
            'yii\\' => 4,
        ),
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'yii\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-composer',
        ),
        'yii\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2',
        ),
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb7619f916a8c7393bface545fff0e173::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb7619f916a8c7393bface545fff0e173::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb7619f916a8c7393bface545fff0e173::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
