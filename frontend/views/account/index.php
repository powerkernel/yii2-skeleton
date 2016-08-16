<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

use common\Core;
use conquer\select2\Select2Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('app', 'My Account');

?>
<div class="account-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title">Profile</h1>
        </div>
        <div class="box-body">
            <div class="account-form">
                <?php $form = ActiveForm::begin(['action'=>Yii::$app->urlManager->createUrl(['/account'])]); ?>
                <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'disabled'=>!$model->canChangeName()]) ?>
                <?= $form->field($model, 'language')->widget(Select2Widget::className(), [
                    'bootstrap'=>false,
                    'items'=>\common\models\Message::getLocaleList(),
                ]) ?>
                <?=
                    $form->field($model, 'timezone')->widget(Select2Widget::className(), [
                        'bootstrap'=>false,
                        'items'=>Core::getTimezoneList(),
                    ])
                ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

