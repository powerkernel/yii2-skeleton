<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\models;


use himiklab\yii2\recaptcha\ReCaptchaValidator;
use yii\base\Model;
use Yii;

/**
 * Class SignupForm
 * @package frontend\models
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'filter', 'filter' => 'ucwords'],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'unique', 'targetClass' => '\common\models\Account', 'message' => Yii::t('app', 'This email address has already been taken.')],

            ['captcha', 'required', 'message' => Yii::t('app', 'Prove you are NOT a robot')],
            ['captcha', ReCaptchaValidator::className(), 'message' => Yii::t('app', 'Prove you are NOT a robot')]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'captcha' => Yii::t('app', 'Verify Code'),
        ];
    }

} 