<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="blog-index">
    <div class="box box-info">
        <div class="box-body">
            <?= \common\models\Setting::getValue('blogDesc') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= ListView::widget([
                'layout' => '<div class="row">{items}</div>{summary}{pager}<div><br /></div>',
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_grid', ['model' => $model, 'index'=>$index]);
                },
            ]) ?>
        </div>
        <div class="col-md-4">
            <div class="hidden-xs hidden-sm"><?= \frontend\widgets\BlogPost::widget(['type' => 'mostViewed']) ?></div>
            <div><?= \frontend\widgets\AdsBox::widget() ?></div>
            <div><?= \frontend\widgets\BlogPost::widget(['type' => 'random']) ?></div>
        </div>
    </div>
</div>
