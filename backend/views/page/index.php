<?php

use common\models\Message;
use common\models\PageData;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PageDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
//$this->registerJs('$(document).on("pjax:send", function(){ $("#loading").modal("show");});$(document).on("pjax:complete", function(){ $("#loading").modal("hide");})');
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);

?>
<div class="page-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'options' => ['class' => 'grid-view table-responsive'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id_page',
                    ['attribute' => 'language', 'value' => function ($model) {
                        return Message::getLocaleList()[$model->language];
                    }, 'filter' => Message::getLocaleList()],
                    //'seo_name',
                    'title',
                    'description',
                    // 'content:ntext',
                    // 'status',
                    // 'created_by',
                    // 'updated_by',
                    // 'created_at',
                    // 'updated_at',
                    ['attribute' => 'status', 'value' => function ($model){return $model->statusText;}, 'filter'=> PageData::getStatusOption()],
                    [
                        'class' => 'yii\grid\ActionColumn',
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>

            <p>
                <?= Html::a(Yii::t('app', 'Add new Page'), ['/page/create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->


    </div>



</div>
