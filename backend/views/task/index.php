<?php

use common\models\TaskLog;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaskLogSearch */
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
<div class="task-log-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
                        Yii::$app->params['mongodb']['taskLog'] ? ['class' => 'yii\grid\SerialColumn'] : 'id',
                        ['attribute' => 'task', 'value' => function ($model) {
                            return $model->task;
                        }, 'filter' => TaskLog::getTaskList()],
                        'result:ntext',
                        //'created_at:dateTime',
                        //'updated_at',
                        [
                            'attribute' => 'created_at',
                            'value' => 'created_at',
                            'format' => 'dateTime',
                            'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']]),
                            'contentOptions' => ['style' => 'min-width: 160px']
                        ],
                        //['attribute' => 'status', 'value' => function ($model){return $model->statusText;}, 'filter'=>''],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
                            'contentOptions' => ['style' => 'min-width: 50px']
                        ],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \modernkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
