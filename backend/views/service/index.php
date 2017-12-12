<?php

use powerkernel\bootstrapsocial\Button;


/* @var $this yii\web\View */
/* @var $services [] */


/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="service-index">
    <div class="alert alert-info">
        <?= Yii::t('app', 'Note: You can manage site services in Settings &gt; API.') ?>
    </div>
    <div class="row">
        <?php if(Yii::$app->authClientCollection->hasClient('flickr-photo')):?>
        <div class="col-xs-6 col-sm-4 col-lg-3">
            <div class="box box-<?= isset($services['flickr-photo'])?'success':'default' ?>">
                <div class="box-header with-border">
                    <?= Button::widget([
                        'button' => 'flickr',
                        'iconOnly' => false,
                        'label' => 'Flickr Photo',
                        'link' => Yii::$app->urlManager->createUrl(['/service/auth', 'authclient'=>'flickr-photo'])
                    ]) ?>
                </div>
                <div class="box-body">
                    <h5 class="text-center">
                        <?php if(isset($services['flickr-photo'])):?>
                            <?php $userid=json_decode($services['flickr-photo']['data'], true)['userid'];?>
                            <?= Yii::t(
                                'app',
                                'Service is using account: {ID}',
                                ['ID'=>\yii\helpers\Html::a($userid, 'https://www.flickr.com/'.$userid, ['target'=>'_blank'])]) ?>
                        <?php else:?>
                            <?= Yii::t('app', 'Click the button above to configure this service.') ?>
                        <?php endif;?>


                    </h5>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div>

</div>