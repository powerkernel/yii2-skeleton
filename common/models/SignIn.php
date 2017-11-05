<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\models;

use modernkernel\sms\components\AwsSMS;
use Yii;

/**
 * This is the model class for table "{{%core_login}}".
 *
 * @property string $login
 * @property string $code
 * @property integer $attempts
 * @property string $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 */
class SignIn extends SignInBase
{


    const STATUS_NEW = 'STATUS_NEW';//10;
    const STATUS_USED = 'STATUS_USED';//20;

    public $captcha;


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_USED => Yii::t('app', 'Used'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->status;
        $list = self::getStatusOption();
        if (!empty($status) && in_array($status, array_keys($list))) {
            return $list[$status];
        }
        return Yii::t('app', 'Unknown');
    }

    /**
     * get status color text
     * @return string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        $list = self::getStatusOption();

        $color = 'default';
        if ($status == self::STATUS_NEW) {
            $color = 'primary';
        }
        if ($status == self::STATUS_USED) {
            $color = 'danger';
        }

        if (!empty($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-' . $color . '">' . $list[$status] . '</span>';
        }
        return '<span class="label label-' . $color . '">' . Yii::t('app', 'Unknown') . '</span>';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            $date = [
                [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator']
            ];
        } else {
            $date = [
                [['created_at', 'updated_at'], 'integer']
            ];
        }

        /* login */
        if (!empty(\modernkernel\sms\models\Setting::getValue('aws_access_key') && !empty(\modernkernel\sms\models\Setting::getValue('aws_secret_key')))) {
            $login = [
                [['login'], 'match', 'pattern' => '/^(\+[1-9][0-9]{9,14})|([a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?)$/', 'message' => Yii::t('app', 'Email or phone number is invalid. Note that phone number should begin with a country prefix code.')],
            ];

        } else {
            $login = [
                [['login'], 'email'],
            ];
        }


        $default = [
            [['attempts'], 'default', 'value' => 0],

            [['login'], 'required'],
            [['code'], 'string', 'length' => 6],

            [['login'], 'trim'],
            //[['login'], 'match', 'pattern' => '/^(\+[1-9][0-9]{9,14})|([a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?)$/', 'message'=>Yii::t('app', 'Email or phone number is invalid. Note that phone number should begin with a country prefix code.')],
            [['code'], 'match', 'pattern' => '/^[0-9]{6}$/'],

            [['status'], 'string', 'max' => 20],
            //['captcha', ReCaptchaValidator::className(), 'message' => Yii::t('app', 'Prove you are NOT a robot')]
        ];

        return array_merge($default, $date, $login);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        $default = [
            'code' => \Yii::t('app', 'Verification code'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];

        if (!empty(\modernkernel\sms\models\Setting::getValue('aws_access_key') && !empty(\modernkernel\sms\models\Setting::getValue('aws_secret_key')))) {
            $login = [
                'login' => \Yii::t('app', 'Email or phone number'),
            ];
        } else {
            $login = [
                'login' => \Yii::t('app', 'Email'),
            ];
        }

        return array_merge($default, $login);
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->code = (string)rand(100000, 999999);
            $this->status = self::STATUS_NEW;
            /* send code */
            if ($this->getType() == 'phone') {
                return $this->sendSMS();
            }
            if ($this->getType() == 'email') {
                return $this->sendEmail();
            }
            /* cannot send code ? */
            return false;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        if ($this->attempts >= 3) {
            $this->delete();
        }
    }

    /**
     * get login type
     * @return bool|string
     */
    public function getType()
    {
        $patterns = [
            'phone' => '/^\+[1-9][0-9]{9,14}$/',
            'email' => '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/'
        ];
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $this->login)) {
                return $type;
            }
        }
        return false;
    }

    /**
     * send SMS code
     * @return bool
     */
    protected function sendSMS()
    {
        return (new AwsSMS())->send(
            $this->login,
            Yii::t('app', '{APP}: Your verification code is {CODE}', ['CODE' => $this->code, 'APP'=>Yii::$app->name]
            ));
    }

    /**
     * send email code
     * @return bool
     */
    public function sendEmail()
    {
        $subject = Yii::t('app', 'Log in to {APP}', ['APP' => Yii::$app->name]);
        return Yii::$app->mailer
            ->compose(
                ['html' => '@common/mail/signin-email-html', 'text' => '@common/mail/signin-email-text'],
                ['model' => $this]
            )
            ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo($this->login)
            ->setSubject($subject)
            ->send();
    }
}
