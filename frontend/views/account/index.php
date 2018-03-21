<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

use common\Core;
use conquer\select2\Select2Widget;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'My Account');
$keywords = Yii::t('app', 'my profile, my account');
$description = Yii::t('app', 'View and update your personal information');
//$js = file_get_contents(__DIR__ . '/pds.min.js');
//$this->registerJs($js);
?>
<div class="account-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title"><?= Yii::t('app', 'Profile'); ?></h1>
        </div>
        <div class="box-body">
            <div class="account-form">
                <?php $form = ActiveForm::begin([
                    'action'=>Yii::$app->urlManager->createUrl(['/account']),
                    'options'=>['class'=>'pds']
                ]); ?>
                <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'disabled'=>!$model->canChangeName()]) ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled'=>true]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'disabled'=>true]) ?>
                <?= $form->field($model, 'language')->widget(Select2Widget::class, [
                    'bootstrap'=>false,
                    'items'=>\common\models\Message::getLocaleList(),
                ]) ?>
                <?=
                    $form->field($model, 'timezone')->widget(Select2Widget::class, [
                        'bootstrap'=>false,
                        'items'=>Core::getTimezoneList(),
                    ])
                ?>
                <div class="form-group">
                    <?= \common\components\SubmitButton::widget(['text'=>Yii::t('app', 'Save'), 'options'=>['class' => 'btn btn-primary']]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

