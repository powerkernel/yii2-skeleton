<footer class="main-footer">
    <div class="container">
        <?php if(Yii::$app->params['showPowered']===true):?>
        <div class="pull-right hidden-xs">
            <?= \common\Core::powered() ?>
        </div>
        <?php endif;?>
        <div><strong>&copy; <?= date('Y') ?> <?= Yii::$app->name ?>.</strong> <?= Yii::t('app', 'All rights reserved.') ?></div>
        <ul class="list-inline">
            <?php foreach($items as $item):?>
                <li><a href="<?= Yii::$app->urlManager->createUrl(preg_match('/\/\//', $item->url)?$item->url:$item->route) ?>"><?= $item->label ?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
</footer>