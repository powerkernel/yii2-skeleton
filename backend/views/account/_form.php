<?php

use common\Core;
use conquer\select2\Select2Widget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'timezone')->widget(Select2Widget::className(), [
        'bootstrap'=>false,
        'items'=>Core::getTimezoneList(),
    ])
    ?>

    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
