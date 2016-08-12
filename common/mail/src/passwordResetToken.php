<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/reset-confirm', 'token' => $user->password_reset_token]);
?>
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="http://schema.org/ConfirmAction">
                    <tr>
                        <td class="content-wrap">
                            <meta itemprop="name" content="Reset Password">
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
                                    <td class="content-block" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler">
                                        <a class="btn-primary" href="<?= $resetLink ?>" itemprop="url"><?= Yii::t('app', 'Reset Password') ?></a>
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