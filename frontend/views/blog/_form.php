<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\Core;
use common\models\Blog;
use dosamigos\tinymce\TinyMce;
use harrytang\photouploader\widgets\Facebook;
use modernkernel\bootstrapsocial\Button;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'slug')->widget(\modernkernel\slugify\Slugify::className(),['source'=>'#blog-title']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_content" data-toggle="tab" aria-expanded="true"><?= $model->getAttributeLabel('content') ?></a></li>
            <li class=""><a href="#tab_photo_uploader" data-toggle="tab" aria-expanded="false"><?= Yii::t('app', 'Photo Uploader') ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_content">
                <?php
                echo $form->field($model, 'content')->widget(TinyMce::className(), [
                    'options' => ['rows' => 6],
                    'language' => Core::getTinyMCELang(Yii::$app->language),

                    'clientOptions' => [
                        'height'=>480,
                        'menubar'=> false,
                        'plugins' => [
                            "advlist autolink lists link charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste image"
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code"
                    ]
                ]);
                ?>
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
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>




</div>
