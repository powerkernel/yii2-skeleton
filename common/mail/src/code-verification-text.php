<?php

/* @var $this yii\web\View */
/* @var $model \common\models\CodeVerification */

?>


<?= Yii::t('app', 'Hello,') ?>


<?= Yii::t('app', 'Your verification code is: {CODE}', ['CODE' => $model->code]) ?>
