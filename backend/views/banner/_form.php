<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\models\Banner;
use common\models\Message;
use modernkernel\tinymce\TinyMce;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lang')->dropDownList(Message::getLocaleList(), ['prompt'=>'Any']) ?>

    <?= $form->field($model, 'text_content')->widget(TinyMce::className())  ?>
    <?= $form->field($model, 'text_style')->textInput(['maxlength' => true, 'placeholder'=>'top: 10px; left: 10px;']) ?>

    <?= $form->field($model, 'banner_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_option')->dropDownList(['_self'=>'default' ,'_blank'=>'_blank']) ?>

    <?= $form->field($model, 'status')->dropDownList(Banner::getStatusOption()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
