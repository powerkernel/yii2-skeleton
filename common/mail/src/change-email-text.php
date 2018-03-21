<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

?>


<?= Yii::t('app', 'Hello,') ?>


<?= Yii::t('app', 'Your verification code is: {CODE}', ['CODE' => $model->new_email_code]) ?>
