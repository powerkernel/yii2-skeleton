<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/* @var $model \common\models\CodeVerification */
/* @var $validation \common\forms\CodeVerificationForm */
/* @var $client boolean */

/* @var $this \yii\web\View */

use powerkernel\fontawesome\Icon;
use yii\bootstrap\ActiveForm;

?>
<div class="widget-login">
    <h1 class="text-center"
        style="margin: 0 0 10px 0; font-size: 1.6em; font-weight: bold"><?= Yii::t('app', 'Log in / Sign up') ?></h1>
    <div class="row">
        <div class="col-xs-12">
            <?php if (!Yii::$app->request->isPost): ?>
                <?php $form = ActiveForm::begin(['id' => 'sign-in-form', 'action' => Yii::$app->urlManager->createUrl(['account/signin'])]); ?>
                <?=
                    $form->field($model, 'identifier',
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
                        'callback' => 'var obj = jQuery.parseJSON(data); $(".signin-alert").html(obj.message); $("#codeverificationform-message").val(obj.message); $("#codeverificationform-vid").val(obj.vid); $("#sign-in-form").addClass("hidden"); $("#validation-form").removeClass("hidden");'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>


            <?php $form = ActiveForm::begin(['id' => 'validation-form', 'options' => ['class' => Yii::$app->request->isPost ? '' : 'hidden']]); ?>
            <?=
            $form->field($validation, 'vid')
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
            <div class="col-xs-12 text-center">
                <?php if (Yii::$app->authClientCollection->hasClient('facebook')): ?>
                    <a title="Facebook" style="color: #3B5998;" href="<?= Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'facebook']) ?>" class="btn btn-default">
                        <?= Icon::widget(['prefix'=>'fab', 'name'=>'facebook', 'size'=>'fa-3x']) ?>
                        <?php // Yii::t('app', 'Login with Facebook') ?>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->authClientCollection->hasClient('google')): ?>
                    <a title="Google" style="color: #EA4335;" href="<?= Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'google']) ?>" class="btn btn-default">
                        <?= Icon::widget(['prefix'=>'fab', 'name'=>'google', 'size'=>'fa-3x']) ?>
                        <?php // Yii::t('app', 'Login with Google') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
