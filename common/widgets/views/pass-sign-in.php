<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $client boolean */

/* @var $model \common\models\PassLoginForm */

use modernkernel\bootstrapsocial\Button;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="widget-login">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="box-title text-center"><?= Yii::t('app', 'Log In') ?></h1>
            <div>
                <hr/>
            </div>

            <?php if ($client): ?>
                <?php if (Yii::$app->authClientCollection->hasClient('facebook')): ?>
                    <?= Button::widget([
                        'button' => 'facebook',
                        'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'facebook']),
                        'label' => Yii::t('app', 'Login with Facebook')
                    ]) ?>
                <?php endif; ?>
                <?php if (Yii::$app->authClientCollection->hasClient('google')): ?>
                    <?= Button::widget([
                        'button' => 'google',
                        'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'google']),
                        'label' => Yii::t('app', 'Login with Google')
                    ]) ?>
                <?php endif; ?>
                <div>
                    <hr/>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'login') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div class="form-group text-center">
                <?= \common\components\SubmitButton::widget(['text' => Yii::t('app', 'Login'), 'options' => ['class' => 'btn btn-primary', 'name' => 'login-button']]) ?>
            </div>
            <?php ActiveForm::end(); ?>

            <div>
                <hr/>
            </div>

            <div class="text-center">
                <?= Html::a(Yii::t('app', 'Sign up'), ['/account/signup'], ['class' => 'btn btn-danger']); ?>
                <?= Html::a(Yii::t('app', 'Forgot password?'), ['/account/reset'], ['class' => 'btn btn-default']); ?>
            </div>

        </div>
    </div>
</div>