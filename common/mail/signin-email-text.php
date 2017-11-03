<?php

/* @var $this yii\web\View */
/* @var $model \common\models\SignIn */

?>


<?= Yii::t('app', 'Hello,') ?>


<?= Yii::t('app', 'Your login verification code is: {CODE}', ['CODE' => $model->code]) ?>
