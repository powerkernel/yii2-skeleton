<?php
use common\models\Setting;
use \yii\helpers\Html;

/* @var $user \common\models\Account */


$loginUrl = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login']);


?>

<?= Yii::t('app', 'Hello {FULLNAME},', ['FULLNAME' => Html::encode($user->fullname)]) ?>


<?= Yii::t('app', 'Thank you for registering with {APPNAME}. Please note details of your account:', ['APPNAME' => Html::encode(Html::encode(\Yii::$app->name))]) ?>


<?= Yii::t('app', 'Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>

<?php if(!Setting::getValue('passwordLessLogin')):?>
<?= Yii::t('app', 'Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>
<?php endif;?>

<?= Yii::t('app', 'Click the link below to login') ?>


<?= $loginUrl ?>