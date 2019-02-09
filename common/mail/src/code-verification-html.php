<?php
/* @var $this yii\web\View */
/* @var $model \common\models\CodeVerification */
?>
<span class="preheader"><?= Yii::t('app', '{CODE} is your verification code.', ['CODE' => $model->code]) ?></span>
<table class="main" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="alert alert-primary">
            <?= Yii::t('app', 'Verification') ?>
        </td>
    </tr>
    <tr>
        <td class="content-wrap">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="content-block">
                        <?= Yii::t('app', 'Hello,') ?>
                    </td>
                </tr>
                <tr>
                    <td class="content-block">
                        <?= Yii::t('app', 'Your verification code is: {CODE}', ['CODE' => $model->code]) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<link href="css/styles.css" media="all" rel="stylesheet" type="text/css"/>
