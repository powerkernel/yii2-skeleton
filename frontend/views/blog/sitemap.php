<?php
/* @var $this \yii\web\View */
use yii\helpers\Url;

/* @var \common\models\Blog[] $models */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php if ($models): ?>
        <?php foreach ($models as $model): ?>
            <url>
                <loc><?= \yii\bootstrap\Html::encode($model->getViewUrl(true)) ?></loc>
                <lastmod><?= Yii::$app->formatter->asDate($model->updated_at, 'php:c') ?></lastmod>
            </url>
        <?php endforeach; ?>
    <?php endif; ?>
</urlset>

