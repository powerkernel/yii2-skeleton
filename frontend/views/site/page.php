<?php

use common\Core;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = $model->content->title;
$keywords = $model->content->keywords;
$description = $model->content->description;

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']);
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


//$this->params['breadcrumbs'][] = ['label' => Yii::t('page', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$js = file_get_contents(__DIR__ . '/page.min.js');
//$this->registerJs($js);
$css=file_get_contents(__DIR__.'/page.css');
$this->registerCss($css);

?>
<div class="site-page">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php if ($model->show_description): ?>
                <p><?= Html::encode($model->content->description) ?></p>
            <?php endif; ?>
            <div class="page-body">
                <?= Core::translateMessage($model->content->content, [
                    '{APP_NAME}' => Yii::$app->name,
                    '{APP_DOMAIN}' => Yii::$app->request->hostInfo,
                ]) ?>
            </div>
        </div>
        <?php if ($model->show_update_date): ?>
            <div class="box-footer">
                <div class="pull-right font-light text-sm">
                    <?= Yii::t('app', 'Last updated: {DATE}', ['DATE' => Yii::$app->formatter->asDate($model->content->updatedAt)]) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>