<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Setting;
use Yii;
use common\models\Account;
use common\models\AccountSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $parent = parent::behaviors();
        return array_merge($parent, [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'suspend' => ['POST'],
                    'unsuspend' => ['POST'],
                    'new-password' => ['POST'],
                ],
            ],
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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
     */
    public function actionLoginAs($id){
        $model=$this->findModel($id);
        $model->generateAccessToken();
        $model->save(false);
        return $this->redirect(Yii::$app->urlManagerFrontend->createUrl(['/account/login-as', 'token'=>$model->access_token]));
    }

    /**
     * suspend account
     * @param $id
     * @return \yii\web\Response
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
     */
    public function actionNewPassword($id)
    {
        $model = $this->findModel($id);
        $model->setPassword();
        if ($model->save()) {
            /* send mail */
            $subject = Yii::t('app', '[{APP_NAME}] Password changed', ['APP_NAME' => Yii::$app->name]);
            $mailSent = Yii::$app->mailer->compose('passwordChanged', ['user' => $model])
                ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
                ->setTo($model->email)
                ->setSubject($subject)
                ->send();
            if($mailSent){
                Yii::$app->session->setFlash('success', Yii::t('app', 'New password has been sent.'));
            }
            else {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'New password set successfully ({PASS}). Error while sending email to user.', ['PASS'=>$model->passwordText]));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. Please try again later.'));
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
