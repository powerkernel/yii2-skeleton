<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

namespace frontend\models;

use common\Core;
use common\models\Setting;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;

/**
 * Class ChangeEmailForm
 * @package frontend\models
 */
class ChangeEmailForm extends Model
{

    public $email;
    public $code;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $captcha = [];
        if (Core::isReCaptchaEnabled()) {
            $captcha = [
                ['captcha', 'required', 'message' => Yii::t('app', 'Prove you are NOT a robot')],
                ['captcha', ReCaptchaValidator::class, 'message' => Yii::t('app', 'Prove you are NOT a robot')]
            ];
        }

        $default = [
            [['email'], 'required'],
            [['code'], 'required', 'on' => ['validation']],

            [['email'], 'email'],
            [['code'], 'match', 'pattern' => '/^[0-9]{6}$/'],
            [['code'], 'validateCode', 'skipOnError' => true],
            [['email'], 'unique', 'targetAttribute' => 'email', 'targetClass' => 'common\models\Account', 'message' => Yii::t('app', 'This email address has already been taken.')],
        ];

        return array_merge($default, $captcha);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Enter your email address'),
            'captcha' => Yii::t('app', 'Verify Code'),
            'code' => \Yii::t('app', 'Verification code'),
        ];
    }

    /**
     * set new email
     * @return bool
     */
    public function setNewEmail()
    {
        $model = Yii::$app->user->identity;
        $model->new_email = $this->email;
        $model->new_email_code = rand(100000, 999999);
        /* send email code */
        Yii::$app->mailer->setViewPath(Yii::getAlias('@common') . '/mail');
        $subject = Yii::t('app', 'Verify your email address at {APP_NAME}', ['APP_NAME' => Yii::$app->name]);
        $sent = Yii::$app->mailer
            ->compose(
                ['html' => 'change-email-html', 'text' => 'change-email-text'],
                ['title' => $subject, 'model' => $model]
            )
            ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
        if ($sent) {
            return $model->save();
        }
        return false;
    }

    /**
     * update phone
     * @return mixed
     */
    public function updateEmail()
    {
        $model = Yii::$app->user->identity;
        $model->email = $this->email;
        $model->new_email = null;
        $model->new_email_code = null;
        return $model->save();
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateCode($attribute, $params, $validator)
    {
        $account = Yii::$app->user->identity;
        if ($this->code != $account->new_email_code) {
            $this->addError($attribute, Yii::t('app', 'Wrong code. Please try again.'));
        }
        unset($params, $validator);
    }


}
