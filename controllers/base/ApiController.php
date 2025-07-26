<?php

namespace app\controllers\base;

use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class ApiController extends ActiveController
{
    /**
     * If true, enable CORS filter for this controller.
     * If false, CORS headers will not be applied.
     */
    public bool $enableCors = true;

    /**
     * If true, skips ownership check for actions listed in $ownershipProtectedActions.
     * If false, only the creator or admin can perform those actions.
     */
    public bool $bypassOwnershipCheck = true;

    /**
     * List of actions that require ownership check.
     * Can be overridden in child controllers.
     */
    public array $ownershipProtectedActions = ['update', 'delete'];

    /**
     * Model attribute that holds the user ID who created the record.
     * Used for ownership validation in access checks.
     */
    public string $ownershipField = 'created_by';

    /**
     * Actions that are excluded from authentication.
     */
    public array $except = [];

    /**
     * Initializes controller configuration from global params if available.
     */
    public function init(): void
    {
        parent::init();

        $params = \Yii::$app->params;

        $this->enableCors = $params['api.enableCors'] ?? $this->enableCors;
        $this->bypassOwnershipCheck = $params['api.bypassOwnershipCheck'] ?? $this->bypassOwnershipCheck;
        $this->ownershipProtectedActions = $params['api.ownershipProtectedActions'] ?? $this->ownershipProtectedActions;
        $this->ownershipField = $params['api.ownershipField'] ?? $this->ownershipField;
        $this->except = $params['api.except'] ?? $this->except;
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);

        if ($this->enableCors) {
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
                        'X-Pagination-Per-Page',
                    ],
                ],
            ];
        }

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => array_merge(['options'], $this->except),
        ];

        return $behaviors;
    }

    /**
     * Checks access to a given action and model.
     * Skips if action not protected, bypass is enabled, or user is admin.
     *
     * @param string $action
     * @param \yii\db\ActiveRecord|null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []): void
    {
        $user = \Yii::$app->user;

        if (!in_array($action, $this->ownershipProtectedActions, true)) {
            return;
        }

        if ($this->bypassOwnershipCheck) {
            return;
        }

        if (!$user->isGuest && $user->identity->role === 30) {
            return;
        }

        if ($model && $model->{$this->ownershipField} !== $user->id) {
            throw new ForbiddenHttpException(sprintf(
                'You can only %s data that you\'ve created.',
                $action
            ));
        }
    }
}
