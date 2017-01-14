<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$resetLink = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/reset-confirm', 'token' => $user->password_reset_token]);
?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $resetLink ?>"/>
        <meta itemprop="name" content="<?= Yii::t('app', 'Reset Password') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::t('app', 'Reset your password') ?>"/>
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
                                        <?= Yii::t('app', 'Hello {FULL_NAME},', ['FULL_NAME' => Html::encode($user->fullname)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'You recently requested to reset your password. Click the button below to reset it:') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a class="btn-primary" href="<?= $resetLink ?>"><?= Yii::t('app', 'Reset Password') ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'If you did not request a password reset, please ignore this email.') ?>
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