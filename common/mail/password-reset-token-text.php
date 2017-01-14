<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$resetLink = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/reset-confirm', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app', 'Hello {FULL_NAME},', ['FULL_NAME' => Html::encode($user->fullname)]) ?>


<?= Yii::t('app', 'You recently requested to reset your password. Click the button below to reset it:') ?>

<?= $resetLink ?>


<?= Yii::t('app', 'If you did not request a password reset, please ignore this email.') ?>
