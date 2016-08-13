<?php

namespace backend\controllers;

use common\models\LoginForm;
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
                Yii::$app->session->setFlash('success', 'Done! Account suspended.');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, something went wrong. Please try again later.');
            }

        } else {
            Yii::$app->session->setFlash('error', 'Administrator account cannot be suspended.');
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
            Yii::$app->session->setFlash('success', 'Done! Account is now activated.');
        } else {
            Yii::$app->session->setFlash('error', 'Sorry, something went wrong. Please try again later.');
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
