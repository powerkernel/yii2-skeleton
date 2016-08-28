<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\controllers;

use common\Core;
use Yii;
use common\models\Blog;
use common\models\BlogSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{

    public $layout = 'account';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['staff'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['manage', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['author'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main';
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * authors manage their blog posts
     * @return string
     */
    public function actionManage()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionView($id, $name=null)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        if ($name != $model->slug) {
            return $this->redirect($model->viewUrl, 301);
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->status == Blog::STATUS_PUBLISHED) {
                return $this->redirect($model->viewUrl);
            }
            return $this->redirect(['manage']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('updateBlog', ['model' => $model])) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if ($model->status == Blog::STATUS_PUBLISHED) {
                    return $this->redirect($model->viewUrl);
                }
                return $this->redirect(['manage']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to perform this action.'));


    }

    /**
     * Deletes an existing Blog model.
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
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $condition = ['id' => $id];
        if (Core::checkMCA(null, 'blog', 'view')) {
            $condition = ['id' => $id, 'status' => Blog::STATUS_PUBLISHED];
        }

        if (($model = Blog::find()->where($condition)->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
