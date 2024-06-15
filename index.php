<?php

use Dotenv\Dotenv;

// Include the Composer autoload file
require __DIR__ . '/vendor/autoload.php';

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set YII_DEBUG and YII_ENV based on .env values
defined('YII_DEBUG') or define('YII_DEBUG', filter_var($_ENV['YII_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV'] ?? 'dev');

require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Load the application configuration
$config = require __DIR__ . '/config/config.php';

// Create and run the application
(new yii\web\Application($config))->run();
