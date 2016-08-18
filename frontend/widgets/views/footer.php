<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">
            <b>Time</b> <?= Yii::$app->formatter->asDatetime(time()) ?>
        </div>
        <strong><?= Yii::t('app', 'Copyright') ?> &copy; <?= date('Y') ?> <?= Yii::$app->name ?>.</strong> <?= Yii::t('app', 'All rights reserved.') ?>
    </div>
</footer>