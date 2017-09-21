<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace frontend\controllers;

use common\models\PageData;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;

/**
 * PageController
 */
class PageController extends Controller
{


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
        $query = PageData::find()->select(['slug', 'language', 'updated_at'])->where(['status' => PageData::STATUS_ACTIVE]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(Yii::$app->params['sitemapPageSize']);

        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->renderPartial('sitemap', ['models' => $models]);
    }


}
