<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;


use Yii;
use yii\base\Model;

/**
 * Class ChangePasswordForm
 * @package common\models
 */
class ChangePasswordForm extends Model
{

    public $currentPassword;
    public $password;
    public $passwordConfirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'password', 'passwordConfirm'], 'required'],
            [['currentPassword', 'password', 'passwordConfirm'], 'string', 'min' => 6],

            ['password', 'compare', 'compareAttribute' => 'currentPassword', 'operator'=>'!=='],

            ['passwordConfirm', 'compare', 'compareAttribute' => 'password'],
            ['currentPassword', 'validatePassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currentPassword' => Yii::t('app', 'Old password'),
            'password' => Yii::t('app', 'New password'),
            'passwordConfirm' => Yii::t('app', 'Confirm new password')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->currentPassword)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect password.'));
            }
        }
        unset($params);
    }

    /**
     * change password
     * @return mixed
     */
    public function changePassword()
    {
        $user = Yii::$app->user->identity;
        $user->setPassword($this->password);
        return $user->save();
    }


}
