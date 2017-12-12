<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\actions;


use common\models\Account;
use common\models\Login;
use common\models\Setting;
use Yii;
use yii\base\Action;

/**
 * Class LoginAction
 * @package common\actions
 */
class LoginAction extends Action
{
    /**
     * email login
     * @param $email
     * @param $token
     * @param bool $remember
     * @return mixed
     */
    public function run($email, $token, $remember = true)
    {
        $login = Login::find()->where(['email' => $email, 'token' => $token, 'status' => Login::STATUS_NEW])->one();
        if (!$this->validateToken($token) or !$login) {
            $this->setErrorMessage();
            return $this->returnLoginPage();
        } else {
            /* success */
            $login->status = Login::STATUS_USED;
            $login->save();

            $account = Account::findByEmail($email);
            if ($account) {
                return $this->existingAccountLogin($account, $remember);
            } else {
                return $this->newAccountLogin($email, $remember);
            }

        }

    }

    /**
     * existing account login
     * @param $account
     * @param $remember
     * @return \yii\web\Response
     */
    protected function existingAccountLogin($account, $remember)
    {
        if (Yii::$app->user->login($account, $remember ? Setting::getValue('rememberMeDuration') : 0)) {
            return $this->controller->redirect(Yii::$app->user->returnUrl);
        } else {
            return $this->returnLoginPage();
        }
    }

    /**
     * new account login
     * @param $email
     * @param $remember
     * @return \yii\web\Response
     */
    protected function newAccountLogin($email, $remember)
    {
        $account = new Account();
        $account->fullname = $email;
        $account->email = $email;
        $account->email_verified = 1;
        if ($account->save()) {
            if (Yii::$app->user->login($account, $remember ? Setting::getValue('rememberMeDuration') : 0)) {
                return $this->controller->redirect(Yii::$app->user->returnUrl);
            }
        }
        return $this->returnLoginPage();
    }

    /**
     * validate token
     * @param $token
     * @return bool
     */
    protected function validateToken($token)
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
     * set error message
     */
    protected function setErrorMessage()
    {
        Yii::$app->session->setFlash('error', Yii::t('app', 'Your login link has been expired. Please login again.'));
    }

    /**
     * return login page
     * @return \yii\web\Response
     */
    protected function returnLoginPage()
    {
        Yii::$app->user->logout();
        return $this->controller->redirect(Yii::$app->user->loginUrl);
    }
}