<?php

namespace app\controllers\base;

use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;

class ApiController extends ActiveController
{
    public $except =  [];

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Remove the existing authenticator
        unset($behaviors['authenticator']);

        // CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Total-Count',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Current-Page',
                    'X-Pagination-Per-Page'
                ],
            ],
        ];

        // Re-add authenticator, using controller's $except
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => array_merge(['options'], $this->except),
        ];

        return $behaviors;
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param Model $model the model to be accessed. If `null`, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */

    public function checkAccess($action, $model = null, $params = []): void
    {
        $user = \Yii::$app->user;

        // Allow all if admin
        if ($user->identity && $user->identity->role == 30) {
            return;
        }

        // Restrict update/delete to creator
        if (in_array($action, ['update', 'delete'])) {
            if ($model->created_by !== $user->id) {
                throw new \yii\web\ForbiddenHttpException(sprintf(
                    'You can only %s data that you\'ve created.',
                    $action
                ));
            }
        }
    }
}
