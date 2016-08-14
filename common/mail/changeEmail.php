<?php

/* @var $this yii\web\View */
/* @var $user common\models\Account */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/account/email-confirm', 'token' => $user->change_email_token]);
?>
<table class="body-wrap" style="background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
    <tr>
        <td style="vertical-align: top;" valign="top"></td>
        <td class="container" width="600" style="vertical-align: top; display: block !important; max-width: 600px !important; margin: 0 auto !important; clear: both !important;" valign="top">
            <div class="content" itemprop="potentialAction" itemscope="" itemtype="http://schema.org/ViewAction" style="max-width: 600px; margin: 0 auto; display: block; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff; border: 1px solid #e9e9e9; border-radius: 3px;" bgcolor="#fff">
                    <tr>
                        <td class="content-wrap" style="vertical-align: top; padding: 20px;" valign="top">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::t('app', 'Hello {USERNAME},', ['USERNAME' => $user->fullname]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::t('app', 'You are requesting to change your email address at {APPNAME}:', ['APPNAME' => Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::t('app', 'To confirm, just click the button below:') ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <a href="<?= $confirmLink ?>" class="btn-primary" style="font-weight: bold; color: #FFF; background-color: #348eda; border: solid #348eda; border-width: 10px 20px; line-height: 2em; text-decoration: none; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize;"><?= Yii::t('app', 'Confirm') ?></a>
                                        <link itemprop="target" href="<?= $confirmLink ?>">
                                        <meta itemprop="name" content="<?= Yii::t('app', 'Confirm') ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::t('app', 'If you did not request this action, please ignore this email.') ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
            <meta itemprop="description" content="<?= Yii::t('app', 'Confirm your new email address') ?>">
        </td>
        <td style="vertical-align: top;" valign="top"></td>
    </tr>
</table>
