<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use yii;
use app\controllers\base\Controller;

class SiteController extends Controller
{

    public $except = [
        // Uncomment any of the routes below to allow access without authentication
        'index',
        'status',
    ];
    /**
     * Index action.
     *
     * @return string
     */
    public function actionIndex()
    {
        return "Welcom to YII Micro Rest APi";
    }

    /**
     * Status action.
     *
     * @return string
     */
    public function actionStatus()
    {
        $environment = YII_ENV;
        $debugStatus = YII_DEBUG ? 'enabled' : 'disabled';
        return "Welcome to yii2 micro API! You are running in the '$environment' environment with debug mode $debugStatus.";
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'OPTIONS'],
            'status' => ['GET', 'OPTIONS'],
        ];
    }
}
