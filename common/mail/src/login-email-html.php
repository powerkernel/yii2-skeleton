<?php

/* @var $this yii\web\View */
/* @var $login[] */


?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $login['link'] ?>"/>
        <meta itemprop="name" content="<?= Yii::t('app', 'Login') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::t('app', 'Login me in {APP}', ['APP'=>Yii::$app->name]) ?>"/>
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
                                        <?= Yii::t('app', 'Hello {NAME},', ['NAME' => $login['name']]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Here is the link for you to get into {APP}:', ['APP' => Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a href="<?= $login['link'] ?>" class="btn-primary"><?= Yii::t('app', 'Login') ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'The link expires in {MIN} minutes and can be used only once. Thanks for using {APP}!', ['MIN'=>$login['min'], 'APP'=>Yii::$app->name]) ?>
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