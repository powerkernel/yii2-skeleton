<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use common\Core;
use himiklab\yii2\recaptcha\ReCaptcha;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model frontend\models\ChangePhoneForm */

$this->title = Yii::t('app', 'Change Phone');
$keywords = Yii::t('app', 'phone, change phone, new phone');
$description = Yii::t('app', 'View and update your phone number');

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

/* breadcrumbs */
//$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => '#'];
?>
<div class="account-email">
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title"><?= $this->title ?></h1>
        </div>
        <div class="box-body">
            <div class="account-form">
                <p><?= Yii::t('app', 'Please enter your new, valid phone number, we will send a verification code to your new phone.') ?></p>
                <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['account/phone', 'act' => 'validate'])]); ?>
                <?=
                $form->field($model, 'phone')
                    ->textInput(['readOnly' => $model->scenario == 'validation'])
                    ->hint(Yii::t('app', 'Phone number should begin with a country prefix code'))
                ?>
                <?php if ($model->scenario == 'validation'): ?>
                    <?= $form->field($model, 'code')->textInput(['maxlength' => 6]) ?>
                <?php endif; ?>
                <?php if (Core::isReCaptchaEnabled()): ?>
                    <?= $form->field($model, 'captcha')->widget(ReCaptcha::className())->label(false) ?>
                <?php endif; ?>
                <div class="form-group">
                    <?php if ($model->scenario != 'validation'): ?>
                        <?= \common\components\SubmitButton::widget(['text' => Yii::t('app', 'Send Code'), 'options' => ['class' => 'btn btn-primary', 'name' => 'send-code']]) ?>
                    <?php else: ?>
                        <?= \common\components\SubmitButton::widget(['text' => Yii::t('app', 'Verify'), 'options' => ['class' => 'btn btn-primary', 'name' => 'validate']]) ?>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
