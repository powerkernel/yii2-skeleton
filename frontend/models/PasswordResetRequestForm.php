<?php
namespace frontend\models;

use common\models\Account;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'verifyCode' => Yii::t('app', 'Verify Code'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'common\models\Account',
                'filter' => ['status' => Account::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no user with such email.')
            ],

            [['verifyCode'], 'required', 'message'=> Yii::t('app', 'Prove you are NOT a robot')],
            [['verifyCode'], ReCaptchaValidator::className(), 'message'=> Yii::t('app', 'Prove you are NOT a robot')]
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {

        $user = Account::findOne([
            'status' => Account::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!Account::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $email= Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                    ->setFrom([Yii::$app->params['settings']['supportEmail'] => \Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject(Yii::t('app', 'Password reset for {APPNAME}', ['APPNAME'=>\Yii::$app->name]));
                return $email->send();                    
            }
        }

        return false;
    }
}
