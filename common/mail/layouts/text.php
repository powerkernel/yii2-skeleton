<?php

/* @var $this \yii\web\View view component instance */
use yii\helpers\Html;

/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>


<?= Html::encode(Yii::$app->name) ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
