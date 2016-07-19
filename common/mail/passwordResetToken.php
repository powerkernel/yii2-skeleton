<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user harrytang\account\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/reset/password', 'token' => $user->password_reset_token]);
?>
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
                    <tr>
                        <td class="content-wrap">
                            <meta itemprop="name" content="Confirm Email"/>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('Hello {USERNAME},', ['USERNAME' => Html::encode($user->username)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('You recently requested to reset your password. Click the button below to reset it:') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
                                        <?= Html::a(Yii::$app->getModule('account')->t('Reset Password'), $resetLink, ['class' => 'btn-primary', 'itemprop' => 'url']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('If you did not request a password reset, please ignore this email.') ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block">
                                <?= Html::encode(Yii::$app->name) ;?>
                            </td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>