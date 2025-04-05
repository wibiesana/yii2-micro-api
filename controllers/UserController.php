<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use app\controllers\base\ApiController;

/**
 * UserController handles user resource actions.
 */
class UserController extends ApiController
{
    /**
     * The model class associated with this controller.
     * Required by the ActiveController to perform CRUD.
     *
     * @inheritdoc
     */
    public $modelClass = 'app\models\User';

    /**
     * Customizes the authenticator behavior.
     * Only authenticated users can access all actions by default.
     * Modify the 'except' array to allow anonymous access.
     *
     * @param array $defaultAuth
     * @return array
     */
    protected function authenticatorBehavior($defaultAuth)
    {
        return [
            'class' => HttpBearerAuth::class,
            'except' => [
                // Example: Uncomment actions below to allow public access
                // 'index',
                // 'view',
                // 'create',
                // 'update',
                // 'delete',
            ],
        ];
    }

    /**
     * Disables default CRUD actions from parent if custom logic needed.
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // Default actions remain intact (nothing is unset)
        // You can comment lines below to enable specific actions
        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );
        return $actions;
    }

    /**
     * Specifies allowed HTTP methods for each action.
     *
     * @return array
     */
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
