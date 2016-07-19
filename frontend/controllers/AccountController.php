<?php

namespace frontend\controllers;

use common\models\Account;
use common\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use yii\web\Controller;

/**
 * Class AccountController
 * @package frontend\controllers
 */
class AccountController extends Controller
{


    /**
     * The signup page
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if (!Yii::$app->params['account']['registrationDisabled']) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if($this->createUser(['name' => $model->name,'email' => $model->email])){
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Your account as been created successfully. Please check your email for login instructions.'));
                    return $this->redirect(Yii::$app->user->loginUrl);
                }
                else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. We\'re working on getting this fixed as soon as we can.'));
                }

            }
        }


        return $this->render('signup', [
            'model' => $model,
        ]);

    }


    /**
     * login
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    /**
     * create new user
     * @param $u
     * @return bool
     */
    protected function createUser($u)
    {
        $user = new Account();
        $user->fullname = $u['name'];
        $user->email = $u['email'];
        $user->setPassword();
        $user->generateAuthKey();
        return $user->save();
    }

    public function actionTest(){
        $user = new Account();
        $user->fullname = 'Gia Duy';
        $user->email = 'me@giaduy.info';
        $user->setPassword();
        $user->generateAuthKey();
        if(!$user->save()){
            var_dump($user->errors);
        }
    }


}