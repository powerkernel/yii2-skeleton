<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\widgets;

use common\models\Account;
use common\models\Setting;
use Yii;
use yii\base\Widget;

/**
 * Class SignIn
 * @package common\widgets
 */
class SignIn extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run(); // TODO: Change the autogenerated stub
        $model = new \common\models\CodeVerification();
        $validation = new \common\forms\CodeVerificationForm();

        /* $validation */
        if ($validation->load(Yii::$app->request->post()) && $validation->validate()) {
            $this->login($validation);
        }

        /* has client auth ? */
        $client=false;
        if(Yii::$app->authClientCollection->hasClient('facebook') or Yii::$app->authClientCollection->hasClient('google')){
            $client=true;
        }

        return $this->render('sign-in', ['model' => $model, 'validation' => $validation, 'client'=>$client]);
    }

    /**
     * Login
     * @param $model
     * @return mixed|void|\yii\web\Response
     */
    protected function login($model)
    {
        if ($model->type == 'email') {
            $account = Account::findByEmail($model->identifier);
        } else {
            $account = Account::findByPhone($model->identifier);
        }
        $remember=1;
        if ($account) {
            $this->existingAccountLogin($account, $remember);
        } else {
            $this->newAccountLogin($model, $remember);
        }
    }

    /**
     * existing account login
     * @param $account
     * @param $remember
     * @return mixed|\yii\web\Response
     */
    protected function existingAccountLogin($account, $remember)
    {
        if (Yii::$app->user->login($account, $remember ? Setting::getValue('rememberMeDuration') : 0)) {
            return Yii::$app->controller->redirect(Yii::$app->user->returnUrl);
        } else {
            return $this->returnLoginPage();
        }
    }

    /**
     * new account login
     * @param $signin \common\models\CodeVerification
     * @param integer $remember
     * @return mixed
     */
    protected function newAccountLogin($signin, $remember)
    {
        $account = new Account();
        $account->fullname = $signin->identifier;
        if($signin->type=='email'){
            $account->email = $signin->identifier;

        }
        else {
            $account->phone = $signin->identifier;
        }

        if ($account->save()) {
            if (Yii::$app->user->login($account, $remember ? Setting::getValue('rememberMeDuration') : 0)) {
                return Yii::$app->controller->redirect(Yii::$app->user->returnUrl);
            }
        }
        return $this->returnLoginPage();
    }

    /**
     * return login page
     * @return mixed
     */
    protected function returnLoginPage()
    {
        Yii::$app->user->logout();
        return Yii::$app->controller->redirect(Yii::$app->user->loginUrl);
    }

}
