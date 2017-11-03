<?php

namespace frontend\controllers;

use common\components\AuthHandler;
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
                        //'actions' => ['*'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['auth', 'signup', 'signin', 'login', 'reset', 'reset-confirm', 'login-as'],
                        'allow' => true,
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
     * change email
     * @return string
     */
    public function actionPassword()
    {
        if (Setting::getValue('passwordLessLogin')) {
            return $this->redirect(['index']);
        }
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
                    }
                    else {
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
 * @param $token
 * @return \yii\web\Response
 */
public
function actionEmailConfirm($token)
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
    return $this->redirect(['account/index']);
}

/**
 * login in as
 * @param $token
 * @return \yii\web\Response
 */
public
function actionLoginAs($token)
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
 * @param string $remove
 * @return string
 */
public
function actionLinked($remove = '')
{
    $model = Yii::$app->user->identity;
    $auths = [];
    if ($model->auths) {
        foreach ($model->auths as $auth) {
            if (is_a($auth, '\yii\mongodb\ActiveRecord')) {
                $auths[$auth->source] = (string)$auth->_id;
            } else {
                $auths[$auth->source] = $auth->id;
            }


            if (!empty($remove) && $remove == $auth->id) {
                Auth::findOne($remove)->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', '{SOURCE} account removed.', ['SOURCE' => ucfirst($auth->source)]));
                //$auths[$auth->source]=null;
                return $this->redirect(['linked']);
            }
        }
    }


    return $this->render('linked', ['model' => $model, 'auths' => $auths]);
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
 * reset password
 * @return string|\yii\web\Response
 */
public
function actionReset()
{
    if (Setting::getValue('passwordLessLogin')) {
        return $this->redirect(['login']);
    }
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
public
function actionResetConfirm($token)
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
protected
function createUser($u)
{
    $user = new Account();
    $user->fullname = $u['name'];
    $user->email = $u['email'];
    return $user->save();
}


}