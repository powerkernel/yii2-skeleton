<?php

/* @var $this yii\web\View */
/* @var $login[] */

?>


<?= Yii::t('app', 'Hello {NAME},', ['NAME' => $login['name']]) ?>


<?= Yii::t('app', 'Here is the link for you to get into {APP}:', ['APP' => Yii::$app->name]) ?>


<?= $login['link'] ?>


<?= Yii::t('app', 'The link expires in {MIN} minutes and can be used only once. Thanks for using {APP}!', ['MIN'=>$login['min'], 'APP'=>Yii::$app->name]) ?>

