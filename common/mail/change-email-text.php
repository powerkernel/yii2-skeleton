<?php

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/email-confirm', 'token' => $user->change_email_token]);
?>

<?= Yii::t('app', 'Hello {USERNAME},', ['USERNAME' => $user->fullname]) ?>


<?= Yii::t('app', 'You are requesting to change your email address at {APPNAME}:', ['APPNAME' => Yii::$app->name]) ?>


<?= Yii::t('app', 'To confirm, just click the link below:') ?>

<?= $confirmLink ?>


<?= Yii::t('app', 'If you did not request this action, please ignore this email.') ?>

