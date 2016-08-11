<?php

namespace frontend\controllers;

use common\models\Account;
use common\models\ChangeEmailForm;
use common\models\ChangePasswordForm;
use common\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class AccountController
 * @package frontend\controllers
 */
class AccountController extends Controller
{
    public $layout = 'account';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['*'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }


    /**
     * change email
     * @return string
     */
    public function actionPassword(){
        $model=new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app','Success! Your Password has been changed!'));
        }
        return $this->render('password', ['model'=>$model]);
    }

    public function actionEmail(){
        $model=new ChangeEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changeEmail()) {
            Yii::$app->session->setFlash('info', Yii::t('app','We sent a verification link to your new email address.'));
        }
        return $this->render('email', ['model'=>$model]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {

        $model=Yii::$app->user->identity;
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'success'=>true]);
        }

        if(Yii::$app->request->get('success')){
            Yii::$app->session->setFlash('success', 'Profile updated successfully');
        }

        return $this->render('index', ['model'=>$model]);
    }

    /**
     * The signup page
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if (!Yii::$app->params['account']['registrationDisabled']) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($this->createUser(['name' => $model->name, 'email' => $model->email])) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Your account as been created successfully. Please check your email for login instructions.'));
                    return $this->redirect(Yii::$app->user->loginUrl);
                } else {
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


}