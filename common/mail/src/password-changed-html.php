<?php
use \yii\helpers\Html;
/* @var $user \common\models\Account */
$loginUrl=Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->user->loginUrl);
if(Yii::$app->id=='app-backend'){
    $loginUrl=Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login']);
}
?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $loginUrl ?>"/>
        <meta itemprop="name" content="<?= Yii::t('app', 'Password changed') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::t('app', 'Your password changed') ?>"/>
</div>
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Hello {USERNAME},', ['USERNAME' => Html::encode($user->fullname)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Your password has been changed:') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>
                                        <br/>
                                        <?= Yii::t('app', 'Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a href="<?= $loginUrl ?>" class="btn-primary"><?= Yii::t('app', 'Login Now') ?></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
        </td>
        <td></td>
    </tr>
</table>
<link href="src/css/mailgun.css" media="all" rel="stylesheet" type="text/css" />