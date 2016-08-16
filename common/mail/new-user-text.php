<?php
use \yii\helpers\Html;

/* @var $user \common\models\Account */

$loginUrl = Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->user->loginUrl);
if (Yii::$app->id == 'app-backend') {
    $loginUrl = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login']);
}

?>

<?= Yii::t('app', 'Hello {FULLNAME},', ['FULLNAME' => Html::encode($user->fullname)]) ?>


<?= Yii::t('app', 'Thank you for registering with {APPNAME}. Please note details of your account:', ['APPNAME' => Html::encode(Html::encode(\Yii::$app->name))]) ?>


<?= Yii::t('app', 'Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>

<?= Yii::t('app', 'Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>


<?= Yii::t('app', 'Click the link below to login') ?>


<?= $loginUrl ?>