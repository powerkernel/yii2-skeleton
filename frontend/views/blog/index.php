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
    <div class="row">
        <div class="col-md-8">
            <?= ListView::widget([
                'layout' => '<div class="row">{items}</div>{summary}{pager}',
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_grid', ['model' => $model]);
                },
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= \frontend\widgets\BlogPost::widget(['type' => 'mostViewed']) ?>
            <?= \frontend\widgets\BlogPost::widget(['type' => 'latest']) ?>
        </div>
    </div>

</div>
