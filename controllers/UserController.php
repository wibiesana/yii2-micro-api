<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * user controller
 */
class UserController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\User';

    //    delete default actions

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => \Yii::$app->params['corsOptions']
        ];
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'except' => [],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['create'],
            $actions['view'],
            $actions['update'],
            $actions['delete'],
            $actions['index']
        );
        return $actions;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }
}
