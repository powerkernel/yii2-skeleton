<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

use common\models\Blog;
use conquer\select2\Select2Widget;
use powerkernel\slugify\Slugify;
use powerkernel\tinymce\TinyMce;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord):?>
    <?= $form->field($model, 'slug')->widget(Slugify::className(),['source'=>'#blog-title']) ?>
    <?php else:?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?php endif;?>

    <?= $form->field($model, 'language')->widget(Select2Widget::className(), [
        'bootstrap'=>false,
        'items'=>\common\Core::getLocaleList(),
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_content" data-toggle="tab" aria-expanded="true"><?= $model->getAttributeLabel('content') ?></a></li>
            <li class=""><a href="#tab_photo_uploader" data-toggle="tab" aria-expanded="false"><?= Yii::t('app', 'Photo Uploader') ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_content">
                <?= $form->field($model, 'content')->widget(TinyMce::className()) ?>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_photo_uploader">
                <?= \common\widgets\FlickrUploadWidget::widget() ?>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumbnail_square')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Blog::getStatusOption()) ?>

    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>

    <?php ActiveForm::end(); ?>




</div>
