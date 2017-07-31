<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_account = false;

    public $admin=false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['email'], 'required', 'on'=>['default', 'passwordLess']],
            [['password'], 'required', 'on'=>['default']],
            [['email'], 'email'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword', 'on'=>['default']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
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
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            }
            else if($account->status==Account::STATUS_SUSPENDED){
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
            if($this->scenario=='default'){
                return $this->defaultLogin();
            }
            else { // password less login
                return $this->passwordLessLogin();
            }
        } else {
            return false;
        }
    }

    /**
     * password login
     * @return bool
     */
    protected function defaultLogin(){
        $account=$this->getAccount();
        /* success login mask email as verified */
        $account->email_verified=1;
        $account->save();
        return Yii::$app->user->login($account, $this->rememberMe ? Setting::getValue('rememberMeDuration') : 0);
    }

    /**
     * password less login
     */
    protected function passwordLessLogin(){
        $login=new Login();
        $login->email=$this->email;
        if($this->rememberMe){
            $login->remember=true;
        }
        /* admin ? */
        if($this->admin){
            $login->admin=$this->admin;
        }

        /* save and send email */
        if($login->save()){
            Yii::$app->session->setFlash('info', Yii::t('app', 'We have emailed the link to login to {EMAIL}. Click on the button inside the email and you will be all set. Check spam box too if you can\'t find the email in your inbox.', ['EMAIL'=>$this->email]));
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'We can not email the link to login to {EMAIL}.', ['EMAIL'=>$this->email]));
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return Account|null
     */
    public function getAccount()
    {
        if ($this->_account === false) {
            $this->_account = Account::findByEmail($this->email);
        }
        return $this->_account;
    }

}
