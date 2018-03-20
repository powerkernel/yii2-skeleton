<?php

namespace backend\controllers;

use common\components\AuthHandler;
use common\models\LoginForm;
use common\models\Setting;
use conquer\select2\Select2Action;
use Yii;
use common\models\Account;
use common\models\AccountSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends BackendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $adminRules = parent::behaviors()['access']['rules'];
        $rules = array_merge([
            [
                'actions' => ['login', 'auth', 'signin'],
                'allow' => true,
                'roles' => ['?']
            ],
        ], $adminRules);
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => $rules,
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
            'list' => [
                'class' => Select2Action::class,
                'dataCallback' => [$this, 'listUser'],
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
        $model->admin=true;
        $model->setScenario('default');
        $passLess=Setting::getValue('passwordLessLogin');
        if($passLess){
            $model->setScenario('passwordLess');
        }

        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                return $this->goBack();
            }
            else {
                return $this->redirect(['login']);
            }

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Account model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id)

        ]);
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Account();
        $model->setScenario('create');
        $model->language = Yii::$app->language;
        $model->timezone = Yii::$app->timeZone;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * login as this user
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionLoginAs($id)
    {
        $model = $this->findModel($id);
        if(empty($model->access_token)){
            $model->generateAccessToken();
            $model->save(false);
        }
        return $this->redirect(Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login-as', 'token' => $model->access_token]));
    }

    /**
     * suspend account
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSuspend($id)
    {
        $model = $this->findModel($id);
        if ($model->canSuspend()) {
            $model->status = Account::STATUS_SUSPENDED;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Done! Account suspended.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. Please try again later.'));
            }

        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Administrator account cannot be suspended.'));
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * un-suspend account
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUnsuspend($id)
    {
        $model = $this->findModel($id);
        $model->status = Account::STATUS_ACTIVE;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Done! Account is now activated.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. Please try again later.'));
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * send new password to user
     * @param $id
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionNewPassword($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Account::STATUS_SUSPENDED) {
            $model->setPassword();
            if ($model->save()) {
                /* send mail */
                $subject = Yii::t('app', '[{APP_NAME}] Password changed', ['APP_NAME' => Yii::$app->name]);
                $mailSent = Yii::$app->mailer
                    //->compose('passwordChanged', ['user' => $model])
                    ->compose(
                        ['html' => 'password-changed-html', 'text' => 'password-changed-text'],
                        ['user' => $model]
                    )
                    ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
                    ->setTo($model->email)
                    ->setSubject($subject)
                    ->send();
                if ($mailSent) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'New password has been sent.'));
                } else {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'New password set successfully ({PASS}). Error while sending email to user.', ['PASS' => $model->passwordText]));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. Please try again later.'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Before you can send the password, restore the suspended account.'));
        }


        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
