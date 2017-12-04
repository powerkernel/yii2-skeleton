<?php

use common\models\Account;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
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
<div class="account-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <div class="table-responsive sort-ordinal">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    //'seo_name',
                    'fullname',
                    //'fullname_changed',
                    //'auth_key',
                    // 'password_hash',
                    // 'password_reset_token',
                    'email:email',
                    // 'email_verified:email',
                    // 'new_email:email',
                    // 'change_email_token:email',
                    // 'role',
                    // 'language',
                    // 'timezone',
                    //'status',
                    ['attribute' => 'created_at', 'value' => 'createdAt', 'format' => 'dateTime', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                    // 'updated_at',
                    ['attribute' => 'status', 'value' => function ($model){return $model->statusColorText;}, 'filter'=> Account::getStatusOption(), 'format'=>'raw'],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view} {update}',
                    ],
                ],
            ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <p><?= Html::a(Yii::t('app', 'Add New User'), ['create'], ['class' => 'btn btn-success']) ?></p>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \powerkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
