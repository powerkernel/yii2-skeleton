<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\models\Setting;
use modernkernel\bootstrapsocial\Button;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="widget-login">
    <div class="row">
        <div class="col-xs-12">
            <?php if(\common\Core::checkMCA(null,'account', 'login')):?>
            <h1 class="box-title text-center"><?= Yii::t('app', 'Login / Register') ?></h1>
            <div><hr /></div>
            <?php endif;?>
            <?php if(Yii::$app->authClientCollection->hasClient('facebook')):?>
                <?= Button::widget([
                    'button' => 'facebook',
                    'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'facebook']),
                    'label'=> Yii::t('app', 'Login with Facebook')
                ]) ?>
            <?php endif;?>
            <?php if(Yii::$app->authClientCollection->hasClient('google')):?>
                <?= Button::widget([
                    'button' => 'google',
                    'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'google']),
                    'label'=> Yii::t('app', 'Login with Google')
                ]) ?>
            <?php endif;?>

            <div>
                <hr/>
            </div>
            <?php if(!Yii::$app->session->hasFlash('info')):?>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'email') ?>
                <?php if($model->scenario=='default'):?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif;?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('app', $model->scenario=='default'?'Login':'Continue'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif;?>

            <?php if(!Setting::getValue('passwordLessLogin')):?>
                <div>
                    <hr/>
                </div>

                <div class="text-center">
                    <?= Html::a(Yii::t('app', 'Sign up'), ['/account/signup'], ['class' => 'btn btn-danger']); ?>
                    <?= Html::a(Yii::t('app', 'Forgot password?'), ['/account/reset'], ['class' => 'btn btn-default']); ?>
                </div>
            <?php endif;?>

        </div>
    </div>
</div>