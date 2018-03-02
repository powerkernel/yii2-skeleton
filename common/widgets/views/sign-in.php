<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/* @var $model \common\models\SignInValidationForm */
/* @var $validation \common\models\SignInValidationForm */
/* @var $client boolean */

/* @var $this \yii\web\View */

use powerkernel\fontawesome\Icon;
use yii\bootstrap\ActiveForm;
use powerkernel\bootstrapsocial\Button;

?>
<div class="widget-login">
    <h1 class="text-center"
        style="margin: 0 0 10px 0; font-size: 1.6em; font-weight: bold"><?= Yii::t('app', 'Log in / Sign up') ?></h1>
    <div class="row">
        <div class="col-xs-12">
            <?php if (!Yii::$app->request->isPost): ?>
                <?php $form = ActiveForm::begin(['id' => 'sign-in-form', 'action' => Yii::$app->urlManager->createUrl(['account/signin'])]); ?>
                <?=
                $form->field($model, 'login',
                    [
                        'template' => "{label}\n<div class=\"input-group\"><div class=\"input-group-addon\">" . Icon::widget(['prefix'=>'fas', 'name' => 'user']) . "</div>{input}</div>{hint}\n{error}",
                    ]
                )
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('login'), 'maxlength' => true])
                ?>


                <?php //$form->field($model, 'captcha')->widget(ReCaptcha::className())->label(false) ?>

                <div class="form-group">

                    <?= \common\components\AjaxSubmitButton::widget([
                        'text' => Yii::t('app', 'Next'), 'options' => ['class' => 'btn btn-block btn-primary'],
                        'callback' => 'var obj = jQuery.parseJSON(data); $(".signin-alert").html(obj.message); $("#signinvalidationform-message").val(obj.message); $("#signinvalidationform-sid").val(obj.sid); $("#sign-in-form").addClass("hidden"); $("#validation-form").removeClass("hidden");'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            <?php endif; ?>


            <?php $form = ActiveForm::begin(['id' => 'validation-form', 'options' => ['class' => Yii::$app->request->isPost ? '' : 'hidden']]); ?>



            <?=
            $form->field($validation, 'sid')
                ->hiddenInput()
                ->label(false)
            ?>
            <?=
            $form->field($validation, 'message')
                ->hiddenInput()
                ->label(false)
            ?>

            <div class="alert alert-success signin-alert">
                <?= $validation->message ?>
            </div>


            <?=
            $form->field(
                $validation,
                'code'
            )
                ->label(false)
                ->textInput([
                    'maxlength' => 6,
                    'placeholder' => $model->getAttributeLabel('code')
                ])
            ?>

            <div class="form-group text-center">
                <?= \common\components\SubmitButton::widget(['text' => Yii::t('app', 'Sign In'), 'options' => ['class' => 'btn btn-primary']]) ?>
                <?php if (Yii::$app->request->isPost): ?>
                    <a href="<?= Yii::$app->request->getUrl() ?>"
                       class="btn btn-default"><?= Yii::t('app', 'Resend Code') ?></a>
                <?php endif; ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if ($client): ?>
        <div class="text-center" style="margin-bottom: 10px;"><strong><?= Yii::t('app', 'or log in with')?></strong></div>

        <div class="row">
            <div class="col-xs-12">
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
            </div>
        </div>
    <?php endif; ?>
</div>
