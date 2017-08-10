<?php
/* @var $this \yii\web\View */
/* @var \common\models\Blog[] $models */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php if ($models): ?>
        <?php foreach ($models as $model): ?>
            <url>
                <loc><?= \yii\bootstrap\Html::encode($model->getViewUrl(true)) ?></loc>
                <lastmod><?= Yii::$app->formatter->asDate($model->updatedAt, 'php:Y-m-d') ?></lastmod>
            </url>
        <?php endforeach; ?>
    <?php endif; ?>
</urlset>

