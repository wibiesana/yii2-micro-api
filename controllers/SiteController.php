<?php

namespace app\controllers;

use app\models\User;
use yii;
use yii\rest\ActiveController;

class SiteController extends ActiveController
{
    public $modelClass = 'app\models\LoginForm';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator'] = [
            'class' => yii\filters\auth\HttpBearerAuth::className(),
            'except' => [
                'login',
                'register',
                'index',
                'reset-password',
                'send-email-reset-password',
            ],
        ];

        return $behaviors;
    }
    protected function verbs()
    {

        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'], //instead of  'index' => ['GET', 'HEAD']
            'login' => ['POST', 'OPTIONS'],
            'register' => ['POST', 'OPTIONS'],
            'reset-password' => ['POST', 'OPTIONS'],
            'set-password' => ['POST', 'OPTIONS'],
            'send-email-reset-password' => ['POST', 'OPTIONS'],
        ];

    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /**
     * i dex.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return 'Welcome Admin!';
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */

    public function actionRegister()
    {
        $model = new \app\models\SignupForm();
        $model->attributes = Yii::$app->request->post();

        if ($model->signup()) {
            $response['isSuccess'] = 201;
            $response['message'] = 'REGISTER_SUCCESS';
            return $response;
        } else {
            Yii::$app->response->statusCode = 404;
            $response['hasErrors'] = $model->hasErrors();
            $response['errors'] = $model->getErrors();
            return $response;
        }
    }

    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        $username = $params['username'] ?? "";
        $password = $params['password'] ?? "";
//        you can use email or username to login
//        $user = User::findByEmail($username);
        $user = User::findByUsername($username);
        if (!empty($user)) {
            if ($user->validatePassword($password)) {
                $response = [
                    'status' => 'success',
                    'message' => 'LOGIN_SUCCESS!',
                    'data' => $user,
                ];
            } else {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'message' => 'WRONG_PASSWORD',
                ];
            }
        } else {
            Yii::$app->response->statusCode = 404;
            $response = [
                'status' => 'error',
                'message' => 'USERNAME_NOT_FOUND',
            ];
        }
        return $response;
    }

    public function actionResetPassword()
    {
        $params = Yii::$app->request->post();
        $token = $params['token'];
        $password = $params['password'];
        if (!empty($token) || empty($password)) {
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'password_reset_token' => $token,
            ]);

            if (!$user) {
                // jika username tidak ada maka
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'message' => 'NOT_FOUND',
                ];
                return $response;
            }

            $user->setPassword($password);
            $user->removePasswordResetToken();
            if ($user->save(false)) {
                $response = [
                    'status' => 'success',
                    'message' => 'PASSWORD_CHANGE',
                ];
            } else {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'message' => 'FAILED_CHANGE_PASSWORD',
                ];
            }
        }
        return $response;
    }

    public function actionChangePassword()
    {
        $params = Yii::$app->request->post();
        $oldPassword = $params['oldPassword'];
        $newPassword = $params['newPassword'];
        // validasi jika tidak kosong
        if (!empty($token) || empty($password)) {
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'id' => \yii::$app->user->id,
            ]);

            if (!$user) {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'message' => 'NOT_FOUND',
                ];
            } else {
                if ($user->validatePassword($oldPassword)) {
                    $user->setPassword($newPassword);
                    if ($user->save(false)) {
                        $response = [
                            'status' => 'success',
                            'message' => 'PASSWORD_CHANGE',
                        ];
                    } else {
                        Yii::$app->response->statusCode = 404;
                        $response = [
                            'status' => 'error',
                            'message' => 'FAILED_CHANGE_PASSWORD',
                        ];
                    }
                } else {
                    Yii::$app->response->statusCode = 404;
                    $response = [
                        'status' => 'error',
                        'password' => 'WRONG_PASSWORD',
                    ];
                }
            }
        }
        return $response;
    }

    public function actionSendEmailResetPassword()
    {
        $params = Yii::$app->request->post();
        $email = $params['email'];
        if (!empty($email)) {
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $email,
            ]);

            if (!$user) {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'message' => 'NOT_FOUND',
                ];
                return $response;
            }
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
                if (!$user->save(false)) {
                    $response = [
                        'status' => 'error',
                        'message' => 'NOT_SAVE',
                    ];
                    return $response;
                }
            }

            $message = Yii::$app->mailer->compose(
                ['html' => 'passwordResetToken'],
                ['user' => $user]
            )
                ->setFrom('no_reply@admin.com')
                ->setTo($email)
                ->setSubject('Password recovery');

            if ($message->send()) {
                $response = [
                    'status' => 'success',
                    'message' => 'EMAIL_SEND',
                ];
            } else {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'status' => 'error',
                    'email' => 'EMAIL_NOT_SEND',
                ];
            }

        }
        return $response;
    }


}
