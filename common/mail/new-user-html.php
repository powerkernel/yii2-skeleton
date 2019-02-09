<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

?>

<span class="preheader" style="margin: 0; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 1px; display: none; mso-hide: all; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; line-height: 1px;"><?= $model->email ?></span>
<table class="main" width="100%" cellpadding="0" cellspacing="0" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; background-color: #fff; border: 1px solid #e9e9e9; border-radius: 3px;" bgcolor="#fff">
    <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
        <td class="alert alert-primary" style="margin: 0; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; vertical-align: top; color: #fff; font-size: 16px; font-weight: 500; padding: 20px; text-align: center; border-radius: 3px 3px 0 0; background-color: #2196f3;" valign="top" align="center" bgcolor="#2196f3">
            <?= Yii::t('app', 'Registration Complete') ?>
        </td>
    </tr>
    <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
        <td class="content-wrap" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; padding: 20px;" valign="top">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                    <td class="content-block" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; padding: 0 0 20px;" valign="top">
                        <?= Yii::t('app', 'Hello,') ?>
                    </td>
                </tr>
                <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                    <td class="content-block" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; padding: 0 0 20px;" valign="top">
                        <?= Yii::t('app', 'You\'ve created a new account at {APP}.', ['APP'=>Yii::$app->name]) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
