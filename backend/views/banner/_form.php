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
    <?= $form->field($model, 'lang')->dropDownList(Yii::$app->params['mongodb']['i18n']?\common\models\mongodb\Message::getLocaleList():\common\models\Message::getLocaleList(), ['prompt'=>'Any']) ?>

    <?= $form->field($model, 'text_content')->widget(TinyMce::className())  ?>
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
