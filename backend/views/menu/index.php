<?php

use common\models\Menu;
use kotchuprik\sortable\grid\Column;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MenuSearch */
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
<div class="menu-index">
    <div class="box box-primary">
        <div class="box-body">


            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'sorter'=>[],
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return ['data-sortable-id' => $model->id];
                    },
                    'options' => [
                        'data' => [
                            'sortable-widget' => 1,
                            'sortable-url' => \yii\helpers\Url::toRoute(['sorting']),
                        ]
                    ],
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
                        [
                            'class' => Column::className(),
                        ],

                        ['attribute' => 'id_parent', 'value' => function ($model){return $model->parent?$model->parent->label:null;}, 'filter'=> Menu::getRootMenu()],
                        'label',
                        'url',
                        'position',
                        //'status',
                        // 'created_at',
                        // 'updated_at',
                        // 'created_by',
                        // 'updated_by',
                        //['attribute' => 'created_at', 'value' => 'created_at', 'format' => 'dateTime', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                        ['attribute' => 'status', 'value' => function ($model){return $model->statusColorText;}, 'filter'=> Menu::getStatusOption(), 'format'=>'raw'],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update} {delete}'
                        ],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <p>
                <?= Html::a(Yii::t('app', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \powerkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>


