<?php

use common\models\Banner;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BannerSearch */
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
<div class="banner-index">
    <div class="box box-primary">
        <div class="box-body">


            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'id',
                        'title',
                        //'lang',
                        [
                            'attribute' => 'lang',
                            'value' => function ($model) {
                                return !empty($model->lang) ? \common\models\Message::getLocaleList()[$model->lang] : null;
                            },
                            'filter' => \common\models\Message::getLocaleList()
                        ],
                        //'text_content:ntext',
                        //'link_url:url',
                        //'link_option',
                        // 'status',
                        // 'created_at',
                        // 'updated_at',
                        //['attribute' => 'created_at', 'value' => 'created_at', 'format' => 'dateTime', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                        ['attribute' => 'status', 'value' => function ($model) {
                            return $model->statusColorText;
                        }, 'filter' => Banner::getStatusOption(), 'format' => 'raw'],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <p>
                <?= Html::a(Yii::t('app', 'Create Banner'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \powerkernel\fontawesome\Icon::widget(['prefix' => 'fas', 'name' => 'sync-alt', 'styling' => 'fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>

</div>
