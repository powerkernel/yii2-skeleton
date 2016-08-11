<?php
use \yii\helpers\Html;

?>
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
                                        <?= Yii::$app->getModule('account')->t('Hello {USERNAME},', ['USERNAME' => Html::encode($user->username)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('Your password has been changed:') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('account')->t('Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>
                                        <br>
                                        <?= Yii::$app->getModule('account')->t('Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>
                                        <br>
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
                                <?= Html::encode(Html::encode(\Yii::$app->name)) ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td></td>
    </tr>
</table>

