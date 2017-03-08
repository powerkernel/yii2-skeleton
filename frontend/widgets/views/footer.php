<footer class="main-footer">
    <div class="container">
        <?php if(Yii::$app->params['showPowered']===true):?>
        <div class="pull-right hidden-xs">
            <?= \common\Core::powered() ?>
        </div>
        <?php endif;?>
        <strong><?= Yii::t('app', 'Copyright') ?> &copy; <?= date('Y') ?> <?= Yii::$app->name ?>.</strong> <?= Yii::t('app', 'All rights reserved.') ?>
    </div>
</footer>