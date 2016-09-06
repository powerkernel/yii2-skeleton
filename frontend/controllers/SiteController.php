<?php
namespace frontend\controllers;


use common\models\Blog;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
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


}
