<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\models\Blog;


/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = $model->title;
$keywords = $model->tags;
$description = $model->desc;

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']); // best 1200 x 630
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle'] = Yii::$app->formatter->asDate($model->updated_at);

/* misc */
$js = file_get_contents(__DIR__ . '/view.min.js');
$this->registerJs($js);
$css = file_get_contents(__DIR__ . '/view.css');
$this->registerCss($css);

?>
<div class="blog-view">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body blog-body">
                    <div><?= $model->content ?></div>
                </div>
                <div class="box-footer text-right">
                    <small
                        class="text-muted"><?= Yii::t('app', 'By {AUTHOR}, last updated {DATE}', ['AUTHOR' => $model->author->fullname, 'DATE' => Yii::$app->formatter->asDate($model->updated_at)]) ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('app', 'Most Viewed') ?></h3>
                </div>

                <div class="box-body no-padding">
                </div>
            </div>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('app', 'Latest Updates') ?></h3>
                </div>

                <div class="box-body no-padding">
                </div>
            </div>

        </div>
    </div>

</div>


