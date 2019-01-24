<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

use common\models\Banner;
use powerkernel\tinymce\TinyMce;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lang')->dropDownList(\common\models\Message::getLocaleList(), ['prompt'=>'Any']) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_content" data-toggle="tab" aria-expanded="true"><?= $model->getAttributeLabel('content') ?></a></li>
            <li class=""><a href="#tab_photo_uploader" data-toggle="tab" aria-expanded="false"><?= Yii::t('app', 'Photo Uploader') ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_content">
                <?= $form->field($model, 'text_content')->widget(TinyMce::class) ?>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_photo_uploader">
                <?= \common\widgets\FlickrUploadWidget::widget() ?>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <?= $form->field($model, 'text_style')->textInput(['maxlength' => true, 'placeholder'=>'top: 10px; left: 10px;']) ?>

    <?= $form->field($model, 'banner_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_option')->dropDownList(['_self'=>'default' ,'_blank'=>'_blank']) ?>

    <?= $form->field($model, 'status')->dropDownList(Banner::getStatusOption()) ?>

    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
