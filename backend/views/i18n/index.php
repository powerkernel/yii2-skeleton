<?php


use common\Core;
use common\models\Message;
use common\models\SourceMessage;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\MessageSearch */

$this->title = Yii::t('app', 'Translation');
$keywords = '';
$description = '';

//$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
//$this->registerMetaTag(['name' => 'description', 'content' => $description]);
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Languages'), 'url' => ['/i18n']];
$this->params['breadcrumbs'][] = $this->title;
//$this->registerJs('$(document).on("pjax:send", function(){ $("#loading").modal("show");});$(document).on("pjax:complete", function(){ $("#loading").modal("hide");})');
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);

?>
<div class="i18n-message-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php Pjax::begin(['id' => 'message-translation-wrap']); ?>    <?= GridView::widget([
                'id' => 'i18n-message-grid',
                'options' => ['class' => 'grid-view table-responsive'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    ['attribute' => 'language', 'value' => 'language', 'filter'=> Message::getLocaleList([Yii::$app->sourceLanguage])],
                    ['attribute' => 'category', 'value' => 'source.category', 'filter' => SourceMessage::getCategoryList()],
                    ['attribute' => 'message', 'value' => 'source.message'],
                    ['attribute' => 'translation', 'format' => 'raw', 'value' => function ($model) {
                        return \modernkernel\jeditable\Editable::widget([
                            'content' => strip_tags($model->translation),
                            'saveUrl' => Yii::$app->urlManager->createUrl(['/i18n/save-translation']),
                            'options' => ['id' => 'message_' . $model->id . '_' . $model->language],
                            'clientOptions' => [
                                'tooltip' => Yii::t('app', 'Click to edit'),
                                'indicator' => Yii::t('app', 'Saving...'),
                                'width' => '93%',
                            ]
                        ]);
                    }],


                    ['attribute' => 'is_translated', 'value' => function ($model) {
                        return Core::getYesNoText($model->is_translated);
                    }, 'filter' => Core::getYesNoOption()],
                ],
            ]); ?>
            <?php Pjax::end(); ?>        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->
    </div>


</div>
