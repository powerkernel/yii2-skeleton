<?php

use common\Core;
use common\models\PageData;
use powerkernel\tinymce\TinyMce;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PageData */
/* @var $form yii\widgets\ActiveForm */
/* @var $languages [] */


$this->title = Yii::t('app', 'Update: {TITLE}', ['TITLE' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id_page' => $model->slug, 'language' => $model->language]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

//$js = file_get_contents(__DIR__ . '/_form.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/update.css');
//$this->registerCss($css);
?>
<div class="page-update">


    <div class="box box-primary">
        <div class="box-body">
            <div class="page-data-form">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'slug')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'language')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_content" data-toggle="tab" aria-expanded="true"><?= $model->getAttributeLabel('content') ?></a></li>
                        <li class=""><a href="#tab_photo_uploader" data-toggle="tab" aria-expanded="false"><?= Yii::t('app', 'Photo Uploader') ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_content">
                            <?= $form->field($model, 'content')->widget(TinyMce::class()) ?>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_photo_uploader">
                            <?= \common\widgets\FlickrUploadWidget::widget() ?>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>

                <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => false, 'placeholder' => Yii::t('app', 'Must be at least 160x90 pixels and at most 1920x1080 pixels')]) ?>
                <?= $form->field($model, 'status')->dropDownList(PageData::getStatusOption()) ?>
                <?= $form->field($model->page, 'show_description')->dropDownList(Core::getYesNoOption()) ?>
                <?= $form->field($model->page, 'show_update_date')->dropDownList(Core::getYesNoOption()) ?>
                <div class="form-group">
                    <p class="pull-left">
                        <?= \common\components\SubmitButton::widget(['text'=>$model->scenario == 'create' ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 'options'=>['class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']]) ?>
                    </p>
                    <?php if (count($languages) > 0): ?>
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <?= Yii::t('app', 'Languages') ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($languages as $key => $language): ?>
                                    <li>
                                        <a href="<?= Yii::$app->urlManager->createUrl(['/page/add-language', 'slug' => $model->slug, 'from' => $model->language, 'to' => $key]) ?>"><?= Yii::t('app', 'Add') ?> <?= $language ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
