<?php

namespace frontend\controllers;

use common\models\Account;
use common\models\ChangeEmailForm;
use common\models\ChangePasswordForm;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
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
                        'actions' => ['signup', 'login', 'reset', 'reset-confirm', 'login-as'],
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
    public function actionPassword()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Success! Your Password has been changed!'));
        }
        return $this->render('password', ['model' => $model]);
    }

    /**
     * change email
     * @return string
     */
    public function actionEmail()
    {
        $model = new ChangeEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changeEmail()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'We sent a verification link to your new email address.'));
        }
        return $this->render('email', ['model' => $model]);
    }


    /**
     * @param $token
     * @return \yii\web\Response
     */
    public function actionEmailConfirm($token)
    {
        $user = Yii::$app->user->identity;
        if (Account::isTokenValid($token) == false || $user->change_email_token != $token) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid or expired token.'));
        } else {
            $user->email = $user->new_email;
            $user->new_email = null;
            $user->removeChangeEmailToken();
            if ($user->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Email successfully changed.'));
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, something went wrong. Please try again later.'));
            }
        }
        return $this->redirect(['/account/email']);
    }

    /**
     * login in as
     * @param $token
     * @return \yii\web\Response
     */
    public function actionLoginAs($token)
    {

        $model = Account::findIdentityByAccessToken($token);
        if ($model) {
            $model->removeAccessToken();
            $model->save();
            Yii::$app->user->login($model);
        }


        return $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {

        $model = Yii::$app->user->identity;
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'success' => true]);
        }

        if (Yii::$app->request->get('success')) {
            Yii::$app->session->setFlash('success', 'Profile updated successfully');
        }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * The signup page
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $this->layout = 'main';
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
        $this->layout = 'login';
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
     * reset password
     * @return string|\yii\web\Response
     */
    public function actionReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->goBack();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    /**
     * set new password
     * @param $token
     * @return string|\yii\web\Response
     */
    public function actionResetConfirm($token)
    {
        $this->layout = 'login';
        $model = new ResetPasswordForm();
        $account = Account::findByPasswordResetToken($token);
        if ($account) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->setPassword($account)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'New password was saved.'));
                return $this->redirect(['/account/login']);
            }
        }


        return $this->render('reset-confirm', [
            'model' => $model,
            'account' => $account
        ]);
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
        return $user->save();
    }


}