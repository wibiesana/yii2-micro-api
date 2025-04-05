<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use yii;
use yii\filters\auth\HttpBearerAuth;
use app\controllers\base\Controller;

class SiteController extends Controller
{
    protected function authenticatorBehavior($defaultAuth)
    {
        return [
            'class' => HttpBearerAuth::class,
            'except' => [
                // uncomment below to allow anonymous access to view action
                'login',
                'status',
                'register',
                'index',
                'reset-password',
                'send-email-reset-password',
            ],
        ];
    }
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

    /**
     * Register action.
     *
     * @return array
     */
    public function actionRegister()
    {
        $model = new SignupForm();
        $params = Yii::$app->request->post();
        $model->name = $params['name'] ?? '';
        $model->email = $params['email'] ?? '';
        $model->username = $params['username'] ?? '';
        $model->password = $params['password'] ?? '';

        if (!$model->signup()) {
            Yii::$app->response->statusCode = 404;
            return [
                'hasErrors' => $model->hasErrors(),
                'errors' => $model->getErrors(),
            ];
        }

        Yii::$app->response->statusCode = 201;
        return [
            'status' => 'success',
            'message' => 'REGISTER_SUCCESS',
        ];
    }

    /**
     * Login action.
     *
     * @return array
     */
    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        if (empty($username) || empty($password)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'DATA_EMPTY',
            ];
        }

        $user = User::findByUsername($username);
        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'USERNAME_NOT_FOUND',
            ];
        }

        if (!$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'WRONG_PASSWORD',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'LOGIN_SUCCESS',
            'data' => $user,
        ];
    }

    /**
     * Change password action.
     *
     * @return array
     */
    public function actionChangePassword()
    {
        $params = Yii::$app->request->post();
        $oldPassword = $params['oldPassword'] ?? '';
        $newPassword = $params['newPassword'] ?? '';

        if (empty($oldPassword) || empty($newPassword)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'DATA_EMPTY',
            ];
        }

        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => yii::$app->user->id,
        ]);

        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'USER_NOT_FOUND',
            ];
        }

        if (!$user->validatePassword($oldPassword)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'WRONG_PASSWORD',
            ];
        }

        $user->setPassword($newPassword);
        if (!$user->save(false)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'CHANGE_PASSWORD_FAILED',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'CHANGE_PASSWORD_SUCCESS',
        ];
    }

    /**
     * Send password reset token via email.
     *
     * @return array
     */
    public function actionSendTokenResetPasswordByEmail()
    {
        $params = Yii::$app->request->post();
        $email = $params['email'] ?? '';

        if (empty($email)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'DATA_EMPTY',
            ];
        }

        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $email,
        ]);

        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'EMAIL_NOT_FOUND',
            ];
        }

        $user->generatePasswordResetToken();
        if (!$user->save(false)) {
            return [
                'status' => 'error',
                'message' => 'TOKEN_NOT_SAVE',
            ];
        }

        $message = Yii::$app->mailer
            ->compose(['html' => 'passwordResetToken'], ['user' => $user])
            ->setFrom('YOUR@EMAIL.COM')
            ->setTo($email)
            ->setSubject('Password recovery');

        if (!$message->send()) {
            return [
                'status' => 'error',
                'message' => 'EMAIL_NOT_SEND',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'SEND',
        ];
    }

    /**
     * Reset password by token action.
     *
     * @return array
     */
    public function actionResetPasswordByToken()
    {
        $params = Yii::$app->request->post();
        $token = $params['token'] ?? '';
        $password = $params['password'] ?? '';

        if (empty($token) || empty($password)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'DATA_EMPTY',
            ];
        }

        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'password_reset_token' => $token,
        ]);

        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'USER_NOT_FOUND',
            ];
        }

        $user->setPassword($password);
        $user->removePasswordResetToken();
        if (!$user->save(false)) {
            Yii::$app->response->statusCode = 404;
            return [
                'status' => 'error',
                'message' => 'CHANGE_PASSWORD_FAILED',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'CHANGE_PASSWORD_SUCCESS',
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'status' => ['GET', 'HEAD', 'OPTIONS'],
            'login' => ['POST', 'OPTIONS'],
            'register' => ['POST', 'OPTIONS'],
            'change-password' => ['POST', 'OPTIONS'],
            'send-token-reset-password-by-email' => ['POST', 'OPTIONS'],
            'reset-password-by-token' => ['POST', 'OPTIONS'],
        ];
    }
}
