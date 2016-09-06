<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Html;

/* @var $model common\models\Blog */
/* @var integer $index */

?>
<div class="col-sm-4">
    <div class="box box-primary">
        <div class="box-header">
            <h2 class="box-title"><?= $model->title ?></h2>
        </div>
        <div class="box-body">
            <p><img src="<?= $model->thumbnail ?>" alt="<?= $model->title ?>" class="img-responsive"></p>
            <p><?= $model->desc ?></p>
            <p><?= Html::a(Yii::t('app', 'Read') . ' ' . Icon::widget(['icon' => 'long-arrow-right']), $model->viewUrl, ['class' => 'btn btn-info btn-xs']) ?></p>
        </div>
        <div class="box-footer">
            <div class="box-tools pull-right">
                <small class="text-muted">
                    <?= Yii::t('app', 'By {AUTHOR}, last updated {DATE}', ['AUTHOR' => $model->author->fullname, 'DATE' => Yii::$app->formatter->asDate($model->published_at)]) ?>
                </small>
            </div>
        </div>
    </div>
</div>
<?php if (($index + 1) % 3 == 0): ?>
    <div class="clearfix hidden-xs"></div>
<?php endif; ?>
