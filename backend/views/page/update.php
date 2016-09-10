<?php

use common\Core;
use common\models\PageData;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PageData */
/* @var $form yii\widgets\ActiveForm */
/* @var $languages [] */


$this->title = Yii::t('app', 'Update: {TITLE}', ['TITLE' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id_page' => $model->id_page, 'language' => $model->language]];
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
                <?= $form->field($model, 'id_page')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'language')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
                <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                    'language' => Core::getTinyMCELang(Yii::$app->language),
                    'clientOptions' => [
                        'height' => 320,
                        'autoresize_max_height' => 640,
                        'menubar' => false,
                        'statusbar' => false,
                        'plugins' => [
                            "code advlist link image",
                        ],
                        'toolbar' => "code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                    ]
                ]) ?>
                <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => false, 'placeholder' => Yii::t('app', 'Must be at least 160x90 pixels and at most 1920x1080 pixels')]) ?>
                <?= $form->field($model, 'status')->dropDownList(PageData::getStatusOption()) ?>
                <?= $form->field($model->page, 'show_description')->dropDownList(Core::getYesNoOption()) ?>
                <?= $form->field($model->page, 'show_update_date')->dropDownList(Core::getYesNoOption()) ?>
                <div class="form-group">
                    <p class="pull-left">
                        <?= Html::submitButton($model->scenario == 'create' ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
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
                                        <a href="<?= Yii::$app->urlManager->createUrl(['/page/add-language', 'id' => $model->id_page, 'from' => $model->language, 'to' => $key]) ?>"><?= Yii::t('app', 'Add') ?> <?= $language ?></a>
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
