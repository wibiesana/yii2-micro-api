<?php

namespace app\models;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'name'], 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],

            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 20],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 50],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = \str_replace(' ', '', $this->username);
        $user->email = \str_replace(' ', '', $this->email);
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save(false)) {
            $profile = new \app\models\Profile();
            $profile->user_id = $user->id;
            $profile->name = $this->name;
            $profile->save(false);
            return $user;
        }
        return null;
    }
}
