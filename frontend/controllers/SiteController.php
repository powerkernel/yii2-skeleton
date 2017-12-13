<?php

namespace frontend\controllers;


use common\components\MainController;
use common\models\Blog;
use common\models\Page;
use common\models\PageData;
use common\models\Setting;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\View;

/**
 * Site controller
 */
class SiteController extends MainController
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
            'browser-config' => [
                'class' => 'common\actions\BrowserConfigAction',
            ],
            'manifest' => [
                'class' => 'common\actions\ManifestAction',
            ],
            'login' => [
                'class' => 'common\actions\LoginAction',
            ],
            'state-list' => [
                'class' => 'common\actions\StateAction',
            ],
            'district-list' => [
                'class' => 'common\actions\DistrictAction',
            ],
            'ward-list' => [
                'class' => 'common\actions\WardAction',
            ],
            'flickr-upload' => [
                'class' => 'common\actions\FlickrUploadAction',
            ],
            'flickr-photo' => [
                'class' => 'common\actions\FlickrPhotoAction',
            ],
            'flickr-delete' => [
                'class' => 'common\actions\FlickrDeleteAction',
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
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPage($id)
    {
        $model = Page::find()->where(['slug'=>$id])->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        /* found */
        $page = $model->content;

        /* redirect */
        $class = get_class(Yii::$app->urlManager);
        if ($class == 'yii\web\UrlManager' && Yii::$app->request->url != $page->getViewUrl()) {
            return $this->redirect($page->getViewUrl());
        }

        /* missing lang */
        if ($page->language !== Yii::$app->language) {
            if(Yii::$app->params['mongodb']['i18n']){
                $locales=\common\models\mongodb\Message::getLocaleList();
            }
            else {
                $locales=\common\models\Message::getLocaleList();
            }

            Yii::$app->session->setFlash(
                'info',
                Yii::t('app',
                    'This page has no {CUR_LANG} version. You are currently viewing the {LANGUAGE} version.',
                    [
                        'CUR_LANG' => $locales[Yii::$app->language],
                        'LANGUAGE' => $locales[$page->language]
                    ]
                ));
        }

        /* seo */
        /* metaData */
        $title = $page->title;
        $keywords = $page->keywords;
        $description = $page->description;
        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        /* Facebook */
        $metaTags[] = ['property' => 'og:title', 'content' => $title];
        $metaTags[] = ['property' => 'og:description', 'content' => $description];
        $metaTags[] = ['property' => 'og:type', 'content' => 'article']; // article, product, profile etc
        $metaTags[] = ['property' => 'og:image', 'content' => $page->thumbnail]; //best 1200 x 630
        $metaTags[] = ['property' => 'og:url', 'content' => $page->getViewUrl(true)];
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
//        $metaTags[]=['name'=>'twitter:image', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label1', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:data2', 'content'=>''];
//        $metaTags[]=['name'=>'twitter:label2', 'content'=>''];
        /* jsonld */
        $imageObject = $page->getImageObject();
        $jsonLd = (object)[
            '@type' => 'Article',
            'http://schema.org/name' => $title,
            'http://schema.org/headline' => $description,
            'http://schema.org/articleBody' => $page->content,
            'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($page->createdAt, 'php:c'),
            'http://schema.org/dateModified' => Yii::$app->formatter->asDate($page->updatedAt, 'php:c'),
            'http://schema.org/datePublished' => Yii::$app->formatter->asDate($page->createdAt, 'php:c'),
            'http://schema.org/url' => $page->getViewUrl(true),
            'http://schema.org/image' => (object)[
                '@type' => 'ImageObject',
                'http://schema.org/url' => !empty($imageObject['url']) ? $imageObject['url'] : '',
                'http://schema.org/width' => !empty($imageObject['width']) ? $imageObject['width'] : '',
                'http://schema.org/height' => !empty($imageObject['height']) ? $imageObject['height'] : ''
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
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/plain;charset=UTF-8');
        $sitemap = Yii::$app->urlManager->createAbsoluteUrl('site/sitemap');
        return $this->renderPartial('robots', array('sitemap' => $sitemap));
    }

    /**
     * sitemap
     * @return mixed
     * @throws \yii\mongodb\Exception
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
        $modules = scandir(\Yii::$app->vendorPath . '/powerkernel');
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
     * This is page where google search result displayed
     * @param null $q
     * @return string|Response
     */
    public function actionSearch($q = null)
    {
        if (empty($q)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        $data['title'] = Yii::t('app', 'Search Result');
        $keywords = Yii::t('app', 'search result, search');
        $description = Yii::t('app', 'Search our website');

        $metaTags[] = ['name' => 'keywords', 'content' => $keywords];
        $metaTags[] = ['name' => 'description', 'content' => $description];
        $metaTags[] = ['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex'];

        $data['metaTags'] = $metaTags;
        $this->registerMetaTagJsonLD($data);


        /* js */
        $cx = Setting::getValue('googleCustomSearch');
        if (empty($cx)) {
            return $this->redirect(Yii::$app->homeUrl);
        }

        $js = <<<EOB
(function() {
    var cx = "{$cx}";
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
})();
EOB;
        $this->view->registerJs($js, View::POS_HEAD);


        return $this->render('search');
    }
}
