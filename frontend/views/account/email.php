<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
use common\Core;
use himiklab\yii2\recaptcha\ReCaptcha;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $model frontend\models\ChangeEmailForm */

$this->title = Yii::t('app', 'Change Email');
$keywords = Yii::t('app', 'email, change email, new email');
$description = Yii::t('app', 'View and change your email here');

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
                <p><?= Yii::t('app', 'Please enter your new, valid email address, we will send a verification link to your new email.') ?></p>
                <p>
                    <span class="label label-default"><?= Yii::t('app', 'Current email')?></span> <?= Yii::$app->user->identity->email ?>
                </p>
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'newEmail')->textInput(['type'=>'email']) ?>
                <?php if(Core::isReCaptchaEnabled()):?>
                <?= $form->field($model, 'verifyCode')->widget(ReCaptcha::className())->label(false) ?>
                <?php endif;?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary']) ?>
                </div
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
