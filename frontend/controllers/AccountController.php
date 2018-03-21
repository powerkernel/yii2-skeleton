<?php

namespace frontend\controllers;

use common\components\AuthHandler;
use common\components\MainController;
use common\models\Account;
use common\models\Auth;

use common\models\Setting;
use frontend\models\ChangeEmailForm;
use frontend\models\ChangePasswordForm;
use frontend\models\ChangePhoneForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

use Yii;
use yii\filters\AccessControl;

/**
 * Class AccountController
 * @package frontend\controllers
 */
class AccountController extends MainController
{
    public $layout = 'account';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [

                    [
                        //'actions' => ['*'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['auth', 'signup', 'signin', 'login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login-as'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],

        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'signin' => [
                'class' => 'common\actions\SignInAction',
            ],
        ];
    }

    /**
     * @param $client \yii\authclient\ClientInterface
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * update email
     * @return string|\yii\web\Response
     */
    public function actionEmail(){
        $model = new ChangeEmailForm();
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['validate'])) {
                $model->setScenario('validation');
            }

            if ($model->validate()) {
                if (empty($model->code)) {
                    $model->setScenario('validation');
                    if ($model->setNewEmail()) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'A message with a 6-digit verification code was just sent to {EMAIL}', ['EMAIL' => $model->email]));
                    } else {
                        $model = new ChangeEmailForm();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'We cannot process your request at this time.'));
                    }
                } else {
                    if ($model->updateEmail()) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Your email address has been successfully updated.'));
                        return $this->redirect('index');
                    }
                }
            }
        }

        return $this->render('email', ['model' => $model]);
    }

    /**
     * change phone
     * @return string
     */
    public function actionPhone()
    {
        $model = new ChangePhoneForm();
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['validate'])) {
                $model->setScenario('validation');
            }

            if ($model->validate()) {
                if (empty($model->code)) {
                    $model->setScenario('validation');
                    if ($model->setNewPhone()) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'A message with a 6-digit verification code was just sent to {LOGIN}', ['LOGIN' => $model->phone]));
                    } else {
                        $model = new ChangePhoneForm();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'We cannot process your request at this time.'));
                    }
                } else {
                    if ($model->updatePhone()) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Your phone number has been successfully updated.'));
                        return $this->redirect('index');
                    }
                }
            }
        }

        return $this->render('phone', ['model' => $model]);
    }

    /**
     * login in as
     * @param $token
     * @return \yii\web\Response
     */
    public function actionLoginAs($token)
    {
        //if (Account::isTokenValid($token)) {
        $model = Account::findIdentityByAccessToken($token);
        if ($model) {
            Yii::$app->user->login($model);
        }
        //}
        return $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public
    function actionIndex()
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
    public
    function actionSignup()
    {
        if (Setting::getValue('passwordLessLogin')) {
            return $this->redirect(['login']);
        }
        //$this->layout = 'main';
        $this->layout = 'login';
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->createUser(['name' => $model->name, 'email' => $model->email])) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your account as been created successfully. Please check your email for login instructions.'));
                return $this->redirect(Yii::$app->user->loginUrl);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. We\'re working on getting this fixed as soon as we can.'));
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
    public
    function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('login', [
            //'model' => $model,
        ]);


    }


    /**
     * create new user
     * @param $u
     * @return bool
     */
    protected
    function createUser($u)
    {
        $user = new Account();
        $user->fullname = $u['name'];
        $user->email = $u['email'];
        return $user->save();
    }


}
