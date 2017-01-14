<?php
use \yii\helpers\Html;

/* @var $user \common\models\Account */

$loginUrl=Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/account/login']);


?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $loginUrl ?>"/>
        <meta itemprop="name" content="<?= Yii::t('app', 'Welcome email') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::t('app', 'Welcome email') ?>"/>
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
                                        <?= Yii::t('app', 'Hello {FULLNAME},', ['FULLNAME' => Html::encode($user->fullname)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Thank you for registering with {APPNAME}. Please note details of your account:', ['APPNAME' => Html::encode(Html::encode(\Yii::$app->name))]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::t('app', 'Email: {EMAIL}', ['EMAIL' => Html::encode($user->email)]) ?>
                                        <br>
                                        <?= Yii::t('app', 'Password: {PASSWORD}', ['PASSWORD' => Html::encode($user->passwordText)]) ?>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a href="<?= $loginUrl ?>" class="btn-primary"><?= Yii::t('app', 'Login Now') ?></a>
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
