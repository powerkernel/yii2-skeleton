<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

use common\Core;
use conquer\select2\Select2Widget;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'My Account');
$this->registerJs("jQuery('.select2').select2([]);")
?>
<div class="account-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title">Profile</h1>
        </div>
        <div class="box-body">
            <div class="account-form">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'timezone')->widget(Select2Widget::className(), [
                    'bootstrap'=>false,
                    'items'=>Core::getTimezoneList(),

                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

