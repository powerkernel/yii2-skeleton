<?php

namespace common\models;

use common\Core;
use yii;
use yii\base\NotSupportedException;
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
 */
class Account extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

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
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
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
            case self::STATUS_DELETED:
                return Yii::t('app', 'Deleted');
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

            [['seo_name', 'fullname', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['language'], 'string', 'max' => 5],

            [['timezone'], 'string', 'max' => 100],
            [['timezone'], 'in', 'range' => timezone_identifiers_list()],

            /* update action */
            [['fullname', 'language', 'timezone'], 'required', 'on' => 'update'],
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
        parent::beforeSave($insert);
        if ($insert) {
            /* default timezone and language */
            $this->language = Yii::$app->language;
            $this->status = self::STATUS_ACTIVE;
        }

        $this->seo_name = Core::generateSeoName($this->fullname);
        return true;
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
            if (in_array($this->id, Yii::$app->params['settings']['admins'])) {
                $auth = Yii::$app->authManager;
                $admin = $auth->getRole('admin');
                $auth->assign($admin, $this->id);
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
        $subject = Yii::t('app', 'Welcome to {APP_NAME}', ['APP_NAME' => Yii::$app->name]);
        return \Yii::$app->mailer->compose('newUser', ['user' => $this])
            ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
    }

    /**
     * Send password email
     * @return bool
     */
    public function sendPassword()
    {
        $subject = Yii::t('app', 'Your password has been changed');
        return \Yii::$app->mailer->compose('password', ['user' => $this])
            ->setFrom([Setting::getValue('outgoingMail') => \Yii::$app->name])
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
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = 3600;//Yii::$app->params['account']['tokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
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
     * Removes change email token
     */
    public function removeChangeEmailToken()
    {
        $this->change_email_token = null;
    }

    /**
     * Finds out if change email token is valid
     *
     * @param string $token change email token
     * @return boolean
     */
    public static function isChangeEmailTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = 3600;//Yii::$app->params['account']['tokenExpire'];
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
        if (!static::isChangeEmailTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'change_email_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * can delete user
     * @return bool
     */
    public function canDelete()
    {

//        if (
//            (in_array($this->id, Yii::$app->params['settings']['admins']) or $this->status == self::STATUS_DELETED)
//            or
//            Yii::$app->user->id == $this->id
//        ) {
//            return false;
//        }
        return true;
    }

    /**
     * is this user's password can be changed?
     * @return bool
     */
    public function canChangePassword()
    {
//        if (in_array($this->id, Yii::$app->params['settings']['admins'])) {
//            return false;
//        }
        return true;
    }

    /**
     * delete user
     * @return bool
     */
    public function deleteUser()
    {
        if ($this->canDelete()) {
            $this->status = Account::STATUS_DELETED;
            $this->save();
            return true;
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Administrator account cannot be deleted.'));
        return false;

    }


}
