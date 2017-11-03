<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * PassLoginForm
 */
class PassLoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;
    private $_account = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['login', 'password'], 'required'],
            [['login'], 'match', 'pattern' => '/^(\+[1-9][0-9]{9,14})|([a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?)$/', 'message' => Yii::t('app', 'Email or phone number is invalid. Note that phone number should begin with a country prefix code.')],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword', 'on' => ['default']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('app', 'Email or phone number'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember Me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $account = $this->getAccount();
            if (!$account || !$account->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect login credentials.'));
            } else if ($account->status == Account::STATUS_SUSPENDED) {
                $this->addError($attribute, Yii::t('app', 'Your account has been suspended.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return $this->signIn();
        } else {
            return false;
        }
    }

    /**
     * password login
     * @return bool
     */
    protected function signIn()
    {
        $account = $this->getAccount();
        /* success login mask email as verified */
        if($this->getType()=='email' && $account->email_verified!=1){
            $account->email_verified = 1;
            $account->save();
        }
        return Yii::$app->user->login($account, $this->rememberMe ? Setting::getValue('rememberMeDuration') : 0);
    }



    /**
     * Finds user by or phone
     *
     * @return Account|null
     */
    public function getAccount()
    {

        if ($this->_account === false) {
            if($this->getType()=='email'){
                $this->_account = Account::findByEmail($this->login);
            }
            elseif($this->getType()=='phone'){
                $this->_account = Account::findByPhone($this->login);
            }
        }
        return $this->_account;
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

}
