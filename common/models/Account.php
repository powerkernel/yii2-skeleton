<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\models;

use common\behaviors\UTCDateTimeBehavior;
use common\Core;
use yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for Account
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $seo_name
 * @property string $fullname
 * @property integer $fullname_changed
 * @property string $auth_key
 * @property string $access_token
 * @property string $email
 * @property string $new_email
 * @property string $new_email_code
 * @property string $phone
 * @property string $new_phone
 * @property string $new_phone_code
 * @property integer $role
 * @property string $language
 * @property string $timezone
 * @property string $status
 * @property \MongoDB\BSON\UTCDateTime $created_at
 * @property \MongoDB\BSON\UTCDateTime $updated_at
 *
 * @property mixed statusText
 */
class Account extends \yii\mongodb\ActiveRecord implements IdentityInterface
{

    const STATUS_ACTIVE = 'STATUS_ACTIVE';//10;
    const STATUS_SUSPENDED = 'STATUS_SUSPENDED';//20;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'accounts';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'seo_name',
            'fullname',
            'fullname_changed',
            'auth_key',
            'access_token',
            'email',
            'new_email',
            'new_email_code',
            'phone',
            'new_phone',
            'new_phone_code',
            'role',
            'language',
            'timezone',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * get id
     * @return \MongoDB\BSON\ObjectID|string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            UTCDateTimeBehavior::class,
        ];
    }

    /**
     * @return int timestamp
     */
    public function getUpdatedAt()
    {
        return $this->updated_at->toDateTime()->format('U');
    }

    /**
     * @return int timestamp
     */
    public function getCreatedAt()
    {
        return $this->created_at->toDateTime()->format('U');
    }

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_SUSPENDED => Yii::t('app', 'Suspended'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get user status
     * @param null $status
     * @return string
     */
    public function getStatusText($status = null)
    {
        if ($status === null)
            $status = $this->status;
        switch ($status) {
            case self::STATUS_ACTIVE:
                return Yii::t('app', 'Active');
                break;
            case self::STATUS_SUSPENDED:
                return Yii::t('app', 'Suspended');
                break;
            default:
                return Yii::t('app', 'Unknown');
                break;
        }
    }

    /**
     * color status text
     * @return mixed|string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        if ($status == self::STATUS_ACTIVE) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_SUSPENDED) {
            return '<span class="label label-warning">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname'], 'required'],
            [['fullname', 'email'], 'filter', 'filter' => 'trim'],

            [['email', 'new_email'], 'email'],
            [['email'], 'filter', 'filter' => 'strtolower'],
            [['phone'], 'match', 'pattern' => '/^\+[1-9][0-9]{9,14}$/'],

            [['fullname_changed', 'role'], 'integer'],
            [['seo_name', 'fullname', 'access_token', 'email', 'status'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['language'], 'string', 'max' => 5],

            [['timezone'], 'string', 'max' => 100],
            [['timezone'], 'in', 'range' => timezone_identifiers_list()],

            [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator'],

            /* update action */
            [['fullname', 'email', 'language', 'timezone'], 'required', 'on' => ['create', 'update']],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => Yii::t('app', 'ID'),
            'seo_name' => Yii::t('app', 'SEO name'),
            'fullname' => Yii::t('app', 'Full Name'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'role' => Yii::t('app', 'Role'),
            'language' => Yii::t('app', 'Language'),
            'timezone' => Yii::t('app', 'Timezone'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if ($insert) {
            $this->generateAuthKey();

            /* default timezone and language */
            $this->language = empty($this->language) ? Yii::$app->language : $this->language;
            $this->status = self::STATUS_ACTIVE;
            $this->timezone = empty($this->timezone) ? Yii::$app->timeZone : $this->timezone;
        }

        $this->seo_name = Core::generateSeoName($this->fullname);
        return parent::beforeSave($insert);
    }


    /**
     * Send email after account created
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $id = (string)$this->_id;
        if ($insert) {
            /* Admin */
            if (Account::find()->count() == 1) {
                $auth = Yii::$app->authManager;
                $admin = $auth->getRole('admin');
                $auth->assign($admin, $id);
            }
        }

        /* name changed ++ */
        if (in_array('fullname', array_keys($changedAttributes))) {
            /* if reach max, revert name */
            if (!$this->canChangeName()) {
                Yii::$app->mongodb->createCommand()
                    ->update('accounts', ['_id' => $this->_id], ['fullname' => $changedAttributes['fullname']]);
            } else {
                /* count changed ++ */
                Yii::$app->mongodb->createCommand()
                    ->update('accounts', ['_id' => $this->_id], ['fullname_changed' => $this->fullname_changed + 1]);
            }
        }
    }

    /**
     * check can change name
     * @return bool
     */
    public function canChangeName()
    {
        $max = Setting::getValue('maxNameChange');
        if ($this->fullname_changed < $max || $max == -1) {
            return true;
        }
        return false;
    }

    /**
     * Send email after created an account
     * @return bool
     */
    protected function sendMailNewUser()
    {
        if (!empty($this->email)) {
            Yii::$app->mailer->setViewPath(Yii::getAlias('@common') . '/mail');
            $subject = Yii::t('app', 'Welcome to {APP_NAME}', ['APP_NAME' => Yii::$app->name]);
            return Yii::$app->mailer
                //->compose('newUser', ['user' => $this])
                ->compose(
                    ['html' => 'new-user-html', 'text' => 'new-user-text'],
                    ['title' => $subject, 'user' => $this]
                )
                ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        }
        return false;
    }


    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * find by phone
     * @param string $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (is_array($id)) {
            $id = array_values($id)[0];
        }
        return static::findOne(['_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }


    /**
     * Generates new change email token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * Generates new change email token
     */
    public function generateChangeEmailToken()
    {
        $this->change_email_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Remove change email token
     */
    public function removeChangeEmailToken()
    {
        $this->change_email_token = null;
    }

    /**
     * remove access token
     */
    public function removeAccessToken()
    {
        $this->access_token = null;
    }

    /**
     * Finds out if change email token is valid
     *
     * @param string $token change email token
     * @return boolean
     */
    public static function isTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = (int)Setting::getValue('tokenExpiryTime');
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Finds user by change email token
     *
     * @param string $token change email token
     * @return static|null
     */
    public static function findByChangeEmailToken($token)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'change_email_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * account can be suspended?
     * @return bool
     */
    public function canSuspend()
    {
        if (
            (in_array((string)$this->_id, Yii::$app->params['rootAdmin']))
            or
            Yii::$app->user->id == $this->_id
            or
            $this->status == self::STATUS_SUSPENDED
        ) {
            return false;
        }
        return true;
    }


    /**
     * Get identifier type
     * @param $identifier
     * @return bool|int|string
     */
    public static function getIdentifierType($identifier){
        $patterns = [
            'phone' => '/^\+[1-9][0-9]{9,14}$/',
            'email' => '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/'
        ];
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $identifier)) {
                return $type;
            }
        }
        return false;
    }

}
