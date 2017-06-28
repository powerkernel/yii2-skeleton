<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\controllers;

use common\components\MainController;
use common\Core;
use common\models\Setting;
use Yii;
use common\models\Blog;
use common\models\BlogSearch;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends MainController
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
                        'actions' => ['view', 'view-amp', 'index', 'sitemap'],
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
        /* @var Blog $model */
        $this->layout = 'main';
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;


        $title = Setting::getValue('blogTitle');
        $description = Setting::getValue('blogDesc');
        $thumbnail = Setting::getValue('blogThumbnail');
        $keywords = Setting::getValue('blogKeywords');

        /* canonical */
        Yii::$app->view->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->urlManager->createAbsoluteUrl(['/blog/index'])]);

        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        /* Facebook */
        $metaTags[] = ['property' => 'og:title', 'content' => $title];
        $metaTags[] = ['property' => 'og:description', 'content' => $description];
        $metaTags[] = ['property' => 'og:type', 'content' => 'website']; // website, article, product, profile etc
        $metaTags[] = ['property' => 'og:image', 'content' => $thumbnail]; //best 1200 x 630
        $metaTags[] = ['property' => 'og:url', 'content' => Yii::$app->urlManager->createAbsoluteUrl(['/blog'])];
        if ($appId = Setting::getValue('fbAppId')) {
            $metaTags[] = ['property' => 'fb:app_id', 'content' => $appId];
        }
        //$metaTags[]=['property' => 'fb:app_id', 'content' => ''];
        //$metaTags[]=['property' => 'fb:admins', 'content' => ''];
        /* Twitter */
        $metaTags[] = ['name' => 'twitter:card', 'content' => 'summary_large_image'];
        $metaTags[] = ['name' => 'twitter:site', 'content' => Setting::getValue('twitterSite')];

        /* jsonld */
        $listItem = null;
        foreach ($dataProvider->models as $model) {
            $listItem[] = (object)[
                '@type' => 'ListItem',
                'http://schema.org/item' => (object)[
                    '@id' => $model->getViewUrl(true),
                    'http://schema.org/name' => $model->title
                ]
            ];
        }
        $jsonLd = (object)[
            '@type' => 'ItemList',
            'http://schema.org/name' => $title,
            'http://schema.org/itemListElement' => $listItem
        ];

        /* OK */
        $data['title'] = $title;
        $data['metaTags'] = $metaTags;
        $data['jsonLd'] = $jsonLd;
        $this->registerMetaTagJsonLD($data);

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
     * @param string $name
     * @return mixed
     */
    public function actionView($name)
    {
        $this->layout = 'main';
        $model = $this->findBySlug($name);
        /* language */
        Yii::$app->language=$model->language;

        /* views ++ */
        $model->updateViews();

        /* amphtml */
        Yii::$app->view->registerLinkTag(['rel' => 'amphtml', 'href' => Yii::$app->urlManager->createAbsoluteUrl(['/blog/view-amp', 'name'=>$model->slug])]);

        /* metaData */
        $title = $model->title;
        $keywords = $model->tags;
        $description = $model->desc;
        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        /* Facebook */
        $metaTags[] = ['property' => 'og:title', 'content' => $title];
        $metaTags[] = ['property' => 'og:description', 'content' => $description];
        $metaTags[] = ['property' => 'og:type', 'content' => 'article']; // article, product, profile etc
        $metaTags[] = ['property' => 'og:image', 'content' => $model->thumbnail]; //best 1200 x 630
        $metaTags[] = ['property' => 'og:url', 'content' => $model->getViewUrl(true)];
        if ($appId = Setting::getValue('fbAppId')) {
            $metaTags[] = ['property' => 'fb:app_id', 'content' => $appId];
        }

        //$metaTags[]=['property' => 'fb:app_id', 'content' => ''];
        //$metaTags[]=['property' => 'fb:admins', 'content' => ''];
        /* Twitter */
        $metaTags[] = ['name' => 'twitter:card', 'content' => 'summary_large_image'];
        $metaTags[] = ['name' => 'twitter:site', 'content' => Setting::getValue('twitterSite')];
//        $metaTags[]=['name'=>'twitter:title', 'content'=>$title];
//        $metaTags[]=['name'=>'twitter:description', 'content'=>$description];
//        $metaTags[]=['name'=>'twitter:card', 'content'=>'summary'];
//        $metaTags[]=['name'=>'twitter:site', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:image', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data2', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label2', 'content'=>''];
        /* jsonld */
        $imageObject = $model->getImageObject();
        $jsonLd = (object)[
            '@type' => 'Article',
            'http://schema.org/name' => $model->title,
            'http://schema.org/headline' => $model->desc,
            'http://schema.org/articleBody' => $model->content,
            'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($model->created_at, 'php:c'),
            'http://schema.org/dateModified' => Yii::$app->formatter->asDate($model->updated_at, 'php:c'),
            'http://schema.org/datePublished' => Yii::$app->formatter->asDate($model->published_at, 'php:c'),
            'http://schema.org/url' => $model->getViewUrl(true),
            'http://schema.org/image' => (object)[
                '@type' => 'ImageObject',
                'http://schema.org/url' => $imageObject['url'],
                'http://schema.org/width' => $imageObject['width'],
                'http://schema.org/height' => $imageObject['height']
            ],
            'http://schema.org/author' => (object)[
                '@type' => 'Person',
                'http://schema.org/name' => $model->author->fullname,
            ],
            'http://schema.org/publisher' => (object)[
                '@type' => 'Organization',
                'http://schema.org/name' => Yii::$app->name,
                'http://schema.org/logo' => (object)[
                    '@type' => 'ImageObject',
                    'http://schema.org/url' => Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->homeUrl . '/images/logo.png')
                ]
            ],
            'http://schema.org/mainEntityOfPage' => (object)[
                '@type' => 'WebPage',
                '@id' => Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl)
            ]
        ];

        /* OK */
        $data['title'] = $title;
        $data['metaTags'] = $metaTags;
        $data['jsonLd'] = $jsonLd;
        $this->registerMetaTagJsonLD($data);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Displays a single Blog model in AMP page.
     * @param string $name
     * @return mixed
     */
    public function actionViewAmp($name)
    {
        $this->layout = '@common/layouts/amp';
        $model = $this->findBySlug($name);

        /* language */
        Yii::$app->language=$model->language;

        /* views ++ */
        $model->updateViews();

        /* canonical */
        Yii::$app->view->registerLinkTag(['rel' => 'canonical', 'href' => $model->getViewUrl(true)]);

        /* metaData */
        $title = $model->title;
        $keywords = $model->tags;
        $description = $model->desc;
        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        /* Facebook */
        $metaTags[] = ['property' => 'og:title', 'content' => $title];
        $metaTags[] = ['property' => 'og:description', 'content' => $description];
        $metaTags[] = ['property' => 'og:type', 'content' => 'article']; // article, product, profile etc
        $metaTags[] = ['property' => 'og:image', 'content' => $model->thumbnail]; //best 1200 x 630
        $metaTags[] = ['property' => 'og:url', 'content' => $model->getViewUrl(true)];
        if ($appId = Setting::getValue('fbAppId')) {
            $metaTags[] = ['property' => 'fb:app_id', 'content' => $appId];
        }

        //$metaTags[]=['property' => 'fb:app_id', 'content' => ''];
        //$metaTags[]=['property' => 'fb:admins', 'content' => ''];
        /* Twitter */
        $metaTags[] = ['name' => 'twitter:card', 'content' => 'summary_large_image'];
        $metaTags[] = ['name' => 'twitter:site', 'content' => Setting::getValue('twitterSite')];
//        $metaTags[]=['name'=>'twitter:title', 'content'=>$title];
//        $metaTags[]=['name'=>'twitter:description', 'content'=>$description];
//        $metaTags[]=['name'=>'twitter:card', 'content'=>'summary'];
//        $metaTags[]=['name'=>'twitter:site', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:image', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data2', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label2', 'content'=>''];
        /* jsonld */
        $imageObject = $model->getImageObject();
        $jsonLd = (object)[
            '@type' => 'Article',
            'http://schema.org/name' => $model->title,
            'http://schema.org/headline' => $model->desc,
            'http://schema.org/articleBody' => $model->content,
            'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($model->created_at, 'php:c'),
            'http://schema.org/dateModified' => Yii::$app->formatter->asDate($model->updated_at, 'php:c'),
            'http://schema.org/datePublished' => Yii::$app->formatter->asDate($model->published_at, 'php:c'),
            'http://schema.org/url' => $model->getViewUrl(true),
            'http://schema.org/image' => (object)[
                '@type' => 'ImageObject',
                'http://schema.org/url' => $imageObject['url'],
                'http://schema.org/width' => $imageObject['width'],
                'http://schema.org/height' => $imageObject['height']
            ],
            'http://schema.org/author' => (object)[
                '@type' => 'Person',
                'http://schema.org/name' => $model->author->fullname,
            ],
            'http://schema.org/publisher' => (object)[
                '@type' => 'Organization',
                'http://schema.org/name' => Yii::$app->name,
                'http://schema.org/logo' => (object)[
                    '@type' => 'ImageObject',
                    'http://schema.org/url' => Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->homeUrl . '/images/logo.png')
                ]
            ],
            'http://schema.org/mainEntityOfPage' => (object)[
                '@type' => 'WebPage',
                '@id' => Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl)
            ]
        ];

        /* OK */
        $data['title'] = $title;
        $data['metaTags'] = $metaTags;
        $data['jsonLd'] = $jsonLd;
        $this->registerMetaTagJsonLD($data);

        return $this->render('view-amp', [
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
        $model->language=Yii::$app->language;

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

        return $this->redirect(['manage']);
    }

    /**
     * sitemap
     * @return string
     */
    public function actionSitemap()
    {
        /* header */
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/xml');

        /* ok */
        $query = Blog::find()->select(['id', 'slug', 'updated_at'])->where(['status' => Blog::STATUS_PUBLISHED]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(Yii::$app->params['sitemapPageSize']);

        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->renderPartial('sitemap', ['models' => $models]);
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

    /**
     * find blog by $slug
     * @param string $slug
     * @return array|Blog|null
     * @throws NotFoundHttpException
     */
    protected function findBySlug($slug)
    {
        if (($model = Blog::find()->where(['slug' => $slug, 'status' => Blog::STATUS_PUBLISHED])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * @inheritdoc
     * @param \yii\base\Action $action
     * @return bool
     * @throws NotFoundHttpException
     */
    public function beforeAction($action)
    {
        if(!Yii::$app->params['enableBlog']){
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

}
