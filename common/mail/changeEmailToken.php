<?php
use yii\helpers\Html;
use harrytang\account\AccountModule;

/* @var $this yii\web\View */
/* @var $user harrytang\account\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/email/confirm', 'token' => $user->change_email_token]);
?>
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope
                       itemtype="http://schema.org/ConfirmAction">
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
                                        <?= Yii::$app->getModule('account')->t('You are requesting to change your email address at {APPNAME}:', ['APPNAME' => Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('Old email: {OLDEMAIL}', ['OLDEMAIL' => $user->email]) ?>
                                        <br/>
                                        <?= Yii::$app->getModule('account')->t('New email: {NEWEMAIL}', ['NEWEMAIL' => $user->new_email]) ?>
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('To confirm, just click the button below:') ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td class="content-block" itemprop="handler" itemscope
                                        itemtype="http://schema.org/HttpActionHandler">
                                        <?= Html::a(Yii::$app->getModule('account')->t('Confirm'), $confirmLink, ['class' => 'btn-primary', 'itemprop' => 'url']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('If you did not request this action, please ignore this email.') ?>
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
                                <?= Html::encode(Yii::$app->name); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td></td>
    </tr>
</table>


