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

                    'slug',
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
                    ['attribute' => 'status', 'value' => function ($model) {
                        return $model->statusColorText;
                    }, 'filter' => PageData::getStatusOption(), 'format' => 'raw'],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                $view = Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['page/view', 'slug' => $model->slug, 'language' => $model->language]), [
                                    'title' => Yii::t('yii', 'View'),
                                    'data-pjax' => 0,
                                ]);
                                unset($key, $url);
                                return $view;
                            },
                            'update' => function ($url, $model, $key) {
                                $update = Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['page/update', 'slug' => $model->slug, 'language' => $model->language]), [
                                    'title' => Yii::t('yii', 'UPdate'),
                                    'data-pjax' => 0,
                                ]);
                                unset($key, $url);
                                return $update;
                            },
                            'delete' => function ($url, $model, $key) {
                                $delete = Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['page/delete', 'slug'=>$model->slug, 'language'=>$model->language]), [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post',
                                ]);
                                unset($key, $url);
                                return $delete;
                            }
                        ],
                        'contentOptions' => ['style' => 'min-width: 70px']
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
