<?php

namespace app\controllers\base;

use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;

class ApiController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Remove authenticator before re-adding it after CORS
        if (isset($behaviors['authenticator'])) {
            $auth = $behaviors['authenticator'];
            unset($behaviors['authenticator']);
        }

        // Add CORS filter
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

        // Restore authenticator with controller-specific settings
        $behaviors['authenticator'] = $this->authenticatorBehavior($auth ?? null);

        return $behaviors;
    }

    /**
     * Allow child controllers to override authentication settings.
     */
    protected function authenticatorBehavior($defaultAuth)
    {
        return $defaultAuth ?? [
            'class' => HttpBearerAuth::class,
            'except' => [],
        ];
    }
}