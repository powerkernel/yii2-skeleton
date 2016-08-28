<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\controllers;

use common\Core;
use nirvana\jsonld\JsonLDHelper;
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
        /* SEO */
        $imageObject=$model->getImageObject();
        $doc = (object)[
            '@type'=>'Article',
            'http://schema.org/name' => $model->title,
            'http://schema.org/headline'=>$model->desc,
            'http://schema.org/articleBody'=>$model->content,
            'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($model->created_at, 'php:c'),
            'http://schema.org/dateModified' => Yii::$app->formatter->asDate($model->updated_at, 'php:c'),
            'http://schema.org/datePublished' => Yii::$app->formatter->asDate($model->published_at, 'php:c'),
            'http://schema.org/url'=>Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl),
            'http://schema.org/image'=>(object)[
                '@type'=>'ImageObject',
                'http://schema.org/url'=>$imageObject['url'],
                'http://schema.org/width'=>$imageObject['width'],
                'http://schema.org/height'=>$imageObject['height']
            ],
            'http://schema.org/author'=>(object)[
                '@type'=>'Person',
                'http://schema.org/name' => $model->author->fullname,
            ],
            'http://schema.org/publisher'=>(object)[
                '@type'=>'Organization',
                'http://schema.org/name'=>Yii::$app->name,
                'http://schema.org/logo'=>(object)[
                    '@type'=>'ImageObject',
                    'http://schema.org/url'=>Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->homeUrl.'/images/logo.png')
                ]
            ],
            'http://schema.org/mainEntityOfPage'=>(object)[
                '@type'=>'WebPage',
                '@id'=>Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl)
            ]
        ];
        JsonLDHelper::add($doc);
        /* OK */
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
