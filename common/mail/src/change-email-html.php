<?php

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/email-confirm', 'token' => $user->change_email_token]);
?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $confirmLink ?>"/>
        <meta itemprop="name" content="<?= Yii::t('app', 'Confirm') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::t('app', 'Confirm new email') ?>"/>
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
                                        <?= Yii::t('app', 'Hello {USERNAME},', ['USERNAME' => $user->fullname]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'You are requesting to change your email address at {APPNAME}:', ['APPNAME' => Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'To confirm, just click the button below:') ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td class="content-block">
                                        <a href="<?= $confirmLink ?>" class="btn-primary"><?= Yii::t('app', 'Confirm') ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'If you did not request this action, please ignore this email.') ?>
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