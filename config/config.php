<?php
$db = require __DIR__ . '/db.php';
return [
    'id' => 'micro-app',
    // the basePath of the application will be the `micro-app` directory
    'basePath' => dirname(__DIR__),
    // set an alias to enable autoloading of classes from the 'micro' namespace
    'name' => 'Clever',
    'language' => 'id',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Asia/Jakarta',
    'aliases' => [
        '@micro' => dirname(__DIR__),
    ],
    // this is where the application will find all controllers
    'controllerNamespace' => 'micro\controllers',
    'components' => [
        'db' => $db,
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'micro\models\User',
            'enableSession' => false,
            'loginUrl' => null,
            'enableAutoLogin' => false,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfCookie' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'enableStrictParsing' => false,
            // 'rules' => [
            //     ['class' => 'yii\rest\UrlRule',
            //         'controller' =>
            //         [
            // put your controller here
            //             'user',
            //         ],
            //     ],
            // ],
        ],

    ],

];
