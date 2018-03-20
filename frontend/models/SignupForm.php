<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace frontend\models;


use common\Core;
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
        $captcha=[];
        if(Core::isReCaptchaEnabled()){
            $captcha=[
                ['captcha', 'required', 'message' => Yii::t('app', 'Prove you are NOT a robot')],
                ['captcha', ReCaptchaValidator::class, 'message' => Yii::t('app', 'Prove you are NOT a robot')]

            ];
        }
        $default= [
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'filter', 'filter' => 'ucwords'],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'unique', 'targetClass' => '\common\models\Account', 'message' => Yii::t('app', 'This email address has already been taken.')],
        ];

        return array_merge($default, $captcha);
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
