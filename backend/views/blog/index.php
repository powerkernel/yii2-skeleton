<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\models\Blog;
use modernkernel\tinymce\TinyMce;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Content */

$this->title = Yii::t('app', 'Blog');
$keywords = '';
$description = '';

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

/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="blog-manage">
    <div class="box box-primary">
        <div class="box-body">


            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php Pjax::begin(); ?>
            <div class="table-responsive sort-ordinal">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'title',
                        //'desc',
                        ['attribute' => 'fullname', 'label' => Yii::t('app', 'Author'), 'value' => 'author.fullname'],
                        //'content:ntext',
                        //'tags',
                        //'author_id',
                        // 'status',
                        // 'created_at',
                        //'updated_at:dateTime',
                        ['attribute' => 'updated_at', 'value' => 'updated_at', 'format' => 'date', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'updated_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                        ['attribute' => 'status', 'value' => function ($model) {
                            return $model->statusText;
                        }, 'filter' => Blog::getStatusOption()],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    $view = Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->viewUrl, [
                                        'title' => Yii::t('yii', 'View'),
                                        'target' => '_blank',
                                        'data-pjax' => 0
                                    ]);
                                    unset($url);
                                    return $model->status == Blog::STATUS_PUBLISHED ? $view : '';
                                }
                            ]
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

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('app', 'Blog Page Content') ?></h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'content')->widget(TinyMce::className()) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
