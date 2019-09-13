<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\PasswordForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * user controller
 */
class UserController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'micro\models\User';

    public function behaviors()
    {
        // remove rateLimiter which requires an authenticated user to work
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */

    public function actionSignup()
    {
        $model = new \micro\models\SignupForm();
        $params = Yii::$app->request->post();
        $model->username = $params['username'];
        $model->password = $params['password'];
        $model->email = $params['email'];

        if ($model->signup()) {
            $response['isSuccess'] = 201;
            $response['message'] = 'You are now a member!';
            $response['user'] = \micro\models\User::findByUsername($model->username);
            return $response;
        } else {
            //$model->validate();
            $model->getErrors();
            $response['hasErrors'] = $model->hasErrors();
            $response['errors'] = $model->getErrors();
            return $response;
        }
    }
    public function actionLogin()
    {
        $model = new \micro\models\LoginForm();
        $params = Yii::$app->request->post();
        $model->username = $params['username'];
        $model->password = $params['password'];
        if ($model->login()) {
            $data = \micro\models\User::findByUsername($model->username);
            $response['message'] = 'You are now logged in!';
            $response['token'] = $data;
            //return [$response,$model];
            return $response;
        } else {
            $model->validate();
            $response['errors'] = $model->getErrors();
            return $response;
        }
    }
}
