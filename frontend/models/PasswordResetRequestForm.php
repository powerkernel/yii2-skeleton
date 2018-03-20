<?php
namespace frontend\models;

use common\Core;
use common\models\Account;
use common\models\Setting;
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
        $captcha=[];
        if(Core::isReCaptchaEnabled()){
            $captcha=[
                ['verifyCode', 'required', 'message' => Yii::t('app', 'Prove you are NOT a robot')],
                ['verifyCode', ReCaptchaValidator::class, 'message' => Yii::t('app', 'Prove you are NOT a robot')]

            ];
        }

        $default= [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'common\models\Account',
                'filter' => ['status' => Account::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no user with such email.')
            ],
        ];

        return array_merge($default, $captcha);
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
            if (!Account::isTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $email= Yii::$app->mailer
                    //->compose('passwordResetToken', ['user' => $user])
                    ->compose(
                        ['html' => 'password-reset-token-html', 'text' => 'password-reset-token-text'],
                        ['user' => $user]
                    )
                    ->setFrom([Setting::getValue('outgoingMail') => \Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject(Yii::t('app', 'Password reset for {APP_NAME}', ['APP_NAME'=>\Yii::$app->name]));
                return $email->send();                    
            }
        }

        return false;
    }
}
