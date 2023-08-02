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
            'index' => ['GET', 'HEAD', 'OPTIONS'], //instead of  'index' => ['GET', 'HEAD']
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    //     example for search and sort
    //     u can access it by using http://localhost/yii2-micro-api/user?UserSearch[name]=hadi&sort=id
    //     note that u have to create UserSearch first u can do that by using gii crud
    /**
     * public function actions()
     * {
     * $actions = parent::actions();
     * $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
     * return $actions;
     * }
     *
     * public function prepareDataProvider()
     * {
     * $searchModel = new \app\models\CbtStudentSearch();
     * return $searchModel->search(\Yii::$app->request->queryParams);
     * }
     **/
}
