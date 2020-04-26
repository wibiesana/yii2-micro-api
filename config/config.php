<?php
$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'yii2-micro-api',
    // the basePath of the application will be the `micro-app` directory
    'basePath' => dirname(__DIR__),
    // set an alias to enable autoloading of classes from the 'micro' namespace
    'name' => 'Yii2MicroApi',
    'language' => 'id',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Asia/Jakarta',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'db' => $db,
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => false,
            'loginUrl' => null,
            'enableAutoLogin' => false,
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
        // uncomment below to use email to retrieve forgoten password
        // 'mailer' => [
        //     'class' => 'yii\swiftmailer\Mailer',
        //     'viewPath' => '@app/mail',
        //     'transport' => [
        //                        'class' => 'Swift_SmtpTransport',
        //                        'host' => 'smtp.gmail.com',
        //                        'username' => 'youremail@gmail.com',
        //                        'password' => 'yourpassword',
        //                        'port' => '25',
        //                        'encryption' => 'ssl',
        //     ],
        // ],

    ],

];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        //        'allowedIPs' => ['127.0.0.1'], // accessible to this ip address only
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
return $config;
