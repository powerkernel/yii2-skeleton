<?php
use \yii\helpers\Html;

/* @var $user \common\models\Account */

$loginUrl = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login']);

?>

<?= Yii::t('app', 'Hello {USERNAME},', ['USERNAME' => Html::encode($user->fullname)]) ?>


<?= Yii::t('app', 'Your password has been changed:') ?>


<?= Yii::t('app', 'Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>

<?= Yii::t('app', 'Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>


<?= Yii::t('app', 'Click the link below to login') ?>

<?= $loginUrl ?>
