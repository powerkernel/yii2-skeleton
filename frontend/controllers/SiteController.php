<?php
namespace frontend\controllers;


use common\Core;
use common\models\Blog;
use common\models\Message;
use common\models\Page;
use common\models\PageData;
use common\models\Setting;
use nirvana\jsonld\JsonLDHelper;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'flickr-upload' => [
                'class' => 'common\components\FlickrUploadAction',
            ],
            'flickr-photo' => [
                'class' => 'common\components\FlickrPhotoAction',
            ],

        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * page view
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @internal param $name
     */
    public function actionPage($id)
    {
        $model = Page::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        /* found */
        if ($model->content->language !== Yii::$app->language) {
            Yii::$app->session->setFlash(
                'info',
                Yii::t('app',
                    'This page has no {CUR_LANG} version. You are currently viewing the {LANGUAGE} version.',
                    [
                        'CUR_LANG' => Message::getLocaleList()[Yii::$app->language],
                        'LANGUAGE' => Message::getLocaleList()[$model->content->language]
                    ]
                ));
        }

        /* seo */
        /* metaData */
        $title = $model->content->title;
        $keywords = $model->content->keywords;
        $description = $model->content->description;
        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        /* Facebook */
        $metaTags[] = ['property' => 'og:title', 'content' => $title];
        $metaTags[] = ['property' => 'og:description', 'content' => $description];
        $metaTags[] = ['property' => 'og:type', 'content' => 'article']; // article, product, profile etc
        $metaTags[] = ['property' => 'og:image', 'content' => $model->content->thumbnail]; //best 1200 x 630
        $metaTags[] = ['property' => 'og:url', 'content' => $model->content->getViewUrl(true)];
        //$metaTags[]=['property' => 'fb:app_id', 'content' => ''];
        //$metaTags[]=['property' => 'fb:admins', 'content' => ''];
        /* Twitter */
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
        $imageObject = $model->content->getImageObject();
        $jsonLd = (object)[
            '@type' => 'Article',
            'http://schema.org/name' => $title,
            'http://schema.org/headline' => $description,
            'http://schema.org/articleBody' => $model->content->content,
            'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($model->content->created_at, 'php:c'),
            'http://schema.org/dateModified' => Yii::$app->formatter->asDate($model->content->updated_at, 'php:c'),
            'http://schema.org/datePublished' => Yii::$app->formatter->asDate($model->content->created_at, 'php:c'),
            'http://schema.org/url' => Yii::$app->request->absoluteUrl,
            'http://schema.org/image' => (object)[
                '@type' => 'ImageObject',
                'http://schema.org/url' => !empty($imageObject['url'])?$imageObject['url']:'',
                'http://schema.org/width' => !empty($imageObject['width'])?$imageObject['width']:'',
                'http://schema.org/height' => !empty($imageObject['height'])?$imageObject['height']:''
            ],
            'http://schema.org/author' => (object)[
                '@type' => 'Organization',
                'http://schema.org/name' => Yii::$app->name,
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
                '@id' => Yii::$app->request->absoluteUrl
            ]
        ];

        /* OK */
        $data['title'] = $title;
        $data['metaTags'] = $metaTags;
        $data['jsonLd'] = $jsonLd;
        $this->registerMetaTagJsonLD($data);

        return $this->render('page', [
            'model' => $model
        ]);

    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * robots.txt
     */
    public function actionRobots()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $sitemap = Yii::$app->urlManager->createAbsoluteUrl('site/sitemap');
        return $this->renderPartial('robots', array('sitemap' => $sitemap));
    }

    /**
     * sitemap
     * @return mixed
     */
    public function actionSitemap()
    {
        /* header response */
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/xml');

        /* begin */
        $sitemaps = [];
        /* blog sitemap */
        $query = Blog::find()->where(['status' => Blog::STATUS_PUBLISHED]);
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count()]);
        $pagination->setPageSize(Yii::$app->params['sitemapPageSize']);
        $pages = $pagination->getPageCount();
        if ($pages > 0) {
            for ($i = 0; $i < $pages; $i++) {
                $sitemaps[] = Yii::$app->urlManager->createAbsoluteUrl(['/blog/sitemap', 'page' => $i + 1]);
            }
        }
        /* page sitemap */
        $query = PageData::find()->where(['status' => PageData::STATUS_ACTIVE]);
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count()]);
        $pagination->setPageSize(Yii::$app->params['sitemapPageSize']);
        $pages = $pagination->getPageCount();
        if ($pages > 0) {
            for ($i = 0; $i < $pages; $i++) {
                $sitemaps[] = Yii::$app->urlManager->createAbsoluteUrl(['/page/sitemap', 'page' => $i + 1]);
            }
        }


        /* load modules sitemap */
        $modules = scandir(\Yii::$app->vendorPath . '/modernkernel');
        foreach ($modules as $module) {
            if (!preg_match('/[\.]+/', $module)) {
                $moduleName = str_ireplace('yii2-', '', $module);
                if (method_exists(Yii::$app->getModule($moduleName), 'sitemap')) {
                    $sitemaps = array_merge($sitemaps, Yii::$app->getModule($moduleName)->sitemap());
                }
            }
        }
        return $this->renderPartial('sitemap', ['sitemaps' => $sitemaps]);

    }

    /**
     * manifest.json
     */
    public function actionManifest()
    {
        $color = Setting::getValue('androidThemeColor');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $baseUrl = Yii::$app->request->baseUrl;
        $json = [
            'name' => Yii::$app->name,
            'icons' => [
                ['src' => $baseUrl . '/android-chrome-192x192.png', 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => $baseUrl . '/android-chrome-512x512.png', 'sizes' => '512x512', 'type' => 'image/png']
            ],
            'display' => 'standalone',
            'theme_color' => $color,
        ];
        return $json;
    }

    /**
     * browserconfig.xml
     */
    public function actionBrowserconfig()
    {
        Yii::$app->response->format = Response::FORMAT_XML;
        $color = Setting::getValue('msTileColor');
        $baseUrl = Yii::$app->request->baseUrl;

        $xml = <<<EOB
<?xml version="1.0" encoding="utf-8"?>
<browserconfig>        
    <msapplication>
        <tile>
          <square150x150logo src="{$baseUrl}/mstile-150x150.png"/>
          <TileColor>{$color}</TileColor>
        </tile>
    </msapplication>
</browserconfig>
EOB;
        echo $xml;

    }

    /**
     * register metaTags and JsonLD info
     * @param array $data
     */
    protected function registerMetaTagJsonLD($data = [])
    {
        $this->view->title = !empty($data['title']) ? $data['title'] : Yii::$app->name;

        if (!empty($data['jsonLd'])) {
            JsonLDHelper::add($data['jsonLd']);
        }
        if (!empty($data['metaTags'])) {
            foreach ($data['metaTags'] as $tag) {
                $this->view->registerMetaTag($tag);
            }
        }
    }
}
