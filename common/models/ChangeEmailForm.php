<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;



use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;

/**
 * Class ChangeEmailForm
 * @package common\models
 */
class ChangeEmailForm extends Model {

    public $newEmail;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newEmail'], 'required'],

            ['newEmail', 'email'],
            ['newEmail', 'filter', 'filter' => 'trim'],
            ['newEmail', 'filter', 'filter' => 'strtolower'],
            ['newEmail', 'unique', 'targetAttribute'=>'email', 'targetClass' => 'common\models\Account', 'message' => Yii::t('app', 'This email address has already been taken.')],

            [['verifyCode'], 'required', 'message'=> Yii::t('app', 'Prove you are NOT a robot')],
            [['verifyCode'], ReCaptchaValidator::className(), 'message'=> Yii::t('app', 'Prove you are NOT a robot')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newEmail' => Yii::t('app', 'New email'),
            'verifyCode' => Yii::t('app', 'Verify Code'),
        ];
    }

    /**
     * set new email
     * @return bool
     */
    public function changeEmail()
    {
        $user= Yii::$app->user->identity;
        if (!Account::isChangeEmailTokenValid($user->change_email_token)) {
            $user->generateChangeEmailToken();
        }

        $user->new_email=$this->newEmail;

        if ($user->save()) {
            Yii::$app->language=$user->language;
            $subject=Yii::t('app', '[{APP_NAME}] Please verify your email address', ['APP_NAME'=> Yii::$app->name]);
            return Yii::$app->mailer->compose('changeEmail', ['title'=>$subject, 'user' => $user])
                ->setFrom([Setting::getValue('outgoingMail')])
                ->setTo($user->new_email)
                ->setSubject($subject)
                ->send();
        }

        return false;
    }
}
