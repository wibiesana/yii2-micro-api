<?php

namespace app\controllers\base;

use yii\web\Controller as BaseController;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;

class Controller extends BaseController
{
    public $except =  [];

    public function behaviors()
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
}
