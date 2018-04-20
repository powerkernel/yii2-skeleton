<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\components\Addthis;
use common\components\GAnalytics;
use common\components\HeaderJS;
use common\widgets\Favicon;
use powerkernel\jsonld\JsonLDHelper;
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="<?= Yii::$app->language ?>">
<!--
+++++++++++++++++++++++++++++++++++++++++++
@author Harry Tang <harry@powerkernel.com>
@link https://powerkernel.com
@copyright Copyright (c) <?= date('Y') ?> Power Kernel
+++++++++++++++++++++++++++++++++++++++++++
-->
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= Favicon::widget() ?>
    <?php JsonLDHelper::registerScripts(); ?>
    <?php GAnalytics::register() ?>
    <?php Addthis::register() ?>
    <?php HeaderJS::register() ?>
</head>
<?= $content ?>
</html>
<?php $this->endPage() ?>