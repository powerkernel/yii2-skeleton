<?php

namespace common\models;

use common\Core;
use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%core_account}}".
 *
 * @property integer $id
 * @property string $seo_name
 * @property string $fullname
 * @property integer $fullname_changed
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $email_verified
 * @property string $new_email
 * @property string $change_email_token
 * @property integer $role
 * @property string $language
 * @property string $timezone
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property mixed statusText
 * @property Auth[] $auths
 */
class Account extends ActiveRecord implements IdentityInterface
{

    const STATUS_ACTIVE = 10;
    const STATUS_SUSPENDED = 20;

    public $passwordText;
    public $emailNewAccount = true;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_account}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'email'], 'required'],
            [['fullname', 'email'], 'filter', 'filter' => 'trim'],

            [['fullname_changed', 'role', 'status', 'created_at', 'updated_at'], 'integer'],

            [['seo_name', 'fullname', 'password_hash', 'password_reset_token', 'access_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['language'], 'string', 'max' => 5],

            [['timezone'], 'string', 'max' => 100],
            [['timezone'], 'in', 'range' => timezone_identifiers_list()],

            /* update action */
            [['fullname', 'email', 'language', 'timezone'], 'required', 'on' => ['create','update']],
            [['fullname', 'email', 'language', 'timezone'], 'required', 'on' => 'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'seo_name' => Yii::t('app', 'SEO name'),
            'fullname' => Yii::t('app', 'Full Name'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'role' => Yii::t('app', 'Role'),
            'language' => Yii::t('app', 'Language'),
            'timezone' => Yii::t('app', 'Timezone'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password = null)
    {
        $this->passwordText = $password;
        if ($this->passwordText === null) {
            $this->passwordText = Yii::$app->security->generateRandomString(9);
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->passwordText);
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
            $this->setPassword();
            $this->generateAuthKey();

            /* default timezone and language */
            $this->language = empty($this->language)?Yii::$app->language:$this->language;
            $this->status = self::STATUS_ACTIVE;
            $this->timezone = empty($this->timezone)?Yii::$app->timeZone:$this->timezone;
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

        if ($insert) {
            if ($this->emailNewAccount) {
                $this->sendMailNewUser();
            }

            /* Admin */
            if (in_array($this->id, Yii::$app->params['rootAdmin'])) {
                $auth = Yii::$app->authManager;
                $admin = $auth->getRole('admin');
                $auth->assign($admin, $this->id);
                Yii::$app->session->setFlash('info', Yii::t('app', 'Your admin account password is {PASS}', ['PASS'=>$this->passwordText]));
            }
        }

        /* name changed ++ */
        if (in_array('fullname', array_keys($changedAttributes))) {
            /* if reach max, revert name */
            if (!$this->canChangeName()) {
                $cmd = Yii::$app->db->createCommand();
                $cmd->update('{{%core_account}}', ['fullname' => $changedAttributes['fullname']], ['id' => $this->id]);
                $cmd->execute();
            } else {
                /* count changed ++ */
                $cmd = Yii::$app->db->createCommand();
                $cmd->update('{{%core_account}}', ['fullname_changed' => $this->fullname_changed + 1], ['id' => $this->id]);
                $cmd->execute();
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

        Yii::$app->mailer->setViewPath(Yii::getAlias('@common'). '/mail');
        //Yii::$app->mailer->htmlLayout = '@common/mail/layouts/html';

        $subject = Yii::t('app', 'Welcome to {APP_NAME}', ['APP_NAME' => Yii::$app->name]);
        return Yii::$app->mailer
            //->compose('newUser', ['user' => $this])
            ->compose(
                ['html' => 'new-user-html', 'text' => 'new-user-text'],
                ['title'=>$subject, 'user' => $this]
            )
            ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
    }



    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }
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
    public function getId()
    {
        return $this->getPrimaryKey();
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
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }



    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
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
    public function removeAccessToken(){
        $this->access_token=null;
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
            (in_array($this->id, Yii::$app->params['rootAdmin']))
            or
            Yii::$app->user->id == $this->id
            or
            $this->status==self::STATUS_SUSPENDED
        ) {
            return false;
        }
        return true;
    }


    /**
     * @return yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }




}
